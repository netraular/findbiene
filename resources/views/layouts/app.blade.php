<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Biene Hunt!</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Use a more playful or retro font? Press Start 2P is very retro --}}
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons (Optional) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Vite Scripts & Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Custom Styles --}}
    <style>
        @keyframes moveGhost {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 0.6; }
            25% { transform: translate(15px, 20px) rotate(5deg); opacity: 0.8; }
            50% { transform: translate(-10px, -15px) rotate(-3deg); opacity: 0.7; }
            75% { transform: translate(5px, -25px) rotate(4deg); opacity: 0.9; }
            100% { transform: translate(0, 0) rotate(0deg); opacity: 0.6; }
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 15px 5px rgba(255, 255, 0, 0.5); } /* Yellow glow */
            50% { transform: scale(1.05); box-shadow: 0 0 25px 10px rgba(255, 255, 0, 0.7); }
            100% { transform: scale(1); box-shadow: 0 0 15px 5px rgba(255, 255, 0, 0.5); }
        }


        body {
            /* --- DARK MODE BACKGROUND --- */
            background-color: #111827; /* Dark blue/grey */
            /* Or a very dark: background-color: #0a0a1e; */
            font-family: 'Poppins', sans-serif; /* Keep Poppins for readability */
            color: #e5e7eb; /* Light grey text */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scrollbar from ghosts */
            padding-bottom: 80px; /* <<< Space for fixed footer. Adjust height as needed */
            position: relative; /* Needed for absolute/fixed children like overlay */
        }

        /* Ghost Animation Container */
        #background-animation {
            position: fixed; /* Keep ghosts fixed in viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Behind everything else */
            overflow: hidden; /* Ghosts shouldn't cause scrollbars */
            pointer-events: none; /* Prevent ghosts from blocking clicks */
        }

        /* Full Page Dark Overlay Styles */
        #page-overlay {
            position: fixed; /* Cover the entire viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Black with 40% opacity - AJUSTA ESTE VALOR (0.0 a 1.0) */
            z-index: 0; /* Above background animation (-1), below content (1) */
            pointer-events: none; /* Allow clicks to go through to the content below */
        }

        #background-animation .ghost {
            position: absolute;
            width: 50px; /* Adjust size as needed */
            height: auto;
            opacity: 0.7; /* Start slightly transparent */
            animation: moveGhost 15s infinite alternate ease-in-out;
        }

        /* Position ghosts randomly (adjust these values) */
        #ghost-1 { top: 10%; left: 15%; animation-duration: 18s; animation-delay: 0s; }
        #ghost-2 { top: 30%; left: 80%; animation-duration: 14s; animation-delay: -5s; } /* blue */
        #ghost-3 { top: 70%; left: 50%; animation-duration: 20s; animation-delay: -10s;} /* orange */
        #ghost-4 { top: 85%; left: 10%; animation-duration: 16s; animation-delay: -2s; }
        #ghost-5 { top: 5%; left: 45%; animation-duration: 13s; animation-delay: -8s; } /* blue */
        #ghost-6 { top: 50%; left: 90%; animation-duration: 19s; animation-delay: -12s; } /* orange */
        #ghost-7 { top: 60%; left: 20%; animation-duration: 17s; animation-delay: -3s; } /* Repeat yellow */


        #app {
            flex: 1; /* Allows content to fill space above fixed footer */
            position: relative; /* Needed to establish stacking context for its children if any */
            z-index: 1; /* Ensure #app content is above #page-overlay (z-index: 0) */
        }

        /* Use the retro font for specific elements */
        .font-retro {
            font-family: 'Press Start 2P', cursive;
            text-transform: uppercase; /* Retro fonts often look better uppercase */
            letter-spacing: 2px;
        }

        /* Hero Section Adjustments */
        .hero-section {
             /* background: rgba(0, 0, 0, 0.3); <-- REMOVED - Overlay is now global via #page-overlay */
             color: #fff; /* Bright white */
             text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
             padding-top: 3rem;
             padding-bottom: 3rem;
        }
        .hero-section h1 {
             color: #facc15; /* Bright yellow for main title */
        }
        .hack-logo {
            filter: invert(1) brightness(1.5) drop-shadow(0 0 5px #fff); /* Invert black logo and make it glow */
            max-width: 200px;
            margin-bottom: 1rem;
        }


        /* Upload Section - Make it pop */
        .upload-section {
             background-color: rgba(31, 41, 55, 0.8); /* Slightly transparent dark grey */
             border: 2px solid #facc15; /* Yellow border */
             border-radius: 15px;
             padding: 2rem;
             box-shadow: 0 0 20px rgba(250, 204, 21, 0.3); /* Yellow glow */
             margin-bottom: 3rem; /* Keep margin so it doesn't touch the footer visually unless scrolled fully */
        }
        .upload-button {
            /* --- COLORFUL BUTTON --- */
            background: linear-gradient(45deg, #ec4899, #f97316); /* Pink to Orange */
            border: none;
            color: #fff;
            padding: 1rem 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 50px; /* Pill shape */
            text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
            box-shadow: 0 5px 15px rgba(236, 72, 153, 0.4); /* Pink shadow */
        }
        .upload-button:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.6);
        }
        /* Error message styling for dark mode */
        .upload-form .invalid-feedback {
            color: #fca5a5; /* Light red */
            font-weight: bold;
            background-color: rgba(185, 28, 28, 0.7); /* Dark red background */
            padding: 0.5rem;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
            text-align: center;
        }
         #fileName {
             color: #9ca3af; /* Lighter grey for file name */
         }
        .alert-warning { /* Style the warning message */
            background-color: rgba(251, 191, 36, 0.2); /* Transparent yellow */
            border-color: #facc15; /* Yellow border */
            color: #fef3c7; /* Light yellow text */
        }
        .alert-warning strong {
            color: #fef08a; /* Brighter yellow for strong */
        }
        .alert-success { /* Style the success message */
            background-color: rgba(52, 211, 153, 0.2); /* Transparent green */
            border-color: #34d399; /* Green border */
            color: #d1fae5; /* Light green text */
            text-align: center;
            font-weight: bold;
        }

        /* Gallery Styling */
        .gallery-section h2 {
            color: #60a5fa; /* Bright Blue heading */
            text-shadow: 1px 1px 3px rgba(96, 165, 250, 0.5);
        }
        .gallery-section {
             padding-bottom: 3rem; /* Add some padding below gallery title */
        }
        /* Polaroid Effect for Dark Mode */
        .polaroid-effect {
            background-color: #374151; /* Dark grey card background */
            padding: 10px 10px 20px 10px;
            border: 1px solid #4b5563; /* Slightly lighter grey border */
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            border-radius: 3px; /* Subtle rounding */
        }
        .polaroid-effect:hover {
            transform: rotate(2deg) scale(1.05);
            box-shadow: 0 8px 16px rgba(0,0,0,0.6);
            border-color: #facc15; /* Highlight border on hover */
        }
        .polaroid-effect .card-img-top { height: 200px; object-fit: cover; display:block; width: 100%;}
        .polaroid-effect .card-body { min-height: 40px; padding-top: 10px; }
        .polaroid-effect small.text-muted { color: #9ca3af !important; } /* Override Bootstrap muted for dark */
        .gallery-section .text-muted { color: #9ca3af !important; } /* For empty message */


        /* Footer Styling */
        footer {
            position: fixed; /* <<< Make footer fixed */
            bottom: 0;       /* <<< Stick to bottom */
            left: 0;         /* <<< Align to left */
            width: 100%;     /* <<< Span full width */
            background-color: #111827; /* <<< CHANGED: Fully opaque dark color */
            font-size: 0.9em;
            color: #9ca3af; /* Grey text */
            border-top: 1px solid #374151; /* Dark border */
            padding-top: 1rem; /* Keep existing padding */
            padding-bottom: 1rem; /* Keep existing padding */
            z-index: 1; /* Ensure footer is also above the overlay */
        }
        footer a {
            color: #60a5fa; /* Light blue links */
            text-decoration: none;
            font-weight: bold;
        }
        footer a:hover {
            color: #93c5fd; /* Lighter blue on hover */
            text-decoration: underline;
        }
        /* Modal Dark Mode Adjustments (Bootstrap 5 usually handles this well, but just in case) */
        /* Modal z-index is handled by Bootstrap defaults now, should be > 1 */
        .modal-content {
            background-color: #1f2937;
            color: #e5e7eb;
        }
        .modal-header {
            border-bottom: 1px solid #374151; /* Dark border */
        }
        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%); /* Make close button white */
        }
        .modal-title {
            color: #60a5fa; /* Blue title */
        }

    </style>
