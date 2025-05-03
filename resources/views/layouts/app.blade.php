<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Encuentra a Biene</title> {{-- Título modificado --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Estilos básicos adicionales (opcional) --}}
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        #app {
            flex: 1;
        }
        footer {
            background-color: #f8f9fa; /* Un color de fondo suave para el footer */
            font-size: 0.9em;
        }
        .gallery-img {
            width: 100%;
            height: 200px; /* Altura fija para las imágenes de la galería */
            object-fit: cover; /* Para que las imágenes cubran el espacio sin deformarse */
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div id="app">
        {{-- Navbar eliminado --}}

        <main class="py-4">
            @yield('content')
        </main>

    </div>

    {{-- Footer añadido --}}
    <footer class="text-center mt-auto py-3 border-top">
        <div class="container">
            <p class="mb-1">Web creada para Hack UPC 2025 gracias al dominio gratuito ofrecido por GoDaddy a través de MLH.</p>
            <p class="mb-0">Más sobre mí o contacto en <a href="https://raular.com" target="_blank" rel="noopener noreferrer">raular.com</a>.</p>
        </div>
    </footer>
</body>
</html>