@extends('layouts.app')

@section('content')
<div class="container-fluid p-0"> {{-- Full width needed for layout --}}

    {{-- Hero Section (Sin cambios) --}}
    <section class="hero-section text-center text-white pt-5 pb-4">
        <div class="container">
             <img src="/images/hackupcLogoBlack.svg" alt="Hack UPC Logo" class="hack-logo">
            <h1 class="display-5 fw-bold font-retro mb-3">Biene Hunt!</h1>
            <p class="lead fw-normal mb-4">Hack UPC 2025 Edition</p>
            {{-- Mensaje cambia ligeramente dependiendo del estado --}}
            @if ($submissionsOpen)
                <h2 class="h4 fw-light">Spotted Biene? Upload your sighting!</h2>
            @else
                 <h2 class="h4 fw-light">The hunt has concluded! Thanks for participating!</h2>
            @endif
        </div>
    </section>

    {{-- Upload Section --}}
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 upload-section">
                    <div class="text-center">

                        {{-- === CONDICIONAL PARA MOSTRAR FORMULARIO O MENSAJE DE CIERRE === --}}
                        @if ($submissionsOpen)

                            {{-- Success Message --}}
                            @if (session('success') && !Str::contains(session('success'), 'deleted')) {{-- No mostrar éxito de subida si acabamos de borrar --}}
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                             {{-- Error Message (for delete or other general errors) --}}
                             @if ($errors->has('delete_error') || $errors->has('upload_error'))
                                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                                    {{ $errors->first('delete_error') ?: $errors->first('upload_error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- Warning Message --}}
                            <div class="alert alert-warning mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Psst!</strong> Keep it mysterious! Try not to reveal the whole spot in your photo. Let others enjoy the hunt! 😉
                            </div>

                            <form method="POST" action="{{ route('images.upload') }}" enctype="multipart/form-data" class="upload-form">
                                @csrf

                                {{-- Input de archivo - ahora no se deshabilita aquí, se controla con JS/estado --}}
                                <input type="file" class="form-control" id="image" name="image" required accept="image/png, image/jpeg, image/gif" style="display: none;">

                                {{-- Label actúa como botón principal de subida --}}
                                <label for="image" class="btn btn-lg upload-button w-100 mb-2 font-retro">
                                    <i class="bi bi-camera-fill me-2"></i>
                                    Upload Biene Photo!
                                </label>
                                <small id="fileName" class="d-block text-muted mb-3"></small>

                                @error('image')
                                    <div class="invalid-feedback d-block mt-2">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror

                                {{-- Botón de Confirmación - ahora no se deshabilita aquí, se controla con JS/estado --}}
                                <button type="submit" class="btn btn-warning font-retro mt-3">Confirm Upload</button>
                            </form>

                        @else  {{-- Si las subidas están CERRADAS --}}

                            <div class="alert alert-info text-center" role="alert">
                                <h4 class="alert-heading font-retro"><i class="bi bi-stopwatch-fill me-2"></i>Time's Up!</h4>
                                <p>The Biene Hunt for Hack UPC 2025 has officially concluded.</p>
                                <hr>
                                <p class="mb-0">Thank you to everyone who participated! We hope you had fun finding Biene.</p>
                            </div>

                             {{-- Opcional: Mostrar un botón deshabilitado para claridad visual --}}
                            <label class="btn btn-lg upload-button w-100 mb-2 font-retro disabled" style="background: #6c757d; border-color: #6c757d; cursor: not-allowed; opacity: 0.65;">
                                <i class="bi bi-camera-fill me-2"></i>
                                Submissions Closed
                            </label>
                            <small class="d-block text-muted mb-3"> </small> {{-- Espacio para mantener layout similar --}}
                             <button type="button" class="btn btn-secondary font-retro mt-3" disabled>Upload Closed</button>

                        @endif
                        {{-- === FIN DEL CONDICIONAL === --}}

                    </div>
                </div>
                <div class="text-center">
                    <img src="{{ asset('images/organizers.jpg') }}"
                        alt="Meeting the HackUPC Organizers"
                        class="img-fluid rounded shadow-sm"
                        style="max-height: 450px; max-width: 100%;">
                    <p class="text-muted mt-2 fst-italic"><small>A quick picture from the amazing HackUPC team!</small></p>
                </div>
            </div>
        </div>
    </section>

    {{-- Gallery Section (Sin cambios estructurales) --}}
    <section class="gallery-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 display-6 fw-bold font-retro">Latest Sightings</h2>
            {{-- Mensaje de éxito de borrado --}}
             @if (session('success') && Str::contains(session('success'), 'deleted'))
                 <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                     <i class="bi bi-check-circle-fill me-2"></i>
                     {{ session('success') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>
             @endif
            @if($images->isNotEmpty())
                <div class="row g-4 justify-content-center">
                    @foreach ($images as $image)
                         <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card polaroid-effect shadow-sm position-relative">
                                {{-- Delete Button (Sin cambios) --}}
                                @auth
                                    @if (Auth::user()->name === 'netraular')
                                        <form method="POST" action="{{ route('images.destroy', $image->id) }}" class="position-absolute top-0 end-0 p-1" style="z-index: 1;" onsubmit="return confirm('Are you absolutely sure you want to delete this Biene sighting?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm p-1" title="Delete Image">
                                                <i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth

                                <a href="{{ Storage::url($image->path) }}" data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                                    <img src="{{ Storage::url($image->path) }}" class="card-img-top gallery-img" alt="Biene Sighting {{ $image->id }}">
                                </a>
                                <div class="card-body text-center p-2">
                                    <small class="text-muted">Seen {{ $image->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                         <!-- Modal (Sin cambios) -->
                        <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $image->id }}" aria-hidden="true">
                             <div class="modal-dialog modal-lg modal-dialog-centered">
                                 <div class="modal-content">
                                     <div class="modal-header">
                                        <h5 class="modal-title font-retro" id="imageModalLabel{{ $image->id }}">Sighting #{{ $image->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ Storage::url($image->path) }}" class="img-fluid rounded" alt="Biene Sighting {{ $image->id }} (Large)">
                                        <p class="mt-3"><small class="text-muted">Seen {{ $image->created_at->diffForHumans() }}</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginación (Sin cambios) --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $images->links() }}
                </div>

            @else
                {{-- Mensaje si no hay imágenes, ajustado si las subidas están cerradas --}}
                @if ($submissionsOpen)
                     <p class="text-center text-muted fs-5 mt-4">No Biene sightings reported yet... <br> The hunt is on! Be the first!</p>
                @else
                     <p class="text-center text-muted fs-5 mt-4">No Biene sightings were submitted during the event, or they have been cleared.</p>
                @endif
            @endif
        </div>
    </section>

</div>
@endsection

@push('scripts')
{{-- Incluir la biblioteca browser-image-compression (Sin cambios) --}}
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

{{-- === SCRIPT DE SUBIDA AHORA ES CONDICIONAL === --}}
<script>
    // Pasa el estado de las subidas desde Blade/PHP a JavaScript
    const submissionsAreOpen = {{ Js::from($submissionsOpen) }}; // Usa la facade Js para seguridad

    // Solo ejecuta el script de subida si las subidas están abiertas
    if (submissionsAreOpen) {

        const fileInput = document.getElementById('image');
        const fileNameDisplay = document.getElementById('fileName');
        const uploadForm = document.querySelector('.upload-form');
        const uploadButtonLabel = document.querySelector('label[for="image"]');
        const submitButton = document.querySelector('button[type="submit"]');

        // --- Configuraciones (sin cambios) ---
        const MAX_ORIGINAL_SIZE_MB = 5;
        const MAX_ORIGINAL_SIZE_BYTES = MAX_ORIGINAL_SIZE_MB * 1024 * 1024;
        const COMPRESSION_OPTIONS = {
            maxSizeMB: 2,
            maxWidthOrHeight: 1920,
            useWebWorker: true,
        };
        // ---------------------

        let fileToUpload = null;

        // Función para actualizar la UI del botón/label (sin cambios en su lógica interna)
        function updateUploadUI(state, message = '', details = '') {
            submitButton.disabled = true;
            submitButton.classList.remove('pulse-animation');
            fileNameDisplay.textContent = details;
            fileNameDisplay.style.color = '#6c757d'; // Reset color (Bootstrap default text-muted)

            switch (state) {
                case 'initial':
                    uploadButtonLabel.style.background = 'linear-gradient(45deg, #ec4899, #f97316)';
                    uploadButtonLabel.innerHTML = '<i class="bi bi-camera-fill me-2"></i> Upload Biene Photo!';
                    submitButton.textContent = 'Confirm Upload';
                    fileToUpload = null;
                    break;
                case 'compressing':
                    uploadButtonLabel.style.background = 'linear-gradient(45deg, #60a5fa, #3b82f6)';
                    uploadButtonLabel.innerHTML = '<i class="bi bi-arrow-down-up me-2"></i> Compressing...';
                    submitButton.textContent = 'Processing...';
                    break;
                case 'ready':
                    uploadButtonLabel.style.background = 'linear-gradient(45deg, #10b981, #22c55e)';
                    uploadButtonLabel.innerHTML = '<i class="bi bi-check-lg me-2"></i> File Ready!';
                    submitButton.textContent = 'Confirm Upload';
                    submitButton.disabled = false;
                    submitButton.classList.add('pulse-animation');
                    break;
                case 'error':
                    uploadButtonLabel.style.background = 'linear-gradient(45deg, #f43f5e, #ef4444)';
                    uploadButtonLabel.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Error';
                    submitButton.textContent = 'Upload Failed';
                    fileNameDisplay.textContent = message || 'An error occurred.';
                    fileNameDisplay.style.color = '#dc3545'; // Bootstrap danger color
                    fileToUpload = null;
                    break;
                 case 'uploading': // Nuevo estado para claridad durante la subida
                    uploadButtonLabel.style.background = 'linear-gradient(45deg, #fdba74, #f59e0b)'; // Orange gradient
                    uploadButtonLabel.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i> Sending...';
                    submitButton.textContent = 'Uploading...';
                    submitButton.disabled = true;
                    submitButton.classList.remove('pulse-animation');
                    break;
            }
        }


        // Asegurarse de que todos los elementos existen antes de añadir listeners
        if (fileInput && fileNameDisplay && uploadForm && uploadButtonLabel && submitButton) {

            // Inicializar UI
            updateUploadUI('initial');

            fileInput.addEventListener('change', async function() {
                if (this.files && this.files.length > 0) {
                    const originalFile = this.files[0];
                    const originalFileName = originalFile.name;
                    const originalFileSizeMB = (originalFile.size / 1024 / 1024).toFixed(2);

                    let fileDetails = `Selected: ${originalFileName} (${originalFileSizeMB} MB)`;

                    if (originalFile.size > MAX_ORIGINAL_SIZE_BYTES) {
                        fileDetails += ` - Size is large, attempting compression...`;
                        updateUploadUI('compressing', '', fileDetails);
                        console.log(`Original size ${originalFileSizeMB}MB exceeds limit of ${MAX_ORIGINAL_SIZE_MB}MB. Compressing...`);

                        try {
                            const compressedFile = await imageCompression(originalFile, COMPRESSION_OPTIONS);
                            const compressedFileName = originalFileName;
                            const compressedFileSizeMB = (compressedFile.size / 1024 / 1024).toFixed(2);

                            console.log(`Compression successful. New size: ${compressedFileSizeMB}MB`);
                            fileToUpload = new File([compressedFile], compressedFileName, { type: compressedFile.type });
                            fileDetails = `Ready: ${compressedFileName} (Compressed to ${compressedFileSizeMB} MB)`;
                            updateUploadUI('ready', '', fileDetails);

                        } catch (error) {
                            console.error('Error during image compression:', error);
                            updateUploadUI('error', 'Compression failed. Please try a smaller image or different format.', `File: ${originalFileName}`);
                            fileToUpload = null;
                        }

                    } else {
                        console.log(`Original size ${originalFileSizeMB}MB is within limits.`);
                        fileToUpload = originalFile;
                        updateUploadUI('ready', '', fileDetails);
                    }

                } else {
                    updateUploadUI('initial'); // Reset si se cancela la selección
                }
            });

            // Interceptar el envío del formulario
            uploadForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                if (!fileToUpload) {
                    updateUploadUI('error', 'No valid file selected or compression failed.');
                    // Añadir un pequeño aviso visual extra brevemente
                    if(fileNameDisplay) {
                        fileNameDisplay.style.color = '#dc3545';
                        setTimeout(() => { if(fileNameDisplay) fileNameDisplay.style.color = '#6c757d'; }, 3000);
                    }
                    return;
                }

                // Actualizar UI a estado de subida
                updateUploadUI('uploading');

                const formData = new FormData();
                formData.append('image', fileToUpload, fileToUpload.name);
                const csrfTokenEl = document.querySelector('meta[name="csrf-token"]'); // Buscar el meta tag CSRF
                const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : null;

                if (!csrfToken) {
                     console.error('CSRF token not found!');
                     updateUploadUI('error', 'Security token missing. Cannot upload.');
                     return;
                }


                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json' // Aseguramos que pedimos JSON
                        },
                        body: formData
                    });

                    const responseData = await response.json(); // Intenta parsear JSON siempre

                    if (response.ok) {
                        console.log('Upload successful:', responseData);
                        // No redirigimos aquí para evitar perder mensajes de error/éxito de borrado
                        // Mostramos mensaje éxito y reseteamos form visualmente
                        // El controller NO redirige en caso de JSON, solo devuelve éxito
                         updateUploadUI('initial'); // Reset UI
                         // Mostrar mensaje de éxito de Bootstrap dinámicamente
                         const successAlertHtml = `
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                ${responseData.success || 'Biene sighting uploaded!'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                         uploadForm.insertAdjacentHTML('beforebegin', successAlertHtml);
                         // Opcional: recargar la página después de un breve retraso para ver la nueva imagen en la galería
                         setTimeout(() => {
                             const currentUrl = new URL(window.location.href);
                             currentUrl.searchParams.delete('page'); // Ir a la página 1
                             window.location.href = currentUrl.toString();
                         }, 2000); // Recarga después de 2 segundos

                    } else {
                         // Intenta obtener el mensaje de error del JSON, o usa uno genérico
                        let errorMessage = responseData.message || `Upload failed. Status: ${response.status}`;
                        if (responseData.errors && responseData.errors.image) {
                             errorMessage = responseData.errors.image[0]; // Más específico si es validación
                        } else if (responseData.errors && Object.keys(responseData.errors).length > 0) {
                             const firstErrorKey = Object.keys(responseData.errors)[0];
                             errorMessage = responseData.errors[firstErrorKey][0];
                        }
                        console.error('Upload failed:', errorMessage, 'Status:', response.status, 'Response Data:', responseData);
                        updateUploadUI('error', errorMessage, `File: ${fileToUpload.name}`);
                    }
                } catch (error) {
                     console.error('Network error or issue processing upload response:', error);
                     updateUploadUI('error', 'Network error or server issue during upload.', `File: ${fileToUpload.name}`);
                }
            });

            // Añadir Keyframes para pulse (sin cambios)
            const styleSheet = document.styleSheets[0];
            if (styleSheet) {
                 try {
                     let pulseExists = false;
                     let pulseAnimationExists = false;
                     if (styleSheet.cssRules) {
                         for (let i = 0; i < styleSheet.cssRules.length; i++) {
                             if (styleSheet.cssRules[i].type === CSSRule.KEYFRAMES_RULE && styleSheet.cssRules[i].name === 'pulse') pulseExists = true;
                             if (styleSheet.cssRules[i].type === CSSRule.STYLE_RULE && styleSheet.cssRules[i].selectorText === '.pulse-animation') pulseAnimationExists = true;
                         }
                     }
                     if (!pulseExists) {
                        styleSheet.insertRule(`@keyframes pulse { 0% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); } 50% { transform: scale(1.03); box-shadow: 0 0 15px rgba(250, 204, 21, 0.8); } 100% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); } }`, styleSheet.cssRules ? styleSheet.cssRules.length : 0);
                     }
                     if (!pulseAnimationExists) {
                        styleSheet.insertRule(`.pulse-animation { animation: pulse 1.5s infinite ease-in-out; }`, styleSheet.cssRules ? styleSheet.cssRules.length : 0);
                     }
                 } catch (e) {
                     console.warn("Could not insert pulse animation rule:", e);
                 }
            }

        } else {
            console.error("One or more required elements for the upload script were not found (upload form might be disabled).");
        }

    } else {
         // Esto se ejecuta si submissionsAreOpen es false
         console.log("Submissions are closed. Upload functionality disabled.");
         // Podrías añadir aquí código para asegurarte que los botones estén visualmente deshabilitados si es necesario,
         const uploadLabel = document.querySelector('label[for="image"]');
         const submitBtn = document.querySelector('.upload-form button[type="submit"]'); // Puede que no exista si está cerrado
         if(uploadLabel && uploadLabel.classList.contains('upload-button') && !uploadLabel.classList.contains('disabled')) {
             uploadLabel.classList.add('disabled');
             uploadLabel.style.background = '#6c757d'; // Estilo grisáceo
             uploadLabel.innerHTML = '<i class="bi bi-camera-fill me-2"></i> Submissions Closed';
         }
         if(submitBtn && !submitBtn.disabled) {
             submitBtn.disabled = true;
             submitBtn.textContent = 'Upload Closed';
         }
    }

</script>

{{-- NUEVO SCRIPT: Ocultar/Mostrar overlay con modales (Sin cambios) --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pageOverlay = document.getElementById('page-overlay');
        const modals = document.querySelectorAll('.modal');

        if (pageOverlay && modals.length > 0) {
            modals.forEach(modal => {
                modal.addEventListener('show.bs.modal', function () {
                    if (pageOverlay) {
                        pageOverlay.style.display = 'none';
                    }
                });
                modal.addEventListener('hidden.bs.modal', function () {
                    setTimeout(() => {
                        if (pageOverlay && !document.body.classList.contains('modal-open')) {
                            pageOverlay.style.display = 'block';
                        }
                    }, 50);
                });
            });
        } else {
             if (!pageOverlay) console.error("Element #page-overlay not found for modal interaction script.");
        }
    });
</script>
@endpush