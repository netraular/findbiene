<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comparte Biene Hunt!</title>

    <!-- Fonts (Opcional, puedes usar fuentes del sistema para impresión) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* Estilos específicos para esta página, optimizados para impresión */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        body {
            background-color: #ffffff; /* Fondo blanco */
            color: #000000; /* Texto negro */
            font-family: 'Poppins', sans-serif; /* O una fuente sans-serif genérica */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centrar verticalmente */
            align-items: center; /* Centrar horizontalmente */
            min-height: 100vh; /* Asegurar que ocupa al menos toda la altura */
            text-align: center; /* Centrar texto */
            padding: 20px; /* Añadir un poco de espacio alrededor */
            box-sizing: border-box; /* Incluir padding en el tamaño total */
        }
        .container {
            max-width: 500px; /* Limitar el ancho del contenido */
        }
        .qr-code-link img {
            display: block; /* Para que el margen auto funcione bien */
            margin: 20px auto; /* Espacio arriba/abajo y centrado horizontal */
            max-width: 80%; /* Hacerlo responsivo pero no gigantesco */
            height: auto;
            /* Ajusta el tamaño máximo si es necesario, ej: max-width: 300px; */
        }
        .description {
            font-size: 1.1em;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .url-text {
            font-size: 1.2em;
            font-weight: 600;
            margin-top: 10px;
            color: #333; /* Un gris oscuro */
            word-break: break-all; /* Para evitar que la URL larga rompa el layout */
        }
        /* Media Query para impresión: Ocultar cosas innecesarias si las hubiera */
        @media print {
            body {
                /* Puedes añadir ajustes específicos para impresión si es necesario */
                padding: 5mm; /* Márgenes de impresión */
                min-height: initial; /* No forzar altura mínima en impresión */
            }
            /* Ejemplo: @page { size: A4; margin: 10mm; } */
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Descripción del sitio --}}
        <p class="description">
            ¡Únete a la caza de Biene en Hack UPC!
            Escanea el código QR o visita la web para ver los últimos avistamientos y subir los tuyos.
        </p>

        {{-- Enlace a la página principal envolviendo la imagen QR --}}
        <a href="{{ url('/') }}" class="qr-code-link" title="Ir a Biene Hunt!">
            {{-- Asegúrate de que 'qr.png' está en la carpeta public/images --}}
            <img src="{{ asset('images/qr.png') }}" alt="Código QR para Biene Hunt">
        </a>

        {{-- Texto de la URL debajo del QR --}}
        <p class="url-text">
            https://biene.photo
        </p>

        {{-- Podrías añadir un pequeño logo o nota adicional si quieres --}}
        {{-- <p style="margin-top: 30px; font-size: 0.8em; color: #555;">Hack UPC 2025</p> --}}
    </div>
</body>
</html>