<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Share Biene Hunt!</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

    <style>
        /* Ghost Animation Keyframes (Screen Only) */
        @media screen {
            @keyframes moveGhost {
                0% { transform: translate(0, 0) rotate(0deg); opacity: 0.4; }
                25% { transform: translate(15px, 20px) rotate(5deg); opacity: 0.6; }
                50% { transform: translate(-10px, -15px) rotate(-3deg); opacity: 0.5; }
                75% { transform: translate(5px, -25px) rotate(4deg); opacity: 0.7; }
                100% { transform: translate(0, 0) rotate(0deg); opacity: 0.4; }
            }
        }

        /* Base styles */
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; }
        body {
            background-color: #f8f9fa; /* Screen background */
            color: #000000;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            min-height: 100vh; text-align: center; padding: 1rem; box-sizing: border-box;
            line-height: 1.6; position: relative; overflow-x: hidden;
        }

        /* Animated Background Ghosts (Screen Only) */
        #background-animation { display: none; /* Initially hidden, shown by screen media query */ }
        @media screen {
            #background-animation {
                display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                z-index: 0; overflow: hidden; pointer-events: none;
            }
            #background-animation .ghost {
                position: absolute; width: 45px; height: auto; opacity: 0.5;
                animation: moveGhost 15s infinite alternate ease-in-out;
            }
            /* Screen positions for background ghosts */
            #ghost-1 { top: 10%; left: 15%; animation-duration: 18s; }
            #ghost-2 { top: 30%; left: 80%; animation-duration: 14s; animation-delay: -5s; } /* blue */
            #ghost-3 { top: 70%; left: 50%; animation-duration: 20s; animation-delay: -10s;} /* orange */
            #ghost-4 { top: 85%; left: 10%; animation-duration: 16s; animation-delay: -2s; }
            #ghost-5 { top: 5%; left: 45%; animation-duration: 13s; animation-delay: -8s; } /* blue */
            #ghost-6 { top: 50%; left: 90%; animation-duration: 19s; animation-delay: -12s; } /* orange */
        }

        /* Content wrapper - Panel Style */
        .content-wrapper {
            position: relative; z-index: 1; max-width: 600px; width: 95%;
            background-color: #ffffff; padding: 2rem 2.5rem; border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); margin: 1rem 0;
        }

        /* Title, Description styles */
        .page-title { font-size: 2rem; font-weight: 700; margin-top: 0; margin-bottom: 1.5rem; color: #1a1a1a; }
        .description { font-size: 1.1rem; margin-bottom: 2rem; color: #333333; }

        /* QR Section - Default/Screen */
        .qr-print-section {
             /* On screen, just acts as a container, QR centers itself */
             margin-bottom: 1.5rem;
        }
        .qr-code-link { display: block; /* Keeps QR link behavior */ }
        .qr-image {
            display: block; margin: 0 auto; /* Center QR on screen */
            max-width: 300px; width: 70%; height: auto;
        }

        /* Print-Specific Ghosts (Hidden on Screen) */
        .print-ghost {
            display: none;
        }

        /* URL Text Styling */
        .url-text {
            font-size: 1.2rem; font-weight: 600; margin-top: 0.5rem; color: #000000;
            word-break: break-all;
            margin-bottom: 1.5rem; /* Space below URL */
        }

        /* Credit Text Styling (Screen) */
        .credit-text {
            font-size: 0.85rem; /* Smaller */
            color: #6c757d;   /* Greyish color */
            margin-top: 1.5rem; /* Space above */
            margin-bottom: 0; /* No extra space below */
        }


        /* ------------- PRINT STYLES ------------- */
        @media print {
            html, body {
                background-color: #ffffff !important; color: #000000 !important;
                padding: 0 !important; font-size: 11pt; display: block !important;
                width: 100%; height: 100%; min-height: initial;
            }

            /* HIDE animated background ghosts */
            #background-animation { display: none !important; }

            /* Reset content wrapper */
            .content-wrapper {
                position: static !important; /* Natural flow */
                z-index: auto !important;
                width: 100% !important; max-width: 100% !important;
                background-color: #ffffff !important; padding: 0 !important;
                border-radius: 0 !important; box-shadow: none !important;
                border: none !important; margin: 0 !important;
            }

             /* Keep text centered */
            .content-wrapper h1, .content-wrapper p { text-align: center; }

            /* Style the QR Print Section using Flexbox */
            .qr-print-section {
                display: flex !important; /* ACTIVATE FLEXBOX */
                align-items: center;      /* Vertically center */
                justify-content: center;  /* Horizontally center */
                gap: 10mm;                 /* Space between items */
                margin: 10mm 0;           /* Space above/below */
                page-break-inside: avoid; /* Keep together */
            }

            /* SHOW and style the print-specific ghosts */
            .print-ghost {
                display: block !important; /* MAKE VISIBLE */
                width: 25mm;               /* Adjust size */
                height: auto;
                flex-shrink: 0; /* Prevent shrinking */
                opacity: 0.8;   /* Optional fade */
            }

            /* Adjust QR code link and image within flex */
            .qr-print-section .qr-code-link {
                 line-height: 0; /* Prevent extra space from link */
            }
            .qr-print-section .qr-image {
                width: 55mm; /* Adjust size */
                max-width: 100%;
                margin: 0 !important; /* REMOVE auto margin */
            }

            /* Adjust text/URL/Credit sizes for print */
            .page-title { font-size: 18pt; color: #000000 !important; page-break-after: avoid; margin: 0 0 5mm 0; }
            .description { font-size: 12pt; color: #000000 !important; page-break-after: avoid; margin: 0 0 7mm 0; }
            .url-text { font-size: 13pt; color: #000000 !important; page-break-before: avoid; margin: 5mm 0 5mm 0; text-align: center; } /* Added bottom margin */
            .credit-text {
                font-size: 9pt; /* Small */
                color: #333333 !important; /* Dark grey */
                text-align: center;
                margin-top: 5mm; /* Space above */
                margin-bottom: 0;
                page-break-before: avoid; /* Avoid starting new page with just this */
            }

            /* Define print page margins */
            @page {
                margin: 15mm;
                size: A4; /* Or 'letter' */
            }
        }
    </style>
</head>
<body>

    {{-- Animated Background Div (Only for Screen) --}}
    <div id="background-animation">
        <img src="{{ asset('images/yellow_ghost.svg') }}" alt="" class="ghost" id="ghost-1">
        <img src="{{ asset('images/blue_ghost.svg') }}" alt="" class="ghost" id="ghost-2">
        <img src="{{ asset('images/orange_ghost.svg') }}" alt="" class="ghost" id="ghost-3">
        <img src="{{ asset('images/yellow_ghost.svg') }}" alt="" class="ghost" id="ghost-4">
        <img src="{{ asset('images/blue_ghost.svg') }}" alt="" class="ghost" id="ghost-5">
        <img src="{{ asset('images/orange_ghost.svg') }}" alt="" class="ghost" id="ghost-6">
    </div>

    {{-- Content Panel --}}
    <div class="content-wrapper">

        <h1 class="page-title">Join the Biene Hunt!</h1>

        <p class="description">
            Spotted Biene at Hack UPC? Scan the QR code or visit the site below
            to see the latest sightings and upload your own discovery.
        </p>

        {{-- Container for QR and Print Ghosts --}}
        <div class="qr-print-section">
            {{-- Left Ghost (Print Only) --}}
            <img src="{{ asset('images/yellow_ghost.svg') }}" alt="Biene Ghost" class="print-ghost print-ghost-left">

            {{-- QR Code Link --}}
            <a href="{{ url('/') }}" class="qr-code-link" title="Go to Biene Hunt!">
                {{-- Ensure 'qr.png' is in public/images/ --}}
                <img src="{{ asset('images/qr.png') }}" alt="QR Code for Biene Hunt website" class="qr-image">
            </a>

            {{-- Right Ghost (Print Only) --}}
            <img src="{{ asset('images/blue_ghost.svg') }}" alt="Biene Ghost" class="print-ghost print-ghost-right">
        </div>

        {{-- URL Text (Non-clickable) --}}
        <p class="url-text">
            https://biene.photo
        </p>

        {{-- Credit Text --}}
        <p class="credit-text">
            Free domain thanks to GoDaddy & MLH
        </p>

    </div>

</body>
</html>