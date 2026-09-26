/*
 * Home page motion & interaction (vanilla, no dependencies).
 * The page is fully readable without this file: the inline guard in
 * home.blade.php only hides [data-reveal] content once motion is allowed,
 * and reveals everything if this script fails to run.
 */
import '../css/home.css';

const root = document.documentElement;
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(pointer: fine)').matches;
const lerp = (a, b, t) => a + (b - a) * t;
const clamp = (v, min, max) => Math.min(max, Math.max(min, v));

window.__homeMotion = true;

/* ------------------------------------------------------------------ */
/* Split headings into words (keeps inline accent spans)               */
/* ------------------------------------------------------------------ */
function splitWords(el) {
    let index = 0;
    const makeWord = (text, className) => {
        const outer = document.createElement('span');
        outer.className = 'split-word';
        const inner = document.createElement('span');
        if (className) inner.className = className;
        inner.textContent = text;
        inner.style.setProperty('--i', index++);
        outer.appendChild(inner);
        return outer;
    };

    const fragment = document.createDocumentFragment();
    [...el.childNodes].forEach((node) => {
        if (node.nodeType === Node.TEXT_NODE) {
            node.textContent.split(/(\s+)/).forEach((part) => {
                if (!part) return;
                fragment.appendChild(/^\s+$/.test(part) ? document.createTextNode(' ') : makeWord(part));
            });
        } else if (node.nodeName === 'BR') {
            fragment.appendChild(node.cloneNode());
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            // Keep an accent phrase (e.g. "like you.") together on one line.
            const group = document.createElement('span');
            group.className = 'split-group';
            node.textContent.split(/(\s+)/).forEach((part) => {
                if (!part) return;
                group.appendChild(/^\s+$/.test(part) ? document.createTextNode(' ') : makeWord(part, node.className));
            });
            fragment.appendChild(group);
        }
    });
    el.setAttribute('aria-label', el.textContent.replace(/\s+/g, ' ').trim());
    el.replaceChildren(fragment);
    [...el.children].forEach((child) => child.setAttribute('aria-hidden', 'true'));
    el.querySelectorAll('.split-word').forEach((w) => w.setAttribute('aria-hidden', 'true'));
}

document.querySelectorAll('[data-split]').forEach(splitWords);

/* ------------------------------------------------------------------ */
/* Reveal on scroll                                                    */
/* ------------------------------------------------------------------ */
document.querySelectorAll('[data-reveal-group]').forEach((group) => {
    group.querySelectorAll('[data-reveal]').forEach((el, i) => {
        el.style.setProperty('--reveal-delay', `${Math.min(i, 8) * 90}ms`);
    });
});

const revealTargets = document.querySelectorAll('[data-reveal], [data-split]');
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
    revealTargets.forEach((el) => observer.observe(el));
} else {
    revealTargets.forEach((el) => el.classList.add('is-in'));
}

/* ------------------------------------------------------------------ */
/* Scroll-driven effects                                               */
/* ------------------------------------------------------------------ */
const progressBar = document.querySelector('.scroll-progress');
const statement = document.querySelector('[data-fill]');
const steps = document.querySelector('[data-steps]');
let lastScrollY = window.scrollY;
let scrollVelocity = 0;

if (statement) {
    const words = statement.textContent.trim().split(/\s+/);
    const accentWords = (statement.dataset.accent || '').toLowerCase().split(',').map((w) => w.trim()).filter(Boolean);
    statement.setAttribute('aria-label', statement.textContent.trim());
    statement.replaceChildren(...words.flatMap((word, i) => {
        const span = document.createElement('span');
        span.className = 'fill-word' + (accentWords.includes(word.toLowerCase().replace(/[^\w]/g, '')) ? ' accent' : '');
        span.textContent = word;
        span.setAttribute('aria-hidden', 'true');
        return i < words.length - 1 ? [span, document.createTextNode(' ')] : [span];
    }));
}
const statementWords = statement ? [...statement.querySelectorAll('.fill-word')] : [];
const stepItems = steps ? [...steps.querySelectorAll('.step')] : [];

function sectionProgress(el, start = 0.85, end = 0.35) {
    const rect = el.getBoundingClientRect();
    const vh = window.innerHeight;
    // 0 when the top reaches `start` of the viewport, 1 when the bottom reaches `end`.
    const total = rect.height + vh * (start - end);
    return clamp((vh * start - rect.top) / total, 0, 1);
}

