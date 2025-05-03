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
            font-family: 'Poppins', sans-serif;
            color: #e5e7eb; /* Light grey text */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scrollbar from ghosts */
            padding-bottom: 80px; /* Space for fixed footer */
            position: relative;
        }

        /* Ghost Animation Container */
        #background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2; /* Behind everything */
            overflow: hidden;
            pointer-events: none;
        }

        /* Full Page Dark Overlay Styles */
        #page-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Black with 40% opacity */
            z-index: -1; /* Above background (-1), below content (1) */
            pointer-events: none;
        }

        #background-animation .ghost {
            position: absolute;
            width: 50px;
            height: auto;
            opacity: 0.7;
            animation: moveGhost 15s infinite alternate ease-in-out;
        }

        /* Position ghosts randomly */
        #ghost-1 { top: 10%; left: 15%; animation-duration: 18s; animation-delay: 0s; }
        #ghost-2 { top: 30%; left: 80%; animation-duration: 14s; animation-delay: -5s; } /* blue */
        #ghost-3 { top: 70%; left: 50%; animation-duration: 20s; animation-delay: -10s;} /* orange */
        #ghost-4 { top: 85%; left: 10%; animation-duration: 16s; animation-delay: -2s; }
        #ghost-5 { top: 5%; left: 45%; animation-duration: 13s; animation-delay: -8s; } /* blue */
        #ghost-6 { top: 50%; left: 90%; animation-duration: 19s; animation-delay: -12s; } /* orange */
        #ghost-7 { top: 60%; left: 20%; animation-duration: 17s; animation-delay: -3s; } /* Repeat yellow */


        #app {
            flex: 1; /* Allows content to fill space above fixed footer */
        }

        /* Use the retro font for specific elements */
        .font-retro {
            font-family: 'Press Start 2P', cursive;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Hero Section Adjustments */
        .hero-section {
             color: #fff;
             text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
             padding-top: 3rem;
             padding-bottom: 3rem;
        }
        .hero-section h1 {
             color: #facc15;
        }
        .hack-logo {
            filter: invert(1) brightness(1.5) drop-shadow(0 0 5px #fff);
            max-width: 200px;
            margin-bottom: 1rem;
        }


        /* Upload Section */
        .upload-section {
             background-color: rgba(31, 41, 55, 0.8);
             border: 2px solid #facc15;
             border-radius: 15px;
             padding: 2rem;
             box-shadow: 0 0 20px rgba(250, 204, 21, 0.3);
             margin-bottom: 3rem;
        }
        .upload-button {
            background: linear-gradient(45deg, #ec4899, #f97316);
            border: none;
            color: #fff;
            padding: 1rem 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 50px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
            box-shadow: 0 5px 15px rgba(236, 72, 153, 0.4);
        }
        .upload-button:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.6);
        }
        /* Error message styling */
        .upload-form .invalid-feedback {
            color: #fca5a5;
            font-weight: bold;
            background-color: rgba(185, 28, 28, 0.7);
            padding: 0.5rem;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
            text-align: center;
        }
         #fileName {
             color: #9ca3af;
         }
        .alert-warning {
            background-color: rgba(251, 191, 36, 0.2);
            border-color: #facc15;
            color: #fef3c7;
        }
        .alert-warning strong {
            color: #fef08a;
        }
        .alert-success {
            background-color: rgba(52, 211, 153, 0.2);
            border-color: #34d399;
            color: #d1fae5;
            text-align: center;
            font-weight: bold;
        }

        /* Gallery Styling */
        .gallery-section h2 {
            color: #60a5fa;
            text-shadow: 1px 1px 3px rgba(96, 165, 250, 0.5);
        }
        .gallery-section {
             padding-bottom: 3rem;
        }
        /* Polaroid Effect */
        .polaroid-effect {
            background-color: #374151;
            padding: 10px 10px 20px 10px;
            border: 1px solid #4b5563;
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            border-radius: 3px;
        }
        .polaroid-effect:hover {
            transform: rotate(2deg) scale(1.05);
            box-shadow: 0 8px 16px rgba(0,0,0,0.6);
            border-color: #facc15;
        }
        .polaroid-effect .card-img-top { height: 200px; object-fit: cover; display:block; width: 100%;}
        .polaroid-effect .card-body { min-height: 40px; padding-top: 10px; }
        .polaroid-effect small.text-muted { color: #9ca3af !important; }
        .gallery-section .text-muted { color: #9ca3af !important; }


        /* Footer Styling */
        footer {
            position: fixed; /* Footer fijo */
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #111827; /* Color opaco */
            font-size: 0.9em;
            color: #9ca3af;
            border-top: 1px solid #374151;
            padding-top: 1rem;
            padding-bottom: 1rem;
            z-index: 1; /* Encima del overlay (z-index: 0) */
        }
        footer a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: bold;
        }
        footer a:hover {
            color: #93c5fd;
            text-decoration: underline;
        }

        /* --- CORRECCIÓN PARA EL MODAL --- */
        /* Asegurar que el modal y su fondo estén por encima de #app (z-index: 1) y el footer (z-index: 1) */
        /* ============================= */
        /* Dark Theme Pagination Styles  */
        /* ============================= */

        .pagination {
             --bs-pagination-color: #9ca3af; /* Color texto normal (gris claro de tu tema) */
             --bs-pagination-bg: #1f2937; /* Fondo (usando el de modal/upload section) */
             --bs-pagination-border-color: #374151; /* Borde (gris oscuro de tu tema) */
             --bs-pagination-hover-color: #e5e7eb; /* Texto hover (el gris más claro) */
             --bs-pagination-hover-bg: #374151; /* Fondo hover (borde oscuro como base) */
             --bs-pagination-hover-border-color: #4b5563; /* Borde hover (un poco más claro) */
             --bs-pagination-focus-color: #e5e7eb; /* Texto focus */
             --bs-pagination-focus-bg: #374151; /* Fondo focus */
             --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(96, 165, 250, 0.3); /* Sombra focus azul (como tu gallery title) */
             --bs-pagination-active-color: #111827; /* Texto activo (oscuro para contraste con amarillo) */
             --bs-pagination-active-bg: #facc15; /* Fondo activo (tu amarillo característico) */
             --bs-pagination-active-border-color: #facc15; /* Borde activo (amarillo) */
             --bs-pagination-disabled-color: #4b5563; /* Texto deshabilitado (gris muy oscuro) */
             --bs-pagination-disabled-bg: #111827; /* Fondo deshabilitado (el fondo base) */
             --bs-pagination-disabled-border-color: #374151; /* Borde deshabilitado */
        }

        /* Opcional: Añadir una transición suave */
        .pagination .page-link {
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
       /* --- FIN DE LA CORRECCIÓN --- */

        /* Modal Dark Mode Adjustments (Estilos existentes) */
        .modal-content {
            background-color: #1f2937;
            color: #e5e7eb;
        }
        .modal-header {
            border-bottom: 1px solid #374151;
        }
        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%); /* Botón de cierre blanco */
        }
        .modal-title {
            color: #60a5fa; /* Título azul */
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

    <div id="app"> {{-- Este div ahora tiene z-index: 1 --}}
        {{-- No Navbar --}}
        <main>
            @yield('content') {{-- Todo el contenido, incluyendo los modales, está dentro de #app --}}
        </main>
    </div>

    {{-- Footer (ahora fijo, opaco y con z-index: 1) --}}
    <footer class="text-center py-3">
        <div class="container">
        <p class="mb-1">Biene Hunt site for Hack UPC 2025 | 
        <a href="https://findbiene.raular.com/share">biene.photo</a>
        </p>
            <p class="mb-0">Contact / More info: <a href="https://raular.com" target="_blank" rel="noopener noreferrer">raular.com</a></p>
        </div>
    </footer>

    {{-- Script Stack --}}
    @stack('scripts')
</body>
</html>