</head>
<body>
    {{-- Animated Background Div --}}
    <div id="background-animation">
        <img src="/images/yellow_ghost.svg" alt="" class="ghost" id="ghost-1">
        <img src="/images/blue_ghost.svg" alt="" class="ghost" id="ghost-2">
        <img src="/images/orange_ghost.svg" alt="" class="ghost" id="ghost-3">
        <img src="/images/yellow_ghost.svg" alt="" class="ghost" id="ghost-4">
        <img src="/images/blue_ghost.svg" alt="" class="ghost" id="ghost-5">
        <img src="/images/orange_ghost.svg" alt="" class="ghost" id="ghost-6">
        <img src="/images/yellow_ghost.svg" alt="" class="ghost" id="ghost-7">
        {{-- Add more ghosts if desired --}}
    </div>

    {{-- Full Page Dark Overlay --}}
    <div id="page-overlay"></div>

    <div id="app">
        {{-- No Navbar --}}
        <main>
            @yield('content')
        </main>
    </div>

    {{-- Footer (now fixed position and opaque) --}}
    <footer class="text-center py-3">
        <div class="container">
            <p class="mb-1">Biene Hunt site for Hack UPC 2025 | Used free domain from GoDaddy & MLH.</p>
            <p class="mb-0">Contact / More info: <a href="https://raular.com" target="_blank" rel="noopener noreferrer">raular.com</a></p>
        </div>
    </footer>

    {{-- Script Stack --}}
    @stack('scripts')
</body>
</html>