function updateScrollEffects() {
    const y = window.scrollY;

    if (progressBar) {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progressBar.style.setProperty('--progress', max > 0 ? (y / max).toFixed(4) : 0);
    }

    if (statement) {
        const lit = Math.round(sectionProgress(statement, 0.9, 0.45) * statementWords.length);
        statementWords.forEach((w, i) => w.classList.toggle('is-lit', reduceMotion || i < lit));
    }

    if (steps) {
        const p = reduceMotion ? 1 : sectionProgress(steps, 0.8, 0.5);
        steps.style.setProperty('--line', p.toFixed(3));
        stepItems.forEach((step, i) => {
            step.classList.toggle('is-active', p >= (stepItems.length === 1 ? 0 : i / (stepItems.length - 1)) - 0.02);
        });
    }
}

/* ------------------------------------------------------------------ */
/* Marquee: a compositor animation (no per-frame JS); scrolling only    */
/* nudges its speed and direction.                                     */
/* ------------------------------------------------------------------ */
const marqueeTrack = document.querySelector('.marquee-track');
const marqueeAnim = (marqueeTrack && !reduceMotion && marqueeTrack.animate)
    ? marqueeTrack.animate(
        [{ transform: 'translate3d(0, 0, 0)' }, { transform: 'translate3d(-50%, 0, 0)' }],
        { duration: 42000, iterations: Infinity },
    )
    : null;
let marqueeDir = 1;

function updateMarquee() {
    if (!marqueeAnim) return;
    if (Math.abs(scrollVelocity) > 0.5) marqueeDir = scrollVelocity > 0 ? 1 : -1;
    const boost = 1 + Math.min(Math.abs(scrollVelocity) * 0.12, 5);
    marqueeAnim.playbackRate = marqueeDir * boost;
}

/* ------------------------------------------------------------------ */
/* Hero: parallax image + glow following mouse or finger               */
/* ------------------------------------------------------------------ */
const hero = document.querySelector('.hero');
const heroMedia = hero?.querySelector('.hero-media');
const heroGlow = hero?.querySelector('.hero-glow');
const REST = { x: 0.66, y: 0.42 };
const target = { ...REST };
const current = { ...REST };
let heroVisible = true;

function applyHero() {
    if (!hero) return;
    const rect = hero.getBoundingClientRect();
    heroGlow?.style.setProperty('--gx', `${(current.x * rect.width).toFixed(1)}px`);
    heroGlow?.style.setProperty('--gy', `${(current.y * rect.height).toFixed(1)}px`);
    heroMedia?.style.setProperty('--px', `${((0.5 - current.x) * 36).toFixed(2)}px`);
    heroMedia?.style.setProperty('--py', `${((0.5 - current.y) * 24 + window.scrollY * 0.15).toFixed(2)}px`);
}

// Returns true while the glow/parallax is still easing towards its target.
function stepHero() {
    if (!hero || !heroVisible) return false;
    current.x = lerp(current.x, target.x, 0.08);
    current.y = lerp(current.y, target.y, 0.08);
    applyHero();
    return Math.abs(current.x - target.x) > 0.001 || Math.abs(current.y - target.y) > 0.001;
}

/* ------------------------------------------------------------------ */
/* On-demand loop: runs only while something is moving, then sleeps     */
/* ------------------------------------------------------------------ */
let rafId = null;

function frame() {
    rafId = null;
    const y = window.scrollY;
    scrollVelocity = lerp(scrollVelocity, y - lastScrollY, 0.25);
    const scrolled = y !== lastScrollY;
    lastScrollY = y;

    if (scrolled) updateScrollEffects();
    updateMarquee();
    const heroMoving = reduceMotion ? false : stepHero();

    if (scrolled || heroMoving || Math.abs(scrollVelocity) > 0.05) {
        rafId = requestAnimationFrame(frame);
    } else {
        scrollVelocity = 0;
        updateMarquee();
    }
}

function wake() {
    if (rafId === null) rafId = requestAnimationFrame(frame);
}

window.addEventListener('scroll', wake, { passive: true });
window.addEventListener('resize', () => { updateScrollEffects(); wake(); }, { passive: true });

