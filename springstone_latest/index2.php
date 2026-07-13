<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';

$brand = 'PrimeAxis';
$site_name = defined('SITE_NAME') ? SITE_NAME : $brand;
$is_logged_in = function_exists('isLoggedIn') && isLoggedIn();
$base = '/springstone_latest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($brand); ?> - Fintech Investment Platform</title>
    <meta name="description" content="A forward-thinking fintech investment platform for wealth management, advisory, real-time market analytics, and cryptocurrency investments.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            color-scheme: light;
            --background: #f4f6fc;
            --foreground: #1a1d2e;
            --card: #ffffff;
            --card-foreground: #1a1d2e;
            --popover: #ffffff;
            --primary: #6c3aea;
            --primary-foreground: #ffffff;
            --secondary: #eef1fb;
            --muted: #eef1fb;
            --muted-foreground: #5c6285;
            --accent: #f4a623;
            --accent-foreground: #1a1d2e;
            --success: #22c55e;
            --border: #dde0f0;
            --ring: #6c3aea;
            --radius: 10px;
        }
        html.dark {
            color-scheme: dark;
            --background: #0d0f1a;
            --foreground: #e8eaff;
            --card: #161928;
            --card-foreground: #e8eaff;
            --popover: #161928;
            --primary: #7b4ff5;
            --secondary: #12152a;
            --muted: #12152a;
            --muted-foreground: #8a8fb5;
            --accent: #ffba3b;
            --border: #2a2e4a;
            --ring: #7b4ff5;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--background);
            color: var(--foreground);
            font-family: Inter, system-ui, sans-serif;
            line-height: 1.6;
        }
        body.menu-open { overflow: hidden; }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        button, input { font: inherit; }
        :focus-visible { outline: 2px solid var(--ring); outline-offset: 3px; }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .skip:focus {
            position: fixed;
            left: 16px;
            top: 16px;
            z-index: 200;
            width: auto;
            height: auto;
            padding: 10px 14px;
            clip: auto;
            color: #fff;
            background: var(--primary);
            border-radius: 8px;
        }
        .container { width: min(100% - 32px, 1280px); margin: 0 auto; }
        .narrow { width: min(100% - 32px, 1024px); margin: 0 auto; }
        h1, h2, h3, h4 {
            margin: 0;
            font-family: "Plus Jakarta Sans", Inter, sans-serif;
            line-height: 1.12;
            letter-spacing: 0;
            color: var(--foreground);
        }
        h1 { font-size: clamp(42px, 7vw, 64px); font-weight: 800; text-wrap: balance; }
        h2 { font-size: clamp(31px, 4vw, 42px); font-weight: 800; text-wrap: balance; }
        h3 { font-size: 20px; font-weight: 800; }
        p { margin: 0; color: var(--muted-foreground); }
        .eyebrow {
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: 13px;
            font-weight: 800;
        }
        .section { padding: 80px 0; }
        @media (min-width: 1024px) { .section { padding: 112px 0; } }
        .section-secondary { background: var(--secondary); }
        .section-head { max-width: 680px; margin: 0 auto 56px; text-align: center; }
        .section-head h2 { margin-top: 12px; }
        .section-head p { margin-top: 16px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 46px;
            border-radius: 8px;
            border: 1px solid transparent;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: var(--primary); color: var(--primary-foreground); box-shadow: 0 14px 28px rgba(108,58,234,.25); }
        .btn-white { background: #fff; color: var(--primary); box-shadow: 0 18px 32px rgba(0,0,0,.12); }
        .btn-outline-white { border-color: rgba(255,255,255,.42); color: #fff; }
        .btn-outline { border-color: var(--border); background: var(--card); color: var(--foreground); }
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--foreground);
            cursor: pointer;
        }
        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 20px;
            font-weight: 800;
        }
        .logo-mark {
            display: inline-flex;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: #fff;
        }
        .nav {
            position: fixed;
            inset: 0 0 auto;
            z-index: 50;
            border-bottom: 1px solid transparent;
            background: rgba(244, 246, 252, .42);
            backdrop-filter: blur(10px);
            transition: all .25s ease;
        }
        html.dark .nav { background: rgba(13,15,26,.42); }
        .nav.scrolled {
            border-color: var(--border);
            background: color-mix(in srgb, var(--background) 82%, transparent);
            box-shadow: 0 4px 16px rgba(0,0,0,.05);
        }
        .nav-inner {
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }
        .desktop-nav { display: none; align-items: center; gap: 4px; }
        .nav-link, .drop-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            padding: 9px 12px;
            color: var(--foreground);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: color .2s ease;
        }
        .nav-link:hover, .drop-btn:hover { color: var(--primary); }
        .dropdown { position: relative; }
        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            width: 260px;
            padding-top: 8px;
        }
        .dropdown:hover .dropdown-menu, .dropdown:focus-within .dropdown-menu { display: block; }
        .dropdown-menu ul {
            list-style: none;
            margin: 0;
            padding: 8px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--popover);
            box-shadow: 0 24px 50px rgba(0,0,0,.16);
        }
        .dropdown-menu a {
            display: block;
            border-radius: 8px;
            padding: 10px 12px;
            color: var(--foreground);
            font-size: 14px;
        }
        .dropdown-menu a:hover { background: var(--muted); color: var(--primary); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .menu-btn { display: inline-flex; }
        .join-desktop { display: none; }
        .mobile-panel {
            position: fixed;
            inset: 0 0 0 auto;
            z-index: 80;
            width: min(86vw, 390px);
            background: var(--background);
            box-shadow: -24px 0 60px rgba(0,0,0,.22);
            transform: translateX(110%);
            transition: transform .25s ease;
            display: flex;
            flex-direction: column;
        }
        .mobile-panel.open { transform: translateX(0); }
        .mobile-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            padding: 16px;
        }
        .mobile-links { padding: 22px 16px; overflow-y: auto; }
        .mobile-links a {
            display: block;
            border-radius: 8px;
            padding: 11px 12px;
            font-weight: 700;
        }
        .mobile-links a:hover { background: var(--muted); color: var(--primary); }
        .mobile-label {
            padding: 18px 12px 4px;
            color: var(--muted-foreground);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .mobile-foot { border-top: 1px solid var(--border); padding: 16px; }
        .mobile-foot .btn { width: 100%; }
        @media (min-width: 1024px) {
            .desktop-nav { display: flex; }
            .menu-btn { display: none; }
            .join-desktop { display: inline-flex; }
        }
        .hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #6c3aea 0%, #3a1cb8 100%);
            color: #fff;
        }
        .mesh {
            position: absolute;
            inset: 0;
            opacity: .18;
            background-image: radial-gradient(circle, rgba(255,255,255,.6) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }
        .orb {
            position: absolute;
            width: 288px;
            height: 288px;
            border-radius: 999px;
            pointer-events: none;
            filter: blur(58px);
        }
        .orb.top { right: -80px; top: 40px; background: rgba(244,166,35,.3); }
        .orb.bottom { left: -64px; bottom: -96px; background: rgba(255,255,255,.1); }
        .hero-grid {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 48px;
            align-items: center;
            padding: 128px 0 80px;
        }
        .hero h1, .hero p { color: #fff; }
        .hero p { margin-top: 20px; color: rgba(255,255,255,.8); font-size: 18px; max-width: 590px; }
        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 999px;
            background: rgba(255,255,255,.1);
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            backdrop-filter: blur(10px);
        }
        .hero-pill i { color: var(--accent); }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px; }
        .hero-stats { display: flex; flex-wrap: wrap; gap: 32px; margin-top: 38px; }
        .hero-stats dt { font-family: "Space Grotesk", monospace; font-size: 28px; font-weight: 700; color: #fff; }
        .hero-stats dd { margin: 0; color: rgba(255,255,255,.7); font-size: 12px; }
        .hero-art { position: relative; }
        .hero-art img { width: 100%; filter: drop-shadow(0 28px 40px rgba(0,0,0,.3)); animation: float-slow 9s ease-in-out infinite; }
        .float-card {
            position: absolute;
            left: -8px;
            bottom: 24px;
            display: none;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 18px;
            background: rgba(255,255,255,.1);
            padding: 16px;
            color: #fff;
            backdrop-filter: blur(12px);
            animation: float 6s ease-in-out infinite;
        }
        .float-card strong { display: block; font-family: "Space Grotesk", monospace; font-size: 22px; }
        .float-card span { color: var(--success); font-size: 12px; font-weight: 800; }
        .wave { position: relative; color: var(--background); }
        .wave svg { display: block; width: 100%; height: 64px; }
        @media (min-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr 1fr; padding-top: 144px; padding-bottom: 112px; }
            .float-card { display: block; }
        }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
        @keyframes float-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        .partner { padding: 40px 0; background: var(--background); }
        .partner p { text-align: center; font-size: 14px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .marquee-wrap {
            margin-top: 24px;
            overflow: hidden;
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        .marquee {
            display: flex;
            width: max-content;
            gap: 64px;
            padding-right: 64px;
            animation: marquee 30s linear infinite;
        }
        .marquee-wrap:hover .marquee { animation-play-state: paused; }
        .marquee span {
            color: color-mix(in srgb, var(--muted-foreground) 60%, transparent);
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: clamp(20px, 3vw, 26px);
            font-weight: 800;
            filter: grayscale(1);
            white-space: nowrap;
        }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .about-grid, .faq-grid {
            display: grid;
            gap: 48px;
            align-items: center;
        }
        .about-image, .faq-image {
            position: relative;
        }
        .image-shell {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: 0 24px 48px rgba(15,23,42,.14);
        }
        .image-shell img { width: 100%; height: 100%; object-fit: cover; }
        .badge {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: var(--card);
            padding: 14px 16px;
            box-shadow: 0 14px 30px rgba(15,23,42,.14);
        }
        .badge.one { left: -16px; top: 32px; }
        .badge.two { right: -16px; bottom: 32px; }
        .badge-icon {
            display: inline-flex;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
        }
        .badge-icon.gold { color: var(--accent); background: color-mix(in srgb, var(--accent) 15%, transparent); }
        .badge-icon.purple { color: var(--primary); background: color-mix(in srgb, var(--primary) 15%, transparent); }
        .badge strong { display: block; font-family: "Space Grotesk", monospace; color: var(--card-foreground); }
        .badge span { color: var(--muted-foreground); font-size: 12px; }
        .copy h2 { margin-top: 12px; }
        .copy p { margin-top: 20px; max-width: 650px; }
        .copy .btn { margin-top: 32px; }
        @media (min-width: 1024px) {
            .about-grid, .faq-grid { grid-template-columns: 1fr 1fr; gap: 64px; }
        }
        .benefit-layout {
            display: grid;
            gap: 32px;
            align-items: center;
            margin-top: 56px;
        }
        .benefit-col { display: grid; gap: 24px; }
        .phone-wrap {
            order: -1;
            position: relative;
            width: min(256px, 70vw);
            margin: 0 auto;
        }
        .phone-wrap::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            scale: 1.1;
            border-radius: 999px;
            background: color-mix(in srgb, var(--primary) 15%, transparent);
            filter: blur(32px);
        }
        .phone-wrap img { filter: drop-shadow(0 28px 28px rgba(0,0,0,.24)); }
        .benefit-card, .service-card, .timeline-card, .team-card, .testimonial-card, .faq-item {
            border: 1px solid var(--border);
            background: var(--card);
            box-shadow: 0 2px 8px rgba(15,23,42,.04);
        }
        .benefit-card {
            border-radius: 18px;
            padding: 24px;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .benefit-card:hover, .service-card:hover, .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 38px rgba(15,23,42,.12);
        }
        .benefit-card.right { text-align: left; }
        .card-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: color-mix(in srgb, var(--primary) 10%, transparent);
            color: var(--primary);
            transition: all .2s ease;
        }
        .benefit-card:hover .card-icon, .service-card:hover .card-icon { background: var(--primary); color: var(--primary-foreground); }
        .benefit-card h3, .service-card h3 { margin-top: 16px; }
        .benefit-card p, .service-card p { margin-top: 7px; font-size: 14px; }
        .benefit-stat { margin-top: 12px; color: var(--accent); font-family: "Space Grotesk", monospace; font-size: 14px; font-weight: 700; }
        @media (min-width: 1024px) {
            .benefit-layout { grid-template-columns: 1fr auto 1fr; }
            .phone-wrap { order: 0; }
            .benefit-card.right { text-align: right; }
            .benefit-card.right .card-icon { margin-left: auto; }
        }
        .service-grid {
            display: grid;
            gap: 24px;
            margin-top: 56px;
        }
        .service-card {
            display: flex;
            min-height: 100%;
            flex-direction: column;
            border-left: 2px solid transparent;
            border-radius: 18px;
            padding: 28px;
            transition: all .2s ease;
        }
        .service-card:hover { border-left-color: var(--primary); }
        .service-card p { flex: 1; }
        .learn { display: inline-flex; align-items: center; gap: 6px; margin-top: 20px; color: var(--accent); font-size: 14px; font-weight: 800; }
        @media (min-width: 700px) { .service-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .service-grid { grid-template-columns: repeat(3, 1fr); } }
        .timeline {
            position: relative;
            margin-top: 64px;
            list-style: none;
            padding: 0;
        }
        .timeline::before {
            content: "";
            position: absolute;
            left: 20px;
            top: 8px;
            bottom: 0;
            border-left: 2px dashed color-mix(in srgb, var(--primary) 40%, transparent);
        }
        .timeline li { position: relative; margin-bottom: 40px; }
        .timeline-row { display: flex; flex-direction: column; gap: 16px; }
        .timeline-card {
            margin-left: 64px;
            border-radius: 18px;
            padding: 24px;
        }
        .timeline-card .phase {
            color: var(--accent);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .timeline-card h3 { margin-top: 4px; }
        .timeline-card p { margin-top: 8px; font-size: 14px; }
        .timeline-badge {
            position: absolute;
            left: 0;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 999px;
            background: var(--primary);
            color: var(--primary-foreground);
            font-family: "Space Grotesk", monospace;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 10px 18px rgba(108,58,234,.24);
            ring: 4px solid var(--secondary);
        }
        @media (min-width: 768px) {
            .timeline::before { left: 50%; transform: translateX(-50%); }
            .timeline-row { flex-direction: row; align-items: center; }
            .timeline li:nth-child(even) .timeline-row { flex-direction: row-reverse; }
            .timeline-half { width: 50%; padding: 0 32px; }
            .timeline-card { margin-left: 0; }
            .timeline li:nth-child(odd) .timeline-card { text-align: right; }
            .timeline-badge { left: 50%; transform: translateX(-50%); }
        }
        .team-head {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-bottom: 48px;
        }
        .team-head p { margin-top: 16px; max-width: 600px; }
        .team-controls { display: flex; gap: 8px; }
        .team-list {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 2px 0 18px;
            scrollbar-width: none;
        }
        .team-list::-webkit-scrollbar { display: none; }
        .team-card {
            width: 70%;
            flex: 0 0 auto;
            scroll-snap-align: start;
            overflow: hidden;
            border-radius: 18px;
            transition: all .2s ease;
        }
        .team-img {
            position: relative;
            aspect-ratio: 4 / 5;
            overflow: hidden;
        }
        .team-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
        .team-card:hover img { transform: scale(1.05); }
        .linkedin {
            position: absolute;
            right: 12px;
            bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            opacity: 0;
            transition: opacity .2s ease;
        }
        .team-card:hover .linkedin { opacity: 1; }
        .team-body { padding: 20px; }
        .team-body p { margin-top: 2px; font-size: 14px; }
        @media (min-width: 640px) {
            .team-card { width: 45%; }
            .team-head { flex-direction: row; justify-content: space-between; align-items: end; }
        }
        @media (min-width: 1024px) { .team-card { width: calc(25% - 18px); } }
        .testimonial-grid {
            display: grid;
            gap: 24px;
            margin-top: 56px;
        }
        .testimonial-card {
            position: relative;
            display: flex;
            flex-direction: column;
            border-top: 2px solid var(--primary);
            border-radius: 18px;
            padding: 28px;
        }
        .quote-icon { position: absolute; right: 24px; top: 24px; color: color-mix(in srgb, var(--primary) 10%, transparent); font-size: 32px; }
        .stars { display: flex; gap: 4px; color: var(--accent); }
        blockquote { flex: 1; margin: 16px 0 0; color: var(--card-foreground); font-style: italic; line-height: 1.7; }
        .author {
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid var(--border);
            margin-top: 24px;
            padding-top: 20px;
        }
        .author img { width: 48px; height: 48px; border-radius: 999px; object-fit: cover; }
        .author strong { display: block; font-family: "Plus Jakarta Sans", sans-serif; font-size: 14px; }
        .author span { color: var(--muted-foreground); font-size: 12px; }
        @media (min-width: 768px) { .testimonial-grid { grid-template-columns: repeat(3, 1fr); } }
        .faq-image { order: 2; }
        .faq-list { margin-top: 32px; display: grid; gap: 12px; }
        .faq-item { border-radius: 14px; overflow: hidden; }
        .faq-q {
            width: 100%;
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border: 0;
            background: transparent;
            color: var(--card-foreground);
            padding: 16px 18px;
            text-align: left;
            font-weight: 800;
            cursor: pointer;
        }
        .faq-q i { color: var(--primary); transition: transform .2s ease; }
        .faq-item.open .faq-q i { transform: rotate(180deg); }
        .faq-a { max-height: 0; overflow: hidden; transition: max-height .25s ease, padding .25s ease; }
        .faq-a p { padding: 0 18px; font-size: 14px; }
        .faq-item.open .faq-a { max-height: 250px; padding-bottom: 18px; }
        @media (min-width: 1024px) { .faq-image { order: 0; } }
        .newsletter-section { padding: 0 0 80px; background: var(--background); }
        @media (min-width: 1024px) { .newsletter-section { padding-bottom: 112px; } }
        .newsletter {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            background: linear-gradient(135deg, #6c3aea, #3a1cb8);
            padding: 56px 24px;
            text-align: center;
            color: #fff;
        }
        .newsletter h2, .newsletter p { color: #fff; }
        .newsletter p { margin: 16px auto 0; max-width: 650px; color: rgba(255,255,255,.8); }
        .newsletter .orb { filter: blur(54px); }
        .newsletter .orb.left { left: -40px; top: 0; background: rgba(244,166,35,.2); }
        .newsletter .orb.right { right: -40px; bottom: 0; background: rgba(255,255,255,.1); }
        .newsletter-form {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 540px;
            margin: 32px auto 0;
        }
        .newsletter input {
            height: 48px;
            width: 100%;
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 8px;
            background: rgba(255,255,255,.1);
            color: #fff;
            padding: 0 16px;
            outline: 0;
            backdrop-filter: blur(10px);
        }
        .newsletter input::placeholder { color: rgba(255,255,255,.6); }
        .newsletter button { background: var(--accent); color: var(--accent-foreground); }
        @media (min-width: 640px) {
            .newsletter { padding: 72px 48px; }
            .newsletter-form { flex-direction: row; }
        }
        .footer-news { background: #12152a; color: #e8eaff; }
        .footer-news .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .footer-news h2 { color: #e8eaff; font-size: 24px; }
        .footer-news p { color: #8a8fb5; margin-top: 4px; font-size: 14px; }
        .footer-news form { display: flex; width: 100%; max-width: 448px; gap: 8px; }
        .footer-news input {
            min-width: 0;
            height: 44px;
            flex: 1;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px;
            background: rgba(255,255,255,.05);
            color: #e8eaff;
            padding: 0 16px;
            outline: 0;
        }
        .footer-main {
            position: relative;
            overflow: hidden;
            background: #0d0f1a;
            color: #e8eaff;
        }
        .footer-main::before {
            content: "";
            position: absolute;
            left: -96px;
            bottom: -96px;
            width: 288px;
            height: 288px;
            border-radius: 999px;
            background: rgba(108,58,234,.2);
            filter: blur(58px);
        }
        .footer-grid {
            position: relative;
            display: grid;
            gap: 40px;
            padding: 64px 0;
        }
        .footer-main .logo span:last-child, .footer-main h3 { color: #e8eaff; }
        .footer-main p, .footer-main a { color: #8a8fb5; }
        .footer-main a:hover { color: var(--accent); }
        .footer-col h3 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: .1em;
        }
        .footer-col ul { list-style: none; margin: 16px 0 0; padding: 0; display: grid; gap: 10px; }
        .socials { display: flex; gap: 12px; margin-top: 24px; }
        .socials a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px;
            background: rgba(255,255,255,.05);
            color: #e8eaff;
        }
        .socials a:hover { border-color: var(--primary); background: var(--primary); color: #fff; }
        .copyright {
            position: relative;
            border-top: 1px solid rgba(255,255,255,.1);
            padding: 24px 0;
            text-align: center;
            color: #8a8fb5;
            font-size: 14px;
        }
        @media (min-width: 768px) {
            .footer-news .container { flex-direction: row; justify-content: space-between; }
            .footer-grid { grid-template-columns: 1.4fr 1fr 1fr 1fr; }
        }
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .preloader {
            position: fixed;
            inset: 0;
            z-index: 120;
            display: grid;
            place-items: center;
            background: var(--background);
            transition: opacity .25s ease, visibility .25s ease;
        }
        .preloader.hide { opacity: 0; visibility: hidden; }
        .spinner {
            display: grid;
            place-items: center;
            width: 72px;
            height: 72px;
            border-radius: 18px;
            background: var(--primary);
            color: #fff;
            animation: pulse 1s ease-in-out infinite alternate;
        }
        @keyframes pulse { from { transform: scale(.94); opacity: .72; } to { transform: scale(1.04); opacity: 1; } }
        @media (max-width: 640px) {
            .badge { position: static; margin: 12px; }
            .hero-stats { gap: 20px; }
            .footer-news form { flex-direction: column; }
            .footer-news button { height: 44px; }
        }
    </style>
</head>
<body>
<a href="#main" class="sr-only skip">Skip to main content</a>
<div class="preloader" id="preloader" aria-hidden="true"><div class="spinner"><?php include __DIR__ . '/logo-mark.svg.php'; ?></div></div>

<header class="nav" id="nav">
    <div class="container nav-inner">
        <a class="logo" href="<?php echo $base; ?>/index2.php" aria-label="<?php echo htmlspecialchars($brand); ?> home">
            <span class="logo-mark"><?php include __DIR__ . '/logo-mark.svg.php'; ?></span>
            <span><?php echo htmlspecialchars($brand); ?></span>
        </a>
        <nav class="desktop-nav" aria-label="Main">
            <a class="nav-link" href="#home">Home</a>
            <a class="nav-link" href="#about">About</a>
            <div class="dropdown">
                <button class="drop-btn" type="button">Services <i class="fa-solid fa-chevron-down"></i></button>
                <div class="dropdown-menu">
                    <ul>
                        <li><a href="#wealth-management">Wealth Management</a></li>
                        <li><a href="#financial-advisory">Financial Advisory</a></li>
                        <li><a href="#real-time-analytics">Real-Time Market Analytics</a></li>
                        <li><a href="#cryptocurrency-investments">Cryptocurrency Investments</a></li>
                        <li><a href="#hr-consulting">HR Consulting</a></li>
                        <li><a href="#marketing-consulting">Marketing Consulting</a></li>
                    </ul>
                </div>
            </div>
            <div class="dropdown">
                <button class="drop-btn" type="button">Auth <i class="fa-solid fa-chevron-down"></i></button>
                <div class="dropdown-menu" style="width:176px">
                    <ul>
                        <li><a href="/login.php">Sign In</a></li>
                        <li><a href="/register.php">Sign Up</a></li>
                    </ul>
                </div>
            </div>
            <a class="nav-link" href="#faq">FAQ</a>
            <a class="nav-link" href="#footer">Contact Us</a>
        </nav>
        <div class="nav-actions">
            <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle dark mode"><i class="fa-solid fa-moon"></i></button>
            <?php if ($is_logged_in): ?>
                <a class="btn btn-primary join-desktop" href="/dashboard/">Dashboard</a>
            <?php else: ?>
                <a class="btn btn-primary join-desktop" href="/register.php">Join Now</a>
            <?php endif; ?>
            <button class="icon-btn menu-btn" id="menuOpen" type="button" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</header>

<aside class="mobile-panel" id="mobilePanel" aria-label="Mobile navigation">
    <div class="mobile-head">
        <a class="logo" href="<?php echo $base; ?>/index2.php"><span class="logo-mark"><?php include __DIR__ . '/logo-mark.svg.php'; ?></span><span><?php echo htmlspecialchars($brand); ?></span></a>
        <button class="icon-btn" id="menuClose" type="button" aria-label="Close menu"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <nav class="mobile-links">
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <div class="mobile-label">Services</div>
        <a href="#wealth-management">Wealth Management</a>
        <a href="#financial-advisory">Financial Advisory</a>
        <a href="#real-time-analytics">Real-Time Market Analytics</a>
        <a href="#cryptocurrency-investments">Cryptocurrency Investments</a>
        <a href="#hr-consulting">HR Consulting</a>
        <a href="#marketing-consulting">Marketing Consulting</a>
        <div class="mobile-label">Account</div>
        <a href="/login.php">Sign In</a>
        <a href="/register.php">Sign Up</a>
        <a href="#footer">Contact Us</a>
    </nav>
    <div class="mobile-foot">
        <?php if ($is_logged_in): ?><a class="btn btn-primary" href="/dashboard/">Dashboard</a><?php else: ?><a class="btn btn-primary" href="/register.php">Join Now</a><?php endif; ?>
    </div>
</aside>

<main id="main">
    <section class="hero" id="home">
        <div class="mesh"></div>
        <div class="orb top"></div>
        <div class="orb bottom"></div>
        <div class="container hero-grid">
            <div class="reveal">
                <span class="hero-pill"><i class="fa-solid fa-arrow-trend-up"></i> Intelligent Trading Platform</span>
                <h1 style="margin-top:20px">Invest Your Money With Higher Returns</h1>
                <p>Anyone can invest money in different currencies and assets to grow their earnings - powered by <?php echo htmlspecialchars($brand); ?>'s intelligent trading platform.</p>
                <div class="hero-actions">
                    <a class="btn btn-white" href="/register.php">Get Started <i class="fa-solid fa-arrow-right"></i></a>
                    <a class="btn btn-outline-white" href="/login.php">Sign In</a>
                </div>
                <dl class="hero-stats">
                    <div><dt>$2B+</dt><dd>Assets Managed</dd></div>
                    <div><dt>25K+</dt><dd>Active Investors</dd></div>
                    <div><dt>4.9</dt><dd>Average Rating</dd></div>
                </dl>
            </div>
            <div class="hero-art reveal">
                <img src="<?php echo $base; ?>/images/hero-dashboard.webp" alt="Isometric illustration of the PrimeAxis trading dashboard showing charts and portfolio gains" width="720" height="620">
                <div class="float-card">
                    <p>Portfolio Value</p>
                    <strong>$124,500</strong>
                    <span>&#9650; 8.4%</span>
                </div>
            </div>
        </div>
        <div class="wave"><svg viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0,32 C240,80 480,80 720,48 C960,16 1200,16 1440,48 L1440,80 L0,80 Z"></path></svg></div>
    </section>

    <section class="partner">
        <div class="container">
            <p>Trusted by leading financial partners</p>
            <div class="marquee-wrap">
                <div class="marquee">
                    <span>NexaBank</span><span>FinVault</span><span>TradeSphere</span><span>CoinAxis</span><span>WealthPath</span>
                    <span>NexaBank</span><span>FinVault</span><span>TradeSphere</span><span>CoinAxis</span><span>WealthPath</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="about">
        <div class="container about-grid">
            <div class="about-image reveal">
                <div class="image-shell"><img src="<?php echo $base; ?>/images/about-advisor.webp" alt="Financial advisor working at a modern desk with charts on dual monitors" width="640" height="560"></div>
                <div class="badge one"><span class="badge-icon gold"><i class="fa-solid fa-award"></i></span><div><strong>30+</strong><span>Years Experience</span></div></div>
                <div class="badge two"><span class="badge-icon purple"><i class="fa-solid fa-users"></i></span><div><strong>25,000+</strong><span>Satisfied Clients</span></div></div>
            </div>
            <div class="copy reveal">
                <span class="eyebrow">Meet Our Company</span>
                <h2>A Forward-Thinking Fintech Firm Built for Everyone</h2>
                <p>At <?php echo htmlspecialchars($brand); ?>, we are committed to empowering individuals and businesses through innovative financial solutions. Our mission is to provide accessible, data-driven investment strategies that help clients grow and secure their financial future.</p>
                <p>With cutting-edge technology and a customer-centric approach, we simplify the complexities of investing so everyone can achieve their goals with confidence.</p>
                <a class="btn btn-outline" href="#services">Explore More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="section section-secondary" id="benefits">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">Why Choose Us</span>
                <h2>Benefits We Offer</h2>
                <p>Unlock the full potential of your portfolio with our platform's powerful features.</p>
            </div>
            <div class="benefit-layout">
                <div class="benefit-col">
                    <article class="benefit-card right reveal"><span class="card-icon"><i class="fa-solid fa-briefcase"></i></span><h3>Investment Lending</h3><p>Investment lending for new projects and ideas.</p><div class="benefit-stat">$10M Available</div></article>
                    <article class="benefit-card right reveal"><span class="card-icon"><i class="fa-solid fa-shield-halved"></i></span><h3>Bank-Level Security</h3><p>Full control over your funds with 256-bit encryption.</p><div class="benefit-stat">100% Protected</div></article>
                </div>
                <div class="phone-wrap reveal"><img src="<?php echo $base; ?>/images/benefits-phone.webp" alt="Smartphone showing the PrimeAxis investment dashboard app" width="420" height="720"></div>
                <div class="benefit-col">
                    <article class="benefit-card reveal"><span class="card-icon"><i class="fa-solid fa-mobile-screen"></i></span><h3>Mobile Payments</h3><p>Flexible mobile payments for investors on the go.</p><div class="benefit-stat">4.9* Rating</div></article>
                    <article class="benefit-card reveal"><span class="card-icon"><i class="fa-solid fa-badge-dollar"></i></span><h3>Zero Fees</h3><p>Zero transaction fees for Pro plan members.</p><div class="benefit-stat">0% Fees</div></article>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="services">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">What We Do</span>
                <h2>Services We Offer</h2>
                <p>Comprehensive financial solutions from experts who care about your growth.</p>
            </div>
            <div class="service-grid">
                <a class="service-card reveal" id="wealth-management" href="#footer"><span class="card-icon"><i class="fa-solid fa-scale-balanced"></i></span><h3>Wealth Management</h3><p>End-to-end wealth management, from asset allocation and retirement planning to long-term financial growth strategies.</p><span class="learn">Learn More <i class="fa-solid fa-arrow-right"></i></span></a>
                <a class="service-card reveal" id="financial-advisory" href="#footer"><span class="card-icon"><i class="fa-solid fa-arrow-trend-up"></i></span><h3>Financial Advisory</h3><p>Automated, low-cost advisory solutions that make high-quality financial guidance accessible to all.</p><span class="learn">Learn More <i class="fa-solid fa-arrow-right"></i></span></a>
                <a class="service-card reveal" id="real-time-analytics" href="#footer"><span class="card-icon"><i class="fa-solid fa-chart-line"></i></span><h3>Real-Time Market Analytics</h3><p>A cutting-edge analytics dashboard providing real-time market insights for data-driven decisions.</p><span class="learn">Learn More <i class="fa-solid fa-arrow-right"></i></span></a>
                <a class="service-card reveal" id="cryptocurrency-investments" href="#footer"><span class="card-icon"><i class="fa-brands fa-bitcoin"></i></span><h3>Cryptocurrency Investments</h3><p>Secure, diversified crypto asset portfolios in the rapidly evolving world of digital currencies.</p><span class="learn">Learn More <i class="fa-solid fa-arrow-right"></i></span></a>
                <a class="service-card reveal" id="hr-consulting" href="#footer"><span class="card-icon"><i class="fa-solid fa-users"></i></span><h3>HR Consulting</h3><p>Personalized planning including retirement strategies, tax optimization, and wealth preservation.</p><span class="learn">Learn More <i class="fa-solid fa-arrow-right"></i></span></a>
                <a class="service-card reveal" id="marketing-consulting" href="#footer"><span class="card-icon"><i class="fa-solid fa-bullhorn"></i></span><h3>Marketing Consulting</h3><p>Data-driven marketing strategy, brand positioning, and customer acquisition consulting.</p><span class="learn">Learn More <i class="fa-solid fa-arrow-right"></i></span></a>
            </div>
        </div>
    </section>

    <section class="section section-secondary">
        <div class="narrow">
            <div class="section-head reveal">
                <span class="eyebrow">How We Work</span>
                <h2>Our Product Roadmap</h2>
                <p>A transparent view of how we build and deliver value to our clients.</p>
            </div>
            <ol class="timeline">
                <li><span class="timeline-badge">P1</span><div class="timeline-row"><div class="timeline-half"><article class="timeline-card reveal"><div class="phase">Phase P1</div><h3>Project Research</h3><p>We gather data, set objectives, and analyze the market landscape to lay a solid foundation for every product we build.</p></article></div><div class="timeline-half"></div></div></li>
                <li><span class="timeline-badge">P2</span><div class="timeline-row"><div class="timeline-half"><article class="timeline-card reveal"><div class="phase">Phase P2</div><h3>Framing the Idea</h3><p>We define the vision, scope, and user stories before any development begins - ensuring alignment across all teams.</p></article></div><div class="timeline-half"></div></div></li>
                <li><span class="timeline-badge">P3</span><div class="timeline-row"><div class="timeline-half"><article class="timeline-card reveal"><div class="phase">Phase P3</div><h3>Design First Draft</h3><p>Our UX team produces wireframes and high-fidelity mockups, gathering feedback before development commences.</p></article></div><div class="timeline-half"></div></div></li>
                <li><span class="timeline-badge">P4</span><div class="timeline-row"><div class="timeline-half"><article class="timeline-card reveal"><div class="phase">Phase P4</div><h3>Final Design</h3><p>After rigorous iteration and testing, we lock in a sleek, modern design that perfectly captures the product vision.</p></article></div><div class="timeline-half"></div></div></li>
                <li><span class="timeline-badge">P5</span><div class="timeline-row"><div class="timeline-half"><article class="timeline-card reveal"><div class="phase">Phase P5</div><h3>Product Development</h3><p>Our engineering team builds with precision, using agile sprints and continuous deployment to hit every milestone.</p></article></div><div class="timeline-half"></div></div></li>
                <li><span class="timeline-badge">P6</span><div class="timeline-row"><div class="timeline-half"><article class="timeline-card reveal"><div class="phase">Phase P6</div><h3>Launch</h3><p>We release to the world with fanfare - and keep iterating. Our products are never truly done; they only get better.</p></article></div><div class="timeline-half"></div></div></li>
            </ol>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="team-head reveal">
                <div><span class="eyebrow">Our Experts</span><h2 style="margin-top:12px">Meet Our Advisers</h2><p>World-class experts ready to guide your financial journey.</p></div>
                <div class="team-controls"><button class="icon-btn" id="teamPrev" type="button" aria-label="Previous advisers"><i class="fa-solid fa-chevron-left"></i></button><button class="icon-btn" id="teamNext" type="button" aria-label="Next advisers"><i class="fa-solid fa-chevron-right"></i></button></div>
            </div>
            <div class="team-list" id="teamList">
                <?php
                $team = [
                    ['Dianne Russell', 'Trade Captain'], ['Gibson Webb', 'Strategic Advisor'], ['Courtney Henry', 'Management Consultant'], ['Albert Flores', 'Development Specialist'],
                    ['Darrell Steward', 'Growth Strategist'], ['Wade Warren', 'Trade Consultant'], ['Cody Fisher', 'HR Consultant'], ['Bessie Cooper', 'Financial Advisor'],
                ];
                foreach ($team as $idx => $member):
                    $img = $idx + 1;
                ?>
                <article class="team-card">
                    <div class="team-img"><img src="<?php echo $base; ?>/images/team-<?php echo $img; ?>.webp" alt="Portrait of <?php echo htmlspecialchars($member[0]); ?>, <?php echo htmlspecialchars($member[1]); ?>"><a class="linkedin" href="https://linkedin.com" target="_blank" rel="noopener" aria-label="<?php echo htmlspecialchars($member[0]); ?> on LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a></div>
                    <div class="team-body"><h3><?php echo htmlspecialchars($member[0]); ?></h3><p><?php echo htmlspecialchars($member[1]); ?></p></div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section section-secondary">
        <div class="container">
            <div class="section-head reveal"><span class="eyebrow">Testimonials</span><h2>What Our Clients Say</h2></div>
            <div class="testimonial-grid">
                <figure class="testimonial-card reveal"><i class="fa-solid fa-quote-right quote-icon"></i><div class="stars">★★★★★</div><blockquote>"PrimeAxis stands out for its innovative technology and user-friendly interface. I feel empowered to make truly informed investment decisions."</blockquote><figcaption class="author"><img src="<?php echo $base; ?>/images/client-1.webp" alt="Portrait of Mobarok Hossain"><div><strong>Mobarok Hossain</strong><span>Trade Master</span></div></figcaption></figure>
                <figure class="testimonial-card reveal"><i class="fa-solid fa-quote-right quote-icon"></i><div class="stars">★★★★★</div><blockquote>"PrimeAxis has completely transformed how I approach investing. Their intuitive platform and data-driven insights helped me see significant portfolio growth in just a few months."</blockquote><figcaption class="author"><img src="<?php echo $base; ?>/images/client-2.webp" alt="Portrait of Guy Hawkins"><div><strong>Guy Hawkins</strong><span>Trade Boss</span></div></figcaption></figure>
                <figure class="testimonial-card reveal"><i class="fa-solid fa-quote-right quote-icon"></i><div class="stars">★★★★★</div><blockquote>"As a small business owner, I was hesitant about investing. Their expert guidance and personalized strategies have helped me secure my financial future. Highly recommended!"</blockquote><figcaption class="author"><img src="<?php echo $base; ?>/images/client-3.webp" alt="Portrait of Belal Hossain"><div><strong>Belal Hossain</strong><span>Trade Genius</span></div></figcaption></figure>
            </div>
        </div>
    </section>

    <section class="section" id="faq">
        <div class="container faq-grid">
            <div class="faq-image reveal"><div class="image-shell"><img src="<?php echo $base; ?>/images/faq-support.webp" alt="Friendly customer support specialist wearing a headset" width="600" height="620"></div></div>
            <div class="copy reveal">
                <span class="eyebrow">Got Questions?</span>
                <h2>Frequently Asked Questions</h2>
                <p>Everything you need to know about investing with us. Can't find an answer? Reach out to our support team anytime.</p>
                <div class="faq-list">
                    <article class="faq-item open"><button class="faq-q" type="button">What does PrimeAxis do? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>We are a fintech investment platform that lets you invest in currencies, cryptocurrencies, stocks, and other assets - all from one easy-to-use dashboard.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">What are the disadvantages of online trading? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>Like all investments, online trading carries risk. However, our platform provides risk management tools, stop-loss settings, and expert guidance to help minimize exposure.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">Is online trading safe on your platform? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>Yes. We use bank-level 256-bit encryption, two-factor authentication, and strict KYC/AML compliance to protect your account and funds.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">What is online trading and how does it work? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>Online trading lets you buy and sell financial assets through our platform without the need for a traditional broker, giving you control and transparency 24/7.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">Which plan is best for beginners? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>We recommend starting with our Starter plan, which includes guided tutorials, low minimum deposits, and access to our AI-powered portfolio suggestions.</p></div></article>
                    <article class="faq-item"><button class="faq-q" type="button">How do I create a trading account? <i class="fa-solid fa-chevron-down"></i></button><div class="faq-a"><p>Click Join Now or Sign Up, fill in your details, verify your identity, deposit your starting funds, and start investing in under 10 minutes.</p></div></article>
                </div>
            </div>
        </div>
    </section>

    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter reveal">
                <div class="mesh"></div><div class="orb left"></div><div class="orb right"></div>
                <div style="position:relative;z-index:1;max-width:700px;margin:0 auto">
                    <h2>Subscribe to Our Newsletter</h2>
                    <p>Stay ahead of the market. Get weekly insights, investment tips, and platform updates delivered to your inbox.</p>
                    <form class="newsletter-form" id="ctaForm"><input type="email" placeholder="Enter your email address" aria-label="Email address" required><button class="btn" type="submit">Subscribe Now <i class="fa-solid fa-paper-plane"></i></button></form>
                </div>
            </div>
        </div>
    </section>
</main>

<footer id="footer">
    <div class="footer-news">
        <div class="container">
            <div style="text-align:center"><h2>Subscribe to our news</h2><p>Stay in the loop with our latest updates.</p></div>
            <form id="footerForm"><input type="email" placeholder="Enter your email" aria-label="Footer email address" required><button class="btn btn-primary" type="submit">Subscribe</button></form>
        </div>
    </div>
    <div class="footer-main">
        <div class="container footer-grid">
            <div>
                <a class="logo" href="<?php echo $base; ?>/index2.php"><span class="logo-mark"><?php include __DIR__ . '/logo-mark.svg.php'; ?></span><span><?php echo htmlspecialchars($brand); ?></span></a>
                <p style="margin-top:16px;max-width:320px">The future of trading, investing, saving and earning.</p>
                <div class="socials"><a href="https://twitter.com" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a><a href="https://linkedin.com" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a><a href="https://facebook.com" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a><a href="https://telegram.org" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a><a href="https://instagram.com" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></div>
            </div>
            <div class="footer-col"><h3>Quick Links</h3><ul><li><a href="#about">About Us</a></li><li><a href="#services">Services</a></li><li><a href="#benefits">Features</a></li><li><a href="#">Blog</a></li></ul></div>
            <div class="footer-col"><h3>Support</h3><ul><li><a href="#">Terms & Conditions</a></li><li><a href="#">Privacy Policy</a></li><li><a href="#faq">FAQs</a></li><li><a href="mailto:support@primeaxisinv.com">Support Center</a></li></ul></div>
            <div class="footer-col"><h3>Company</h3><ul><li><a href="#">Careers</a></li><li><a href="#">Updates</a></li><li><a href="#">Jobs</a></li><li><a href="#">Announcements</a></li></ul></div>
        </div>
        <div class="copyright">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brand); ?>. All Rights Reserved.</div>
    </div>
</footer>

<script>
    const html = document.documentElement;
    const nav = document.getElementById('nav');
    const preloader = document.getElementById('preloader');
    const themeToggle = document.getElementById('themeToggle');
    const mobilePanel = document.getElementById('mobilePanel');
    const savedTheme = localStorage.getItem('primeaxis-v0-theme');
    if (savedTheme === 'dark') html.classList.add('dark');
    function syncThemeIcon() {
        themeToggle.innerHTML = html.classList.contains('dark') ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
    }
    syncThemeIcon();
    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('primeaxis-v0-theme', html.classList.contains('dark') ? 'dark' : 'light');
        syncThemeIcon();
    });
    window.addEventListener('load', () => setTimeout(() => preloader.classList.add('hide'), 550));
    window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 80), { passive: true });
    function closeMenu() { mobilePanel.classList.remove('open'); document.body.classList.remove('menu-open'); }
    document.getElementById('menuOpen').addEventListener('click', () => { mobilePanel.classList.add('open'); document.body.classList.add('menu-open'); });
    document.getElementById('menuClose').addEventListener('click', closeMenu);
    mobilePanel.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
    document.querySelectorAll('.faq-q').forEach(btn => btn.addEventListener('click', () => btn.closest('.faq-item').classList.toggle('open')));
    const teamList = document.getElementById('teamList');
    document.getElementById('teamPrev').addEventListener('click', () => teamList.scrollBy({ left: -teamList.clientWidth * .8, behavior: 'smooth' }));
    document.getElementById('teamNext').addEventListener('click', () => teamList.scrollBy({ left: teamList.clientWidth * .8, behavior: 'smooth' }));
    document.querySelectorAll('form').forEach(form => form.addEventListener('submit', event => {
        event.preventDefault();
        const button = form.querySelector('button');
        if (button) {
            const old = button.innerHTML;
            button.innerHTML = '<i class="fa-solid fa-check"></i> Subscribed';
            setTimeout(() => button.innerHTML = old, 1800);
        }
        form.reset();
    }));
    const observer = new IntersectionObserver(entries => {
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
