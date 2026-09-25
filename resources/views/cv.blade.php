<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Maria Delfina Mendonça - Nail Technician CV</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-green: #554F13;
            --brand-green-deep: #413B0E;
            --brand-green-soft: #8D8540;
            --brand-surface: #FCFBF6;
            --brand-text: #2F2A09;
            --brand-muted: #5E5720;
            --rose: #E8C4D4;
            --nude: #F5E6D3;
            --beige-50: #FBF7F1;
            --beige-100: #F2EBDD;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--brand-text);
            background: var(--beige-100);
            font-size: 13.5px;
            line-height: 1.55;
        }

        h1 {
            font-family: 'Ahsing', 'Cormorant Garamond', serif;
            letter-spacing: 0.02em;
            margin: 0;
        }

        .page {
            max-width: 900px;
            margin: 2rem auto;
            background: var(--brand-surface);
            border-radius: 1.5rem;
            box-shadow: 0 20px 50px rgba(65, 59, 14, 0.15);
            overflow: hidden;
        }

        /* ---- Toolbar (screen only) ---- */
        .toolbar {
            max-width: 900px;
            margin: 1.25rem auto 0;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn-print {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: #fff;
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-soft));
            border: none;
            border-radius: 0.75rem;
            padding: 0.6rem 1.1rem;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(65, 59, 14, 0.25);
        }

        .btn-print:hover {
            filter: brightness(1.05);
        }

        /* ---- Header ---- */
        .cv-header {
            background: linear-gradient(135deg, var(--nude) 0%, var(--rose) 100%);
            padding: 2.25rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .cv-header .identity h1 {
            font-size: 2.1rem;
            color: var(--brand-green-deep);
        }

        .cv-header .identity .role {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-size: 0.78rem;
            color: var(--brand-green);
            margin: 0.4rem 0 0;
        }

        .cv-header .contact {
            text-align: right;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--brand-green-deep);
        }

        .cv-header .contact div {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.55rem;
            margin-bottom: 0.45rem;
        }

        .cv-header .contact div:last-child {
            margin-bottom: 0;
        }

        .cv-header .contact i {
            color: var(--brand-green);
            font-size: 0.95rem;
            width: 17px;
            text-align: center;
        }

        /* ---- Body grid ---- */
        .cv-body {
            display: grid;
            grid-template-columns: 1fr 1.55fr;
        }

        .col-left {
            background: var(--beige-50);
            padding: 2rem 1.9rem;
            border-right: 1px solid var(--beige-100);
        }

        .col-right {
            padding: 2rem 2.25rem;
        }

        .section {
            margin-bottom: 1.6rem;
        }

        .section:last-child {
            margin-bottom: 0;
        }

        .pill-label {
            display: inline-block;
            background: var(--rose);
            color: var(--brand-green-deep);
            font-family: 'Ahsing', 'Cormorant Garamond', serif;
            font-size: 1.05rem;
            font-weight: 600;
            padding: 0.35rem 1.1rem;
            border-radius: 999px;
            margin-bottom: 0.85rem;
        }

        .col-left .pill-label {
            background: var(--brand-green);
            color: #fff;
        }

        .profile-text {
            color: var(--brand-muted);
            font-size: 0.92rem;
        }

        /* Experience */
        .job-title {
            font-weight: 700;
            color: var(--brand-green-deep);
            font-size: 0.95rem;
            display: block;
        }

        .job-meta {
            font-size: 0.8rem;
            color: var(--brand-green);
            font-weight: 600;
            margin-bottom: 0.6rem;
            display: block;
        }

        .job-list {
            margin: 0;
            padding-left: 1.1rem;
            color: var(--brand-muted);
            font-size: 0.86rem;
        }

        .job-list li {
            margin-bottom: 0.3rem;
        }

        /* Skills */
        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .skill-pill {
            background: var(--nude);
            color: var(--brand-green-deep);
            font-size: 0.78rem;
            font-weight: 500;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
        }

        /* Training */
        .training-issuer {
            font-weight: 700;
            color: var(--brand-green-deep);
            font-size: 0.9rem;
            margin-bottom: 0.35rem;
            display: block;
        }

        .training-list {
            margin: 0;
            padding-left: 1.1rem;
            color: var(--brand-muted);
            font-size: 0.86rem;
        }

        .training-list li {
            margin-bottom: 0.15rem;
        }

        /* Languages */
        .lang-row {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .lang-chip {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--beige-100);
            color: var(--brand-green-deep);
            font-size: 0.82rem;
            font-weight: 500;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
        }

        /* Portfolio */
        .portfolio-section {
            padding: 1.8rem 2.25rem 2.1rem;
            border-top: 1px solid var(--beige-100);
        }

        /* Matches the layout Delfina picked her photos in: 4 fixed-height rows
           instead of square tiles, with the "More work" call-out as a grid
           tile rather than footer text. */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 110px;
            gap: 0.65rem;
        }

        .portfolio-grid figure {
            margin: 0;
            border-radius: 0.9rem;
            overflow: hidden;
            position: relative;
            border: 1px solid var(--beige-100);
            box-shadow: 0 6px 14px rgba(65, 59, 14, 0.1);
        }

        .portfolio-grid figure.hero {
            grid-column: span 2;
            grid-row: span 2;
        }

        /* Delfina's own crop for the hero shot. */
        .portfolio-grid figure.hero img {
            object-position: center 32%;
        }

        .portfolio-grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .portfolio-more {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            text-align: center;
            background: var(--brand-green);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.5rem;
            line-height: 1.35;
        }

        .portfolio-more i {
            font-size: 1.1rem;
        }

        /* Footer */
        .cv-footer {
            background: var(--brand-green);
            color: #fff;
            padding: 1rem 2.25rem;
            text-align: center;
            font-size: 0.85rem;
        }

        /* Screen-only: an A4 page at 8mm margins is ~733px wide, which would
           otherwise trip this breakpoint and stack the CV when printing. */
        @media screen and (max-width: 760px) {
            .cv-header {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .cv-header .contact {
                text-align: left;
            }

            .cv-header .contact div {
                justify-content: flex-start;
            }

            .cv-body {
                grid-template-columns: 1fr;
            }

            .col-left {
                border-right: none;
                border-bottom: 1px solid var(--beige-100);
            }

            /* Two columns keeps tiles a readable size under the hero on
               narrow screens. */
            .portfolio-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media print {

            /* Keep brand colours when printing from the browser dialog,
               where "Background graphics" is off by default. */
            *,
            *::before,
            *::after {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body {
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            /* Fit the CV *and* its portfolio on one A4 page. Nearly every
               dimension here is in rem, so shrinking the root size scales the
               whole layout down uniformly instead of needing per-rule tweaks. */
            html {
                font-size: 13px;
            }

            body {
                font-size: 12.5px;
            }

            /* Exactly one A4 sheet, edge to edge. The coloured bands bleed to
               the paper edge; all text stays 13mm in, so a printer that can't
               do borderless clips only colour, never a word. */
            .page {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
                max-width: none;
                width: 210mm;
                height: 297mm;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .cv-header {
                padding: 13mm 13mm 10mm;
            }

            .col-left {
                padding: 8mm 7mm 8mm 13mm;
            }

            .col-right {
                padding: 8mm 13mm 8mm 8mm;
            }

            /* The portfolio is sized by its square tiles, so the body column
               absorbs the leftover height instead and the footer still lands on
               the bottom edge. */
            .cv-body {
                flex: 1 1 auto;
            }

            .portfolio-section {
                padding: 6mm 13mm 8mm;
            }

            .cv-footer {
                padding: 4mm 13mm 5mm;
            }

            /* Never split a heading from its content across pages. */
            .section,
            .portfolio-section {
                break-inside: avoid;
            }
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Print / Save as PDF
        </button>
    </div>

    <div class="page">

        <header class="cv-header">
            <div class="identity">
                <h1>Maria Delfina Mendonça</h1>
                <p class="role">Nail Technician</p>
            </div>
            <div class="contact">
                <div><span>+353 89 940 9670</span><i class="fas fa-phone"></i></div>
                <div><span>d.mariamendonca@gmail.com</span><i class="fas fa-envelope"></i></div>
                <div><span>@nailsbydelphina</span><i class="fab fa-instagram"></i></div>
                <div><span>Tallaght, Dublin 24</span><i class="fas fa-map-pin"></i></div>
            </div>
        </header>

        <div class="cv-body">
            <div class="col-left">

                <div class="section">
                    <span class="pill-label">Skills</span>
                    <div class="skills-grid">
                        <span class="skill-pill">Manicure</span>
                        <span class="skill-pill">Gel Polish / Shellac</span>
                        <span class="skill-pill">BIAB</span>
                        <span class="skill-pill">Acrylic &amp; Gel Extensions</span>
                        <span class="skill-pill">Nail Art Design</span>
                        <span class="skill-pill">E-File</span>
                        <span class="skill-pill">Nail Hygiene &amp; Sanitation</span>
                        <span class="skill-pill">Customer Service</span>
                    </div>
                </div>

                <div class="section">
                    <span class="pill-label">Training &amp; Certification</span>
                    <span class="training-issuer">Young Nails Dublin</span>
                    <ul class="training-list">
                        <li>BIAB</li>
                        <li>Gel</li>
                        <li>Acrylic</li>
                        <li>E-File</li>
                        <li>Nail Art</li>
                    </ul>
                </div>

                <div class="section">
                    <span class="pill-label">Languages</span>
                    <div class="lang-row">
                        <span class="lang-chip"><i class="fas fa-comment"></i> English</span>
                        <span class="lang-chip"><i class="fas fa-comment"></i> Spanish</span>
                    </div>
                </div>

            </div>

            <div class="col-right">

                <div class="section">
                    <span class="pill-label">Profile</span>
                    <p class="profile-text">
                        Detail-oriented Nail Technician trained in Gel, Acrylic and BIAB, with hands-on experience
                        running a private nail studio since 2024. Comfortable managing the full client experience
                        from consultation to finish, with a strong focus on hygiene, precision and consistent,
                        long-lasting results. Looking to bring that same care and technical skill to a nail
                        center team.
                    </p>
                </div>

                <div class="section">
                    <span class="pill-label">Experience</span>
                    <span class="job-title">Independent Nail Technician &mdash; Home Studio, Tallaght, Dublin</span>
                    <span class="job-meta">2024 &ndash; Present</span>
                    <ul class="job-list">
                        <li>Deliver Gel, Acrylic, BIAB and custom Nail Art services for a growing private clientele</li>
                        <li>Manage the full client journey: consultations, online bookings, deposits and scheduling</li>
                        <li>Maintain strict hygiene and sanitation standards in a private, fully-equipped studio</li>
                        <li>Build repeat clientele through consistent, detail-oriented finishes and strong customer
                            service</li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="portfolio-section">
            <span class="pill-label">Portfolio</span>
            <div class="portfolio-grid">
                {{-- Delfina's full 8-slot pick, in her order (applied 17 Aug 2026). --}}
                <figure class="hero"><img src="{{ asset('cv-photos/01-pink-polka-dot.jpg') }}"
                        alt="Pink nail art with white polka dots"></figure>

                <figure><img src="{{ asset('cv-photos/02-butter-yellow-gel.jpg') }}"
                        alt="Butter yellow gel manicure"></figure>
                <figure><img src="{{ asset('cv-photos/03-nude-pink-natural.jpg') }}"
                        alt="Natural nude pink manicure on both hands"></figure>

                <figure><img src="{{ asset('cv-photos/04-chocolate-glossy-almond.jpg') }}"
                        alt="Chocolate brown high-gloss almond gel manicure"></figure>
                <figure><img src="{{ asset('cv-photos/05-milky-nude-almond.jpg') }}"
                        alt="Milky nude almond gel manicure"></figure>

                <figure><img src="{{ asset('cv-photos/06-chrome-celestial-art.jpg') }}"
                        alt="Celestial silver chrome nail art"></figure>
                <figure><img src="{{ asset('cv-photos/07-milky-white-square.jpg') }}"
                        alt="Milky white square gel manicure"></figure>
                <figure><img src="{{ asset('cv-photos/08-polka-dot-nude-long.jpg') }}"
                        alt="White polka dot art on long nude nails"></figure>

                <figure class="portfolio-more">
                    <i class="fab fa-instagram"></i>
                    More work<br>@nailsbydelphina
                </figure>
            </div>
        </div>

        <footer class="cv-footer">References available upon request</footer>

    </div>

</body>

</html>