if (hero && !reduceMotion) {
    const setTarget = (clientX, clientY) => {
        const rect = hero.getBoundingClientRect();
        target.x = clamp((clientX - rect.left) / rect.width, 0, 1);
        target.y = clamp((clientY - rect.top) / rect.height, 0, 1);
        wake();
    };
    const rest = () => { target.x = REST.x; target.y = REST.y; wake(); };
    hero.addEventListener('pointermove', (e) => setTarget(e.clientX, e.clientY), { passive: true });
    hero.addEventListener('pointerdown', (e) => setTarget(e.clientX, e.clientY), { passive: true });
    hero.addEventListener('pointerleave', rest, { passive: true });
    hero.addEventListener('touchmove', (e) => {
        const t = e.touches[0];
        if (t) setTarget(t.clientX, t.clientY);
    }, { passive: true });
    hero.addEventListener('touchend', rest, { passive: true });

    new IntersectionObserver(([entry]) => { heroVisible = entry.isIntersecting; }).observe(hero);
}

/* ------------------------------------------------------------------ */
/* Magnetic buttons (mouse only)                                       */
/* ------------------------------------------------------------------ */
if (finePointer && !reduceMotion) {
    document.querySelectorAll('.btn-magnetic').forEach((btn) => {
        btn.addEventListener('pointermove', (e) => {
            const r = btn.getBoundingClientRect();
            btn.style.setProperty('--mx', `${((e.clientX - r.left - r.width / 2) * 0.28).toFixed(1)}px`);
            btn.style.setProperty('--my', `${((e.clientY - r.top - r.height / 2) * 0.4).toFixed(1)}px`);
        });
        btn.addEventListener('pointerleave', () => {
            btn.style.setProperty('--mx', '0px');
            btn.style.setProperty('--my', '0px');
        });
    });
}

/* ------------------------------------------------------------------ */
/* 3D tilt (mouse hover, or while a finger is pressing)                */
/* ------------------------------------------------------------------ */
if (!reduceMotion) {
    document.querySelectorAll('.tilt').forEach((card) => {
        const move = (e) => {
            if (e.pointerType === 'touch' && !card.classList.contains('is-tilting')) return;
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            card.style.setProperty('--ry', `${((px - 0.5) * 12).toFixed(2)}deg`);
            card.style.setProperty('--rx', `${((0.5 - py) * 10).toFixed(2)}deg`);
            card.style.setProperty('--sx', `${(px * 100).toFixed(1)}%`);
            card.style.setProperty('--sy', `${(py * 100).toFixed(1)}%`);
        };
        const reset = () => {
            card.classList.remove('is-tilting');
            card.style.setProperty('--rx', '0deg');
            card.style.setProperty('--ry', '0deg');
        };
        card.addEventListener('pointerenter', (e) => { if (e.pointerType !== 'touch') card.classList.add('is-tilting'); });
        card.addEventListener('pointerdown', (e) => { card.classList.add('is-tilting'); move(e); });
        card.addEventListener('pointermove', move);
        card.addEventListener('pointerleave', reset);
        card.addEventListener('pointerup', (e) => { if (e.pointerType === 'touch') reset(); });
        card.addEventListener('pointercancel', reset);
    });
}

/* ------------------------------------------------------------------ */
/* Spotlight border that follows the pointer                           */
/* ------------------------------------------------------------------ */
document.querySelectorAll('.spotlight').forEach((card) => {
    const set = (e) => {
        const r = card.getBoundingClientRect();
        card.style.setProperty('--cx', `${e.clientX - r.left}px`);
        card.style.setProperty('--cy', `${e.clientY - r.top}px`);
    };
    card.addEventListener('pointermove', set, { passive: true });
    card.addEventListener('pointerdown', (e) => {
        set(e);
        card.classList.add('is-touched');
        window.setTimeout(() => card.classList.remove('is-touched'), 900);
    }, { passive: true });
});

/* ------------------------------------------------------------------ */
/* Pause decorative animations while their section is off screen        */
/* ------------------------------------------------------------------ */
if ('IntersectionObserver' in window) {
    const offscreen = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            entry.target.classList.toggle('is-offscreen', !entry.isIntersecting);
            if (marqueeAnim && entry.target.classList.contains('marquee')) {
                entry.isIntersecting ? marqueeAnim.play() : marqueeAnim.pause();
            }
        });
    });
    document.querySelectorAll('.hero, .marquee, .cta-band').forEach((el) => offscreen.observe(el));
}

/* ------------------------------------------------------------------ */
/* Initial state                                                       */
/* ------------------------------------------------------------------ */
updateScrollEffects();
applyHero();

// Above-the-fold hero content animates in on load, without waiting for the
// intersection observer (which browsers pause in background tabs).
hero?.querySelectorAll('[data-reveal], [data-split]').forEach((el) => el.classList.add('is-in'));
