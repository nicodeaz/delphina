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
/* Shared scroll loop                                                  */
/* ------------------------------------------------------------------ */
const progressBar = document.querySelector('.scroll-progress');
const statement = document.querySelector('[data-fill]');
const steps = document.querySelector('[data-steps]');
let lastScrollY = window.scrollY;
let scrollVelocity = 0;
let marqueeHalf = 0;

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

function sectionProgress(el, start = 0.85, end = 0.35) {
    const rect = el.getBoundingClientRect();
    const vh = window.innerHeight;
    // 0 when the top reaches `start` of the viewport, 1 when the bottom reaches `end`.
    const total = rect.height + vh * (start - end);
    return clamp((vh * start - rect.top) / total, 0, 1);
}

let layoutDirty = true;
window.addEventListener('resize', () => { layoutDirty = true; marqueeHalf = 0; }, { passive: true });

function onScrollFrame() {
    const y = window.scrollY;
    scrollVelocity = lerp(scrollVelocity, y - lastScrollY, 0.2);
    const moved = y !== lastScrollY;
    lastScrollY = y;
    if (!moved && !layoutDirty) return;
    layoutDirty = false;

    if (progressBar) {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progressBar.style.setProperty('--progress', max > 0 ? (y / max).toFixed(4) : 0);
    }

    if (statement) {
        const words = statement.querySelectorAll('.fill-word');
        const lit = Math.round(sectionProgress(statement, 0.9, 0.45) * words.length);
        words.forEach((w, i) => w.classList.toggle('is-lit', reduceMotion || i < lit));
    }

    if (steps) {
        const p = reduceMotion ? 1 : sectionProgress(steps, 0.8, 0.5);
        steps.style.setProperty('--line', p.toFixed(3));
        steps.querySelectorAll('.step').forEach((step, i, all) => {
            step.classList.toggle('is-active', p >= (all.length === 1 ? 0 : i / (all.length - 1)) - 0.02);
        });
    }
}

/* ------------------------------------------------------------------ */
/* Marquee: steady drift, nudged by scroll speed & direction           */
/* ------------------------------------------------------------------ */
const marquee = document.querySelector('.marquee-track');
let marqueeX = 0;
let marqueeDir = -1;

function marqueeFrame() {
    if (!marquee) return;
    if (!marqueeHalf) marqueeHalf = marquee.scrollWidth / 2;
    const half = marqueeHalf;
    if (Math.abs(scrollVelocity) > 0.5) marqueeDir = scrollVelocity > 0 ? -1 : 1;
    const speed = 0.6 + Math.min(Math.abs(scrollVelocity) * 0.35, 12);
    marqueeX += marqueeDir * speed;
    if (marqueeX <= -half) marqueeX += half;
    if (marqueeX > 0) marqueeX -= half;
    marquee.style.setProperty('--marquee-x', `${marqueeX.toFixed(2)}px`);
}

/* ------------------------------------------------------------------ */
/* Hero: parallax image + glow following mouse or finger               */
/* ------------------------------------------------------------------ */
const hero = document.querySelector('.hero');
const heroMedia = hero?.querySelector('.hero-media');
const heroGlow = hero?.querySelector('.hero-glow');
const target = { x: 0.5, y: 0.45, active: false };
const current = { x: 0.5, y: 0.45 };
let heroVisible = true;
let idleT = 0;

if (hero) {
    const setTarget = (clientX, clientY) => {
        const rect = hero.getBoundingClientRect();
        target.x = clamp((clientX - rect.left) / rect.width, 0, 1);
        target.y = clamp((clientY - rect.top) / rect.height, 0, 1);
        target.active = true;
    };
    hero.addEventListener('pointermove', (e) => setTarget(e.clientX, e.clientY), { passive: true });
    hero.addEventListener('pointerdown', (e) => setTarget(e.clientX, e.clientY), { passive: true });
    hero.addEventListener('pointerleave', () => { target.active = false; }, { passive: true });
    hero.addEventListener('touchmove', (e) => {
        const t = e.touches[0];
        if (t) setTarget(t.clientX, t.clientY);
    }, { passive: true });
    hero.addEventListener('touchend', () => { target.active = false; }, { passive: true });

    new IntersectionObserver(([entry]) => { heroVisible = entry.isIntersecting; }).observe(hero);
}

function heroFrame() {
    if (!hero || !heroVisible) return;
    if (!target.active) {
        // Gentle idle orbit so the glow feels alive on touch screens too.
        idleT += 0.006;
        target.x = 0.62 + Math.cos(idleT) * 0.16;
        target.y = 0.45 + Math.sin(idleT * 1.3) * 0.18;
    }
    current.x = lerp(current.x, target.x, 0.07);
    current.y = lerp(current.y, target.y, 0.07);

    const rect = hero.getBoundingClientRect();
    heroGlow?.style.setProperty('--gx', `${(current.x * rect.width).toFixed(1)}px`);
    heroGlow?.style.setProperty('--gy', `${(current.y * rect.height).toFixed(1)}px`);
    heroMedia?.style.setProperty('--px', `${((0.5 - current.x) * 36).toFixed(2)}px`);
    heroMedia?.style.setProperty('--py', `${((0.5 - current.y) * 24 + window.scrollY * 0.15).toFixed(2)}px`);
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
/* Main loop                                                           */
/* ------------------------------------------------------------------ */
function frame() {
    onScrollFrame();
    if (!reduceMotion) {
        marqueeFrame();
        heroFrame();
    }
    requestAnimationFrame(frame);
}

onScrollFrame();
requestAnimationFrame(frame);

// Above-the-fold hero content animates in on load, without waiting for the
// intersection observer (which browsers pause in background tabs).
hero?.querySelectorAll('[data-reveal], [data-split]').forEach((el) => el.classList.add('is-in'));
