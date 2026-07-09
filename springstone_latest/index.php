<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';

$site_name = defined('SITE_NAME') ? SITE_NAME : 'PrimeAxis Investment';
$is_logged_in = function_exists('isLoggedIn') && isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($site_name); ?> | Springstone Latest</title>
    <meta name="description" content="A modern fintech investment platform for wealth management, market analytics, crypto investing, and advisory services.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --brand: #6c3aea;
            --brand-2: #3a1cb8;
            --accent: #f4a623;
            --bg: #f4f6fc;
            --card: #ffffff;
            --section: #eef1fb;
            --text: #1a1d2e;
            --muted: #5c6285;
            --border: #dde0f0;
            --success: #22c55e;
            --danger: #ef4444;
            --shadow: 0 18px 55px rgba(33, 38, 74, .12);
            --soft-shadow: 0 10px 30px rgba(33, 38, 74, .08);
            --radius: 8px;
        }

        [data-theme="dark"] {
            --brand: #7b4ff5;
            --accent: #ffba3b;
            --bg: #0d0f1a;
            --card: #161928;
            --section: #12152a;
            --text: #e8eaff;
            --muted: #9aa0c2;
            --border: #2a2e4a;
            --shadow: 0 18px 55px rgba(0, 0, 0, .38);
            --soft-shadow: 0 10px 30px rgba(0, 0, 0, .28);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: "Inter", sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }
        body.menu-open { overflow: hidden; }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        button, input, select, textarea { font: inherit; }
        :focus-visible { outline: 3px solid rgba(244, 166, 35, .75); outline-offset: 3px; }

        .skip-link {
            position: fixed;
            left: 16px;
            top: -80px;
            z-index: 99999;
            background: var(--accent);
            color: #131522;
            padding: 10px 14px;
            border-radius: 6px;
            font-weight: 800;
        }
        .skip-link:focus { top: 16px; }
        .container { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }
        .section { padding: 88px 0; }
        .section.alt { background: var(--section); }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--brand);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .eyebrow::before {
            content: "";
            width: 26px;
            height: 2px;
            background: var(--accent);
            border-radius: 999px;
        }
        h1, h2, h3 {
            margin: 0;
            font-family: "Plus Jakarta Sans", sans-serif;
            line-height: 1.12;
            letter-spacing: 0;
            color: var(--text);
        }
        h1 { font-size: clamp(40px, 7vw, 68px); font-weight: 800; }
        h2 { font-size: clamp(30px, 4.2vw, 44px); font-weight: 800; }
        h3 { font-size: 21px; font-weight: 800; }
        p { margin: 0; color: var(--muted); }
        .lead { max-width: 670px; font-size: 17px; }
        .section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 28px;
            margin-bottom: 38px;
        }
        .section-head.center {
            display: block;
            text-align: center;
            max-width: 760px;
            margin: 0 auto 42px;
        }
        .section-head.center .lead { margin: 14px auto 0; }

        .btn {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: var(--radius);
            border: 1px solid transparent;
            padding: 12px 18px;
            font-weight: 900;
            font-size: 14px;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn.primary { background: var(--brand); color: #fff; box-shadow: 0 12px 28px rgba(108, 58, 234, .28); }
        .btn.primary:hover { box-shadow: 0 16px 36px rgba(108, 58, 234, .36); }
        .btn.light { background: #fff; color: var(--brand); }
        .btn.gold { background: var(--accent); color: #171421; }
        .btn.outline { border-color: rgba(255, 255, 255, .72); color: #fff; background: transparent; }
        .btn.ghost { border-color: var(--border); color: var(--text); background: transparent; }
        .icon-btn {
            width: 42px;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--card);
            color: var(--text);
            cursor: pointer;
        }

        .preloader {
            position: fixed;
            inset: 0;
            z-index: 99998;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            display: grid;
            place-items: center;
            transition: opacity .35s ease, visibility .35s ease;
        }
        .preloader.hide { opacity: 0; visibility: hidden; }
        .preloader-mark {
            width: 74px;
            height: 74px;
            border-radius: 20px;
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.22);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 30px;
            animation: pulse 1.1s ease-in-out infinite alternate;
        }
        @keyframes pulse { from { transform: scale(.94); opacity: .72; } to { transform: scale(1.04); opacity: 1; } }

        .nav {
            position: fixed;
            inset: 0 0 auto;
            z-index: 1000;
            background: rgba(244, 246, 252, .9);
            border-bottom: 1px solid rgba(221, 224, 240, .75);
            backdrop-filter: blur(18px);
            transition: box-shadow .2s ease, background .2s ease;
        }
        [data-theme="dark"] .nav { background: rgba(13, 15, 26, .82); border-color: rgba(42, 46, 74, .8); }
        .nav.scrolled { box-shadow: 0 12px 36px rgba(24, 28, 52, .12); }
        .nav-inner { min-height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-weight: 900;
            color: var(--text);
            white-space: nowrap;
        }
        .brand img { width: 34px; height: 34px; }
        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brand), var(--accent));
            display: grid;
            place-items: center;
            color: #fff;
        }
        .nav-links { display: flex; align-items: center; gap: 6px; }
        .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 10px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 800;
        }
        .nav-link:hover { color: var(--brand); }
        .dropdown { position: relative; }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 260px;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--card);
            box-shadow: var(--shadow);
            display: none;
        }
        .dropdown:hover .dropdown-menu, .dropdown:focus-within .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 6px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 800;
        }
        .dropdown-menu a:hover { background: rgba(108,58,234,.08); color: var(--brand); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .menu-toggle { display: none; }

        .mobile-menu {
            position: fixed;
            inset: 76px 0 0;
            z-index: 999;
            background: var(--bg);
            transform: translateX(100%);
            transition: transform .25s ease;
            padding: 24px;
            overflow: auto;
        }
        .mobile-menu.open { transform: translateX(0); }
        .mobile-menu a { display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid var(--border); font-weight: 900; }
        .mobile-menu .btn { width: 100%; margin-top: 18px; }

        .hero {
            min-height: 94vh;
            padding: 132px 0 56px;
            background:
                radial-gradient(circle at 84% 18%, rgba(244, 166, 35, .24), transparent 26%),
                radial-gradient(circle at 16% 72%, rgba(255, 255, 255, .14), transparent 24%),
                linear-gradient(135deg, var(--brand) 0%, var(--brand-2) 100%);
            color: #fff;
            overflow: hidden;
            position: relative;
        }
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
            background-size: 46px 46px;
            mask-image: linear-gradient(120deg, rgba(0,0,0,.85), transparent 78%);
        }
        .hero-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(340px, .9fr);
            gap: 48px;
            align-items: center;
        }
        .hero h1, .hero p { color: #fff; }
        .hero p { max-width: 620px; color: rgba(255,255,255,.82); font-size: 18px; margin-top: 18px; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 30px; }
        .hero-kpis { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 34px; max-width: 620px; }
        .hero-kpi {
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.1);
            border-radius: var(--radius);
            padding: 14px;
            backdrop-filter: blur(12px);
        }
        .hero-kpi strong { display: block; font-family: "Space Grotesk", sans-serif; font-size: 24px; }
        .hero-kpi span { display: block; color: rgba(255,255,255,.72); font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
        .dashboard-art {
            position: relative;
            min-height: 520px;
            perspective: 1000px;
        }
        .screen {
            position: absolute;
            inset: 42px 0 auto auto;
            width: min(520px, 100%);
            border-radius: 18px;
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.24);
            box-shadow: 0 34px 80px rgba(0,0,0,.26);
            transform: rotateY(-11deg) rotateX(6deg);
            backdrop-filter: blur(16px);
            padding: 18px;
        }
        .screen-top { display: flex; gap: 8px; margin-bottom: 18px; }
        .dot { width: 10px; height: 10px; border-radius: 999px; background: rgba(255,255,255,.5); }
        .chart { height: 170px; border-radius: 12px; background: rgba(8, 10, 28, .38); padding: 18px; display: flex; align-items: end; gap: 8px; }
        .bar { flex: 1; min-width: 12px; border-radius: 999px 999px 3px 3px; background: linear-gradient(180deg, var(--accent), #fff0a6); }
        .dash-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 14px; }
        .mini-panel { border-radius: 12px; padding: 16px; background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.16); }
        .mini-panel strong { display: block; font-family: "Space Grotesk", sans-serif; font-size: 22px; color: #fff; }
        .mini-panel span { color: rgba(255,255,255,.68); font-size: 12px; }
        .coin {
            position: absolute;
            display: grid;
            place-items: center;
            width: 66px;
            height: 66px;
            border-radius: 50%;
            background: radial-gradient(circle at 32% 26%, #fff1b8, var(--accent) 42%, #b87411 100%);
            color: #4a2a04;
            font-size: 26px;
            box-shadow: 0 18px 38px rgba(0,0,0,.2);
            animation: float 4.8s ease-in-out infinite;
        }
        .coin.one { right: 8px; top: 0; }
        .coin.two { left: 16px; bottom: 72px; animation-delay: 1s; }
        .floating-card {
            position: absolute;
            right: 16px;
            bottom: 14px;
            width: 240px;
            border-radius: 12px;
            background: #fff;
            color: #161928;
            box-shadow: 0 24px 60px rgba(0,0,0,.24);
            padding: 16px;
            animation: float 5.4s ease-in-out infinite .4s;
        }
        .floating-card strong { display: block; font-family: "Space Grotesk", sans-serif; font-size: 24px; }
        .floating-card span { color: #22a954; font-weight: 900; }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }

        .trust-strip {
            padding: 22px 0;
            background: var(--card);
            border-bottom: 1px solid var(--border);
            overflow: hidden;
        }
        .trust-row { display: flex; align-items: center; gap: 36px; white-space: nowrap; animation: marquee 22s linear infinite; width: max-content; }
        .trust-row span { color: var(--muted); font-weight: 900; }
        .trust-logo { display: inline-flex; align-items: center; gap: 8px; color: var(--text); opacity: .62; font-family: "Plus Jakarta Sans", sans-serif; font-weight: 900; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        .about-grid, .benefits-grid, .faq-grid, .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 42px;
            align-items: center;
        }
        .image-panel {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: var(--card);
            border: 1px solid var(--border);
        }
        .image-panel img { width: 100%; height: 470px; object-fit: cover; }
        .stat-badge {
            position: absolute;
            background: var(--card);
            border: 1px solid var(--border);
            box-shadow: var(--soft-shadow);
            border-radius: var(--radius);
            padding: 14px 16px;
            min-width: 172px;
        }
        .stat-badge.top { top: 18px; left: 18px; }
        .stat-badge.bottom { right: 18px; bottom: 18px; }
        .stat-badge strong { display: block; color: var(--brand); font-family: "Space Grotesk", sans-serif; font-size: 24px; }
        .stat-badge span { color: var(--muted); font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: .04em; }
        .copy-stack { display: grid; gap: 18px; }
        .stats-line { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 20px; }
        .small-stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
        }
        .small-stat strong { display: block; font-family: "Space Grotesk", sans-serif; font-size: 24px; color: var(--brand); }
        .small-stat span { color: var(--muted); font-size: 13px; font-weight: 800; }

        .benefit-list { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .feature-card, .service-card, .team-card, .testimonial-card, .timeline-item, .faq-item, .form-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--soft-shadow);
        }
        .feature-card { padding: 18px; transition: transform .2s ease, border-color .2s ease; }
        .feature-card:hover, .service-card:hover { transform: translateY(-6px); border-color: rgba(108,58,234,.42); }
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            color: var(--brand);
            background: rgba(108, 58, 234, .1);
            font-size: 22px;
            margin-bottom: 14px;
        }
        .feature-card strong { display: block; margin-bottom: 6px; }
        .feature-card p, .service-card p, .timeline-item p, .team-card p, .testimonial-card p, .faq-item p { font-size: 14px; }
        .phone-mock {
            position: relative;
            width: min(320px, 100%);
            margin: 0 auto;
            border: 12px solid #141728;
            border-radius: 34px;
            background: var(--card);
            box-shadow: var(--shadow);
            padding: 18px;
        }
        .phone-mock::before {
            content: "";
            display: block;
            width: 80px;
            height: 6px;
            border-radius: 999px;
            background: #2a2e4a;
            margin: 0 auto 20px;
        }
        .phone-balance {
            border-radius: 16px;
            color: #fff;
            padding: 20px;
            background: linear-gradient(135deg, var(--brand), var(--accent));
        }
        .phone-balance span { display: block; opacity: .76; font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .phone-balance strong { font-family: "Space Grotesk", sans-serif; font-size: 30px; }
        .phone-list { display: grid; gap: 10px; margin-top: 16px; }
        .phone-row { display: flex; align-items: center; justify-content: space-between; background: var(--section); border-radius: 8px; padding: 12px; }
        .phone-row b { color: var(--success); }

        .services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
        .service-card { padding: 24px; border-left: 4px solid transparent; transition: transform .2s ease, border-color .2s ease; }
        .service-card:hover { border-left-color: var(--brand); }
        .service-card a { display: inline-flex; align-items: center; gap: 7px; margin-top: 16px; color: var(--accent); font-weight: 900; }

        .timeline { position: relative; display: grid; gap: 20px; max-width: 920px; margin: 0 auto; }
        .timeline::before { content: ""; position: absolute; top: 0; bottom: 0; left: 50%; border-left: 2px dashed rgba(108,58,234,.45); }
        .timeline-item { position: relative; width: calc(50% - 28px); padding: 22px; }
        .timeline-item:nth-child(odd) { justify-self: start; }
        .timeline-item:nth-child(even) { justify-self: end; }
        .timeline-badge {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .team-scroll {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(220px, 1fr);
            gap: 18px;
            overflow-x: auto;
            padding-bottom: 10px;
            scroll-snap-type: x mandatory;
        }
        .team-card { padding: 18px; scroll-snap-align: start; }
        .avatar {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 14px;
            border: 4px solid rgba(108,58,234,.12);
        }
        .team-card p { margin-top: 5px; }
        .team-card a { margin-top: 12px; display: inline-flex; color: var(--brand); }
        .testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
        .testimonial-card { padding: 24px; border-top: 4px solid var(--brand); }
        .stars { color: var(--accent); letter-spacing: 2px; margin-bottom: 14px; }
        .testimonial-author { display: flex; align-items: center; gap: 12px; margin-top: 18px; }
        .testimonial-author img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
        .testimonial-author strong { display: block; }
        .testimonial-author span { color: var(--muted); font-size: 13px; font-weight: 800; }

        .faq-list { display: grid; gap: 12px; }
        .faq-item { overflow: hidden; }
        .faq-q {
            width: 100%;
            min-height: 58px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: transparent;
            border: 0;
            color: var(--text);
            text-align: left;
            cursor: pointer;
            font-weight: 900;
        }
        .faq-q i { color: var(--brand); transition: transform .2s ease; }
        .faq-item.open .faq-q i { transform: rotate(180deg); }
        .faq-a { max-height: 0; overflow: hidden; transition: max-height .24s ease, padding .24s ease; }
        .faq-a p { padding: 0 18px 0; }
        .faq-item.open .faq-a { max-height: 240px; padding-bottom: 18px; }

        .newsletter {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: 42px;
            background:
                radial-gradient(circle at 18% 10%, rgba(255,255,255,.2), transparent 24%),
                linear-gradient(120deg, var(--brand), var(--accent));
            color: #fff;
        }
        .newsletter h2, .newsletter p { color: #fff; }
        .newsletter-grid { display: grid; grid-template-columns: 1.1fr .9fr; gap: 28px; align-items: center; }
        .newsletter-form { display: flex; gap: 10px; background: rgba(255,255,255,.18); padding: 8px; border-radius: 10px; border: 1px solid rgba(255,255,255,.22); }
        .newsletter-form input {
            min-width: 0;
            flex: 1;
            border: 0;
            outline: 0;
            border-radius: 7px;
            padding: 12px 14px;
            color: #161928;
        }

        .contact-grid { align-items: start; }
        .form-card { padding: 24px; }
        .field { display: grid; gap: 7px; margin-bottom: 14px; }
        .field label { font-size: 13px; font-weight: 900; color: var(--text); }
        .field input, .field select, .field textarea {
            width: 100%;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            border-radius: var(--radius);
            padding: 12px 13px;
            outline: 0;
        }
        .field textarea { min-height: 120px; resize: vertical; }
        .form-message { display: none; margin-top: 12px; color: var(--success); font-weight: 900; }

        .footer {
            margin-top: 88px;
            background: #111427;
            color: #fff;
        }
        .footer-news { padding: 34px 0; border-bottom: 1px solid rgba(255,255,255,.12); }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr repeat(3, 1fr);
            gap: 28px;
            padding: 44px 0;
        }
        .footer p, .footer a { color: rgba(255,255,255,.68); }
        .footer h4 { margin: 0 0 14px; font-family: "Plus Jakarta Sans", sans-serif; }
        .footer a { display: block; margin: 9px 0; font-weight: 700; }
        .socials { display: flex; gap: 10px; margin-top: 18px; }
        .socials a { width: 38px; height: 38px; margin: 0; border-radius: 8px; display: grid; place-items: center; background: rgba(255,255,255,.1); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.12); padding: 18px 0; text-align: center; color: rgba(255,255,255,.6); font-size: 13px; }

        .reveal { opacity: 0; transform: translateY(18px); transition: opacity .55s ease, transform .55s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 980px) {
            .nav-links, .nav-actions .btn { display: none; }
            .menu-toggle { display: inline-flex; }
            .hero-grid, .about-grid, .benefits-grid, .faq-grid, .contact-grid, .newsletter-grid { grid-template-columns: 1fr; }
            .dashboard-art { min-height: 430px; }
            .hero-kpis, .services-grid, .testimonials-grid, .footer-grid { grid-template-columns: 1fr 1fr; }
            .timeline::before { left: 23px; }
            .timeline-item { width: auto; margin-left: 54px; justify-self: stretch !important; }
        }
        @media (max-width: 640px) {
            .section { padding: 64px 0; }
            .container { width: min(100% - 24px, 1180px); }
            .hero { padding-top: 112px; }
            .hero-kpis, .benefit-list, .services-grid, .testimonials-grid, .stats-line, .footer-grid { grid-template-columns: 1fr; }
            .section-head { display: block; }
            .dashboard-art { display: none; }
            .newsletter { padding: 28px 18px; }
            .newsletter-form { display: grid; }
            .image-panel img { height: 360px; }
            .stat-badge { position: static; margin: 12px; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <div class="preloader" id="preloader" aria-hidden="true">
        <div class="preloader-mark"><i class="fa-solid fa-chart-line"></i></div>
    </div>

    <nav class="nav" id="nav">
        <div class="container nav-inner">
            <a class="brand" href="/springstone_latest/" aria-label="<?php echo htmlspecialchars($site_name); ?> Springstone Latest home">
                <span class="brand-mark"><i class="fa-solid fa-arrow-trend-up"></i></span>
                <span><?php echo htmlspecialchars($site_name); ?></span>
            </a>
            <div class="nav-links" aria-label="Primary navigation">
                <a class="nav-link" href="#home">Home</a>
                <a class="nav-link" href="#about">About</a>
                <div class="dropdown">
                    <a class="nav-link" href="#services">Services <i class="fa-solid fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="#wealth"><i class="fa-solid fa-scale-balanced"></i> Wealth Management</a>
                        <a href="#advisory"><i class="fa-solid fa-brain"></i> Financial Advisory</a>
                        <a href="#analytics"><i class="fa-solid fa-chart-simple"></i> Real-Time Analytics</a>
                        <a href="#crypto"><i class="fa-brands fa-bitcoin"></i> Cryptocurrency Investments</a>
                        <a href="#hr"><i class="fa-solid fa-users"></i> HR Consulting</a>
                        <a href="#marketing"><i class="fa-solid fa-bullhorn"></i> Marketing Consulting</a>
                    </div>
                </div>
                <a class="nav-link" href="#roadmap">Roadmap</a>
                <a class="nav-link" href="#faq">FAQ</a>
                <a class="nav-link" href="#contact">Contact</a>
            </div>
            <div class="nav-actions">
                <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle dark mode"><i class="fa-solid fa-moon"></i></button>
                <?php if ($is_logged_in): ?>
                    <a class="btn primary" href="/dashboard/"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
                <?php else: ?>
                    <a class="btn primary" href="/register.php"><i class="fa-solid fa-user-plus"></i> Join Now</a>
                <?php endif; ?>
                <button class="icon-btn menu-toggle" id="menuToggle" type="button" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu" aria-label="Mobile navigation">
        <a href="#home">Home <i class="fa-solid fa-arrow-right"></i></a>
        <a href="#about">About <i class="fa-solid fa-arrow-right"></i></a>
        <a href="#services">Services <i class="fa-solid fa-arrow-right"></i></a>
        <a href="#roadmap">Roadmap <i class="fa-solid fa-arrow-right"></i></a>
        <a href="#team">Advisers <i class="fa-solid fa-arrow-right"></i></a>
        <a href="#faq">FAQ <i class="fa-solid fa-arrow-right"></i></a>
        <a href="#contact">Contact <i class="fa-solid fa-arrow-right"></i></a>
        <?php if ($is_logged_in): ?>
            <a class="btn primary" href="/dashboard/">Dashboard</a>
        <?php else: ?>
            <a class="btn primary" href="/register.php">Join Now</a>
            <a class="btn ghost" href="/login.php">Sign In</a>
        <?php endif; ?>
    </div>

    <main id="main">
        <section class="hero" id="home">
            <div class="container hero-grid">
                <div class="reveal">
                    <span class="eyebrow" style="color:#fff">Springstone latest</span>
                    <h1>Invest Your Money With Higher Returns</h1>
                    <p>Anyone can invest money in currencies, digital assets, and guided wealth strategies through <?php echo htmlspecialchars($site_name); ?>'s intelligent trading platform.</p>
                    <div class="hero-actions">
                        <a class="btn light" href="/register.php"><i class="fa-solid fa-rocket"></i> Get Started</a>
                        <a class="btn outline" href="/login.php"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
                    </div>
                    <div class="hero-kpis" aria-label="Platform highlights">
                        <div class="hero-kpi"><strong>$124.5M</strong><span>Portfolio signals</span></div>
                        <div class="hero-kpi"><strong>8.4%</strong><span>Avg. growth view</span></div>
                        <div class="hero-kpi"><strong>24/7</strong><span>Market access</span></div>
                    </div>
                </div>
                <div class="dashboard-art reveal" aria-label="Trading dashboard illustration">
                    <div class="coin one"><i class="fa-solid fa-coins"></i></div>
                    <div class="coin two"><i class="fa-brands fa-bitcoin"></i></div>
                    <div class="screen">
                        <div class="screen-top"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
                        <div class="chart">
                            <span class="bar" style="height:42%"></span>
                            <span class="bar" style="height:74%"></span>
                            <span class="bar" style="height:58%"></span>
                            <span class="bar" style="height:86%"></span>
                            <span class="bar" style="height:66%"></span>
                            <span class="bar" style="height:92%"></span>
                            <span class="bar" style="height:78%"></span>
                        </div>
                        <div class="dash-row">
                            <div class="mini-panel"><strong>+12.4%</strong><span>Market momentum</span></div>
                            <div class="mini-panel"><strong>BTC</strong><span>Crypto watchlist</span></div>
                        </div>
                    </div>
                    <div class="floating-card">
                        <small>Portfolio Value</small>
                        <strong>$124,500</strong>
                        <span><i class="fa-solid fa-arrow-trend-up"></i> 8.4% today</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="trust-strip" aria-label="Financial partners">
            <div class="trust-row">
                <span>Trusted by leading financial partners</span>
                <span class="trust-logo"><i class="fa-solid fa-building-columns"></i> NexaBank</span>
                <span class="trust-logo"><i class="fa-solid fa-shield-halved"></i> FinVault</span>
                <span class="trust-logo"><i class="fa-solid fa-chart-line"></i> TradeSphere</span>
                <span class="trust-logo"><i class="fa-brands fa-bitcoin"></i> CoinAxis</span>
                <span class="trust-logo"><i class="fa-solid fa-leaf"></i> WealthPath</span>
                <span>Trusted by leading financial partners</span>
                <span class="trust-logo"><i class="fa-solid fa-building-columns"></i> NexaBank</span>
                <span class="trust-logo"><i class="fa-solid fa-shield-halved"></i> FinVault</span>
                <span class="trust-logo"><i class="fa-solid fa-chart-line"></i> TradeSphere</span>
                <span class="trust-logo"><i class="fa-brands fa-bitcoin"></i> CoinAxis</span>
                <span class="trust-logo"><i class="fa-solid fa-leaf"></i> WealthPath</span>
            </div>
        </section>

        <section class="section" id="about">
            <div class="container about-grid">
                <div class="image-panel reveal">
                    <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=900&q=80" alt="Financial adviser reviewing market charts with a client">
                    <div class="stat-badge top"><strong>30+</strong><span>Years consulting</span></div>
                    <div class="stat-badge bottom"><strong>25,000+</strong><span>Satisfied clients</span></div>
                </div>
                <div class="copy-stack reveal">
                    <span class="eyebrow">Meet our company</span>
                    <h2>A Forward-Thinking Fintech Firm Built for Everyone</h2>
                    <p>At <?php echo htmlspecialchars($site_name); ?>, we empower individuals and businesses through accessible, data-driven investment tools. Our platform combines market intelligence, disciplined advisory, and simple account workflows so clients can invest with clarity.</p>
                    <p>We make complex financial decisions easier to understand, from crypto exposure and wealth planning to market analytics and long-term portfolio guidance.</p>
                    <div class="stats-line">
                        <div class="small-stat"><strong>$2B+</strong><span>Assets tracked</span></div>
                        <div class="small-stat"><strong>50+</strong><span>Countries served</span></div>
                        <div class="small-stat"><strong>4.9</strong><span>Client rating</span></div>
                    </div>
                    <a class="btn ghost" href="#services">Explore More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <section class="section alt" id="benefits">
            <div class="container benefits-grid">
                <div class="copy-stack reveal">
                    <span class="eyebrow">Benefits we offer</span>
                    <h2>Unlock the Full Potential of Your Portfolio</h2>
                    <p class="lead">Purpose-built tools help investors scan opportunities, manage risk, and act confidently from any device.</p>
                    <div class="benefit-list">
                        <div class="feature-card"><div class="card-icon"><i class="fa-solid fa-briefcase"></i></div><strong>Investment lending</strong><p>$10M available for new projects and ideas.</p></div>
                        <div class="feature-card"><div class="card-icon"><i class="fa-solid fa-lock"></i></div><strong>Bank-level security</strong><p>Full control over your funds and profile.</p></div>
                        <div class="feature-card"><div class="card-icon"><i class="fa-solid fa-mobile-screen"></i></div><strong>Mobile payments</strong><p>Flexible deposits and withdrawals on the go.</p></div>
                        <div class="feature-card"><div class="card-icon"><i class="fa-solid fa-money-bill-transfer"></i></div><strong>Zero-fee options</strong><p>Priority pricing for eligible plan members.</p></div>
                    </div>
                </div>
                <div class="phone-mock reveal" aria-label="Mobile investment dashboard mockup">
                    <div class="phone-balance"><span>Portfolio balance</span><strong>$48,920</strong><small>+6.8% this week</small></div>
                    <div class="phone-list">
                        <div class="phone-row"><span>Wealth plan</span><b>+2.4%</b></div>
                        <div class="phone-row"><span>BTC basket</span><b>+4.1%</b></div>
                        <div class="phone-row"><span>Market analytics</span><b>Live</b></div>
                        <div class="phone-row"><span>Risk score</span><b>Low</b></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="services">
            <div class="container">
                <div class="section-head center reveal">
                    <span class="eyebrow">Services we offer</span>
                    <h2>Comprehensive Financial Solutions</h2>
                    <p class="lead">Expert guidance for investors, founders, and operators who want practical financial growth systems.</p>
                </div>
                <div class="services-grid">
                    <article class="service-card reveal" id="wealth"><div class="card-icon"><i class="fa-solid fa-scale-balanced"></i></div><h3>Wealth Management</h3><p>End-to-end planning, asset allocation, retirement strategy, and long-term growth support.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
                    <article class="service-card reveal" id="advisory"><div class="card-icon"><i class="fa-solid fa-brain"></i></div><h3>Financial Advisory</h3><p>Algorithm-assisted advisory workflows that make high-quality financial guidance accessible.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
                    <article class="service-card reveal" id="analytics"><div class="card-icon"><i class="fa-solid fa-chart-simple"></i></div><h3>Real-Time Market Analytics</h3><p>Live market signals, custom alerts, heatmaps, and decision support for fast-moving investors.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
                    <article class="service-card reveal" id="crypto"><div class="card-icon"><i class="fa-brands fa-bitcoin"></i></div><h3>Cryptocurrency Investments</h3><p>Diversified digital-asset exposure with secure portfolio workflows and 24/7 market access.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
                    <article class="service-card reveal" id="hr"><div class="card-icon"><i class="fa-solid fa-users"></i></div><h3>HR Consulting</h3><p>Talent strategy, compensation planning, leadership systems, and team growth consulting.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
                    <article class="service-card reveal" id="marketing"><div class="card-icon"><i class="fa-solid fa-bullhorn"></i></div><h3>Marketing Consulting</h3><p>Brand positioning, customer acquisition, analytics, paid media, and content strategy.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
                </div>
            </div>
        </section>

        <section class="section alt" id="roadmap">
            <div class="container">
                <div class="section-head center reveal">
                    <span class="eyebrow">Our product roadmap</span>
                    <h2>How We Build and Deliver Value</h2>
                    <p class="lead">A transparent operating system for research, design, development, and launch.</p>
                </div>
                <div class="timeline">
                    <article class="timeline-item reveal"><div class="timeline-badge">P1</div><h3>Project Research</h3><p>We gather data, set objectives, and analyze the market landscape.</p></article>
                    <article class="timeline-item reveal"><div class="timeline-badge">P2</div><h3>Framing the Idea</h3><p>We define the vision, scope, and user stories before development begins.</p></article>
                    <article class="timeline-item reveal"><div class="timeline-badge">P3</div><h3>Design First Draft</h3><p>UX teams produce wireframes and high-fidelity mockups for feedback.</p></article>
                    <article class="timeline-item reveal"><div class="timeline-badge">P4</div><h3>Final Design</h3><p>After iteration and testing, the experience is polished and approved.</p></article>
                    <article class="timeline-item reveal"><div class="timeline-badge">P5</div><h3>Product Development</h3><p>Engineering builds with agile milestones and production-focused QA.</p></article>
                    <article class="timeline-item reveal"><div class="timeline-badge">P6</div><h3>Launch</h3><p>We release confidently and keep improving from real client feedback.</p></article>
                </div>
            </div>
        </section>

        <section class="section" id="team">
            <div class="container">
                <div class="section-head reveal">
                    <div>
                        <span class="eyebrow">Meet our advisers</span>
                        <h2>World-Class Experts Ready to Guide You</h2>
                    </div>
                    <a class="btn ghost" href="#contact">Speak With Us</a>
                </div>
                <div class="team-scroll">
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80" alt="Dianne Russell"><h3>Dianne Russell</h3><p>Trade Captain</p><a href="#" aria-label="Dianne Russell on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80" alt="Gibson Webb"><h3>Gibson Webb</h3><p>Strategic Advisor</p><a href="#" aria-label="Gibson Webb on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80" alt="Courtney Henry"><h3>Courtney Henry</h3><p>Management Consultant</p><a href="#" aria-label="Courtney Henry on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80" alt="Albert Flores"><h3>Albert Flores</h3><p>Development Specialist</p><a href="#" aria-label="Albert Flores on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80" alt="Darrell Steward"><h3>Darrell Steward</h3><p>Growth Strategist</p><a href="#" aria-label="Darrell Steward on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80" alt="Wade Warren"><h3>Wade Warren</h3><p>Trade Consultant</p><a href="#" aria-label="Wade Warren on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1556157382-97eda2d62296?auto=format&fit=crop&w=300&q=80" alt="Cody Fisher"><h3>Cody Fisher</h3><p>HR Consultant</p><a href="#" aria-label="Cody Fisher on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                    <article class="team-card"><img class="avatar" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80" alt="Bessie Cooper"><h3>Bessie Cooper</h3><p>Financial Advisor</p><a href="#" aria-label="Bessie Cooper on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></article>
                </div>
            </div>
        </section>

        <section class="section alt" id="testimonials">
            <div class="container">
                <div class="section-head center reveal">
                    <span class="eyebrow">What our clients say</span>
                    <h2>Trusted by Growing Investors</h2>
                </div>
                <div class="testimonials-grid">
                    <article class="testimonial-card reveal"><div class="stars">★★★★★</div><p>"Spring Stone stands out for its innovative technology and user-friendly interface. I feel empowered to make truly informed investment decisions."</p><div class="testimonial-author"><img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=180&q=80" alt="Mobarok Hossain"><div><strong>Mobarok Hossain</strong><span>Trade Master</span></div></div></article>
                    <article class="testimonial-card reveal"><div class="stars">★★★★★</div><p>"<?php echo htmlspecialchars($site_name); ?> transformed how I approach investing. Their data-driven insights helped me see portfolio growth in just a few months."</p><div class="testimonial-author"><img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=180&q=80" alt="Guy Hawkins"><div><strong>Guy Hawkins</strong><span>Trade Boss</span></div></div></article>
                    <article class="testimonial-card reveal"><div class="stars">★★★★★</div><p>"As a small business owner, I was hesitant about investing. Their guidance and personalized strategies helped secure my financial future."</p><div class="testimonial-author"><img src="https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=180&q=80" alt="Belal Hossain"><div><strong>Belal Hossain</strong><span>Trade Genius</span></div></div></article>
                </div>
            </div>
        </section>

        <section class="section" id="faq">
            <div class="container faq-grid">
                <div class="reveal">
                    <span class="eyebrow">Frequently asked questions</span>
                    <h2>Answers Before You Start</h2>
                    <p class="lead" style="margin-top:14px">Learn how the platform works, what risks to understand, and how to create your account.</p>
                    <div class="image-panel" style="margin-top:24px">
                        <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=80" alt="Customer support professional helping an investor">
                    </div>
                </div>
                <div class="faq-list reveal">
                    <article class="faq-item open"><button class="faq-q" type="button">What does <?php echo htmlspecialchars($site_name); ?> do? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>We are a fintech investment platform that lets you invest in currencies, cryptocurrencies, stocks, and other assets from one dashboard.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">What are the disadvantages of online trading? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>All investing carries risk. Our platform provides risk management tools, alerts, and expert guidance to help reduce exposure.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">Is online trading safe on your platform? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>We use secure account practices, encrypted sessions, and strict operational workflows to protect users and transactions.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">Which plan is best for beginners? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>Beginners should start with a lower-risk plan, review tutorials, and build confidence before scaling their position size.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">How do I create a trading account? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>Click Join Now, fill in your details, verify your account, make a deposit, and choose your investment plan.</p></div></article>
                </div>
            </div>
        </section>

        <section class="section" id="contact">
            <div class="container contact-grid">
                <div class="copy-stack reveal">
                    <span class="eyebrow">Contact us</span>
                    <h2>Let's Get In Touch</h2>
                    <p class="lead">Have questions about plans, crypto investments, or advisory services? Send a note and our support team will reply.</p>
                    <p><strong>Email:</strong> support@primeaxisinv.com</p>
                    <p><strong>Address:</strong> 120 Fintech Avenue, New York, NY</p>
                    <p><strong>Phone:</strong> +1 (800) 555-0199</p>
                    <div class="socials" aria-label="Social links">
                        <a href="#" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
                <form class="form-card reveal" id="contactForm">
                    <div class="field"><label for="name">Full Name</label><input id="name" name="name" autocomplete="name" required></div>
                    <div class="field"><label for="email">Email Address</label><input id="email" name="email" type="email" autocomplete="email" required></div>
                    <div class="field"><label for="subject">Subject</label><select id="subject" name="subject" required><option value="">Choose a topic</option><option>Investment Plans</option><option>Account Support</option><option>Advisory Services</option><option>Partnership</option></select></div>
                    <div class="field"><label for="message">Message</label><textarea id="message" name="message" required></textarea></div>
                    <button class="btn primary" type="submit"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
                    <div class="form-message" id="formMessage">Thank you! We'll be in touch within 24 hours.</div>
                </form>
            </div>
        </section>

        <section class="section" style="padding-top:0">
            <div class="container">
                <div class="newsletter reveal">
                    <div class="newsletter-grid">
                        <div>
                            <h2>Subscribe to Our Newsletter</h2>
                            <p style="margin-top:10px">Stay ahead of the market with weekly insights, investment tips, and platform updates.</p>
                        </div>
                        <form class="newsletter-form" id="newsletterForm">
                            <input type="email" placeholder="Email address" aria-label="Email address" required>
                            <button class="btn gold" type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-news">
            <div class="container">
                <div class="brand" style="color:#fff"><span class="brand-mark"><i class="fa-solid fa-arrow-trend-up"></i></span><span><?php echo htmlspecialchars($site_name); ?></span></div>
            </div>
        </div>
        <div class="container footer-grid">
            <div>
                <h4><?php echo htmlspecialchars($site_name); ?></h4>
                <p>The future of trading, investing, saving and earning.</p>
                <div class="socials">
                    <a href="#" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div><h4>Quick Links</h4><a href="#about">About Us</a><a href="#services">Services</a><a href="#benefits">Features</a><a href="#">Blog</a></div>
            <div><h4>Support</h4><a href="#">Terms & Conditions</a><a href="#">Privacy Policy</a><a href="#faq">FAQs</a><a href="#contact">Support Center</a></div>
            <div><h4>Company</h4><a href="#">Careers</a><a href="#">Updates</a><a href="#">Jobs</a><a href="#">Announcements</a></div>
        </div>
        <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_name); ?>. All Rights Reserved.</div>
    </footer>

    <script>
        const root = document.documentElement;
        const storedTheme = localStorage.getItem('springstone-theme') || 'light';
        root.dataset.theme = storedTheme;

        const preloader = document.getElementById('preloader');
        window.addEventListener('load', () => {
            setTimeout(() => preloader.classList.add('hide'), 650);
        });

        const nav = document.getElementById('nav');
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const themeToggle = document.getElementById('themeToggle');

        function syncThemeIcon() {
            themeToggle.innerHTML = root.dataset.theme === 'dark'
                ? '<i class="fa-solid fa-sun"></i>'
                : '<i class="fa-solid fa-moon"></i>';
        }
        syncThemeIcon();

        themeToggle.addEventListener('click', () => {
            root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('springstone-theme', root.dataset.theme);
            syncThemeIcon();
        });

        menuToggle.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('open');
            document.body.classList.toggle('menu-open', open);
            menuToggle.innerHTML = open ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                document.body.classList.remove('menu-open');
                menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
            });
        });

        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 80);
        });

        document.querySelectorAll('.faq-q').forEach(button => {
            button.addEventListener('click', () => button.closest('.faq-item').classList.toggle('open'));
        });

        document.getElementById('contactForm').addEventListener('submit', event => {
            event.preventDefault();
            document.getElementById('formMessage').style.display = 'block';
            event.currentTarget.reset();
        });

        document.getElementById('newsletterForm').addEventListener('submit', event => {
            event.preventDefault();
            event.currentTarget.reset();
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: .12 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>
