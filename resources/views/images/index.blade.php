@extends('layouts.app')

@section('content')
<div class="container-fluid p-0"> {{-- Full width needed for layout --}}

    {{-- Hero Section --}}
    <section class="hero-section text-center text-white pt-5 pb-4"> {{-- Adjusted padding --}}
        <div class="container">
             {{-- Use HackUPC Logo (inverted) --}}
             <img src="/images/hackupcLogoBlack.svg" alt="Hack UPC Logo" class="hack-logo">

            {{-- Game Title - Use Retro Font --}}
            <h1 class="display-5 fw-bold font-retro mb-3">Biene Hunt!</h1>
            <p class="lead fw-normal mb-4">Hack UPC 2025 Edition</p>
            <h2 class="h4 fw-light">Spotted Biene? Upload your sighting!</h2>
        </div>
    </section>

    {{-- Upload Section --}}
    <section class="py-5"> {{-- Remove upload-section class, apply styles directly or via parent --}}
        <div class="container">
            <div class="row justify-content-center">
                 {{-- Apply upload-section styling to this column --}}
                <div class="col-md-8 col-lg-7 upload-section">
                    <div class="text-center">

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Warning Message --}}
                        <div class="alert alert-warning mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Psst!</strong> Keep it mysterious! Try not to reveal the *whole* spot in your photo. Let others enjoy the hunt! 😉
                        </div>

                        <form method="POST" action="{{ route('images.upload') }}" enctype="multipart/form-data" class="upload-form">
                            @csrf

                            <input type="file" class="form-control" id="image" name="image" required accept="image/png, image/jpeg, image/gif" style="display: none;">

                            <label for="image" class="btn btn-lg upload-button w-100 mb-2 font-retro"> {{-- Added retro font --}}
                                <i class="bi bi-camera-fill me-2"></i>
                                Upload Biene Photo!
                            </label>
                            <small id="fileName" class="d-block text-muted mb-3"></small>

                            @error('image')
                                <div class="invalid-feedback d-block mt-2">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror

                            {{-- Added a visible submit button for clarity --}}
                            <button type="submit" class="btn btn-warning font-retro mt-3">Confirm Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Gallery Section --}}
    <section class="gallery-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 display-6 fw-bold font-retro">Latest Sightings</h2> {{-- Retro font --}}
            @if($images->count())
                <div class="row g-4 justify-content-center">
                    @foreach ($images as $image)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card polaroid-effect shadow-sm">
                                <a href="{{ Storage::url($image->path) }}" data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                                    <img src="{{ Storage::url($image->path) }}" class="card-img-top gallery-img" alt="Biene Sighting {{ $loop->iteration }}">
                                </a>
                                <div class="card-body text-center p-2">
                                    <small class="text-muted">Seen {{ $image->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                         <!-- Modal -->
                        <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $image->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content"> {{-- Styles applied via layout CSS --}}
                                     <div class="modal-header">
                                        <h5 class="modal-title font-retro" id="imageModalLabel{{ $image->id }}">Sighting #{{ $loop->iteration }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ Storage::url($image->path) }}" class="img-fluid rounded" alt="Biene Sighting {{ $loop->iteration }} (Large)">
                                        <p class="mt-3"><small class="text-muted">Seen {{ $image->created_at->diffForHumans() }}</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted fs-5 mt-4">No Biene sightings reported yet... <br> The hunt is on! Be the first!</p>
            @endif
        </div>
    </section>

</div>
@endsection

@push('scripts')
{{-- Incluir la biblioteca browser-image-compression desde un CDN --}}
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

<script>
    const fileInput = document.getElementById('image');
    const fileNameDisplay = document.getElementById('fileName');
    const uploadForm = document.querySelector('.upload-form');
    const uploadButtonLabel = document.querySelector('label[for="image"]'); // Label original
    const submitButton = document.querySelector('button[type="submit"]'); // Botón de confirmar

    // --- Configuraciones ---
    const MAX_ORIGINAL_SIZE_MB = 5; // Umbral para mostrar aviso y comprimir (en MB)
    const MAX_ORIGINAL_SIZE_BYTES = MAX_ORIGINAL_SIZE_MB * 1024 * 1024;
    const COMPRESSION_OPTIONS = {
        maxSizeMB: 2,          // Tamaño máximo objetivo después de comprimir (en MB)
        maxWidthOrHeight: 1920, // Redimensionar si es más grande que 1920px en ancho o alto
        useWebWorker: true,    // Usar Web Worker para no bloquear la interfaz
        // initialQuality: 0.7 // Puedes ajustar la calidad inicial (0-1) si es necesario
    };
    // ---------------------

    let fileToUpload = null; // Variable para guardar el archivo final (original o comprimido)

    // Función para actualizar la UI del botón/label
    function updateUploadUI(state, message = '', details = '') {
        submitButton.disabled = true; // Deshabilitar por defecto
        submitButton.classList.remove('pulse-animation');
        fileNameDisplay.textContent = details;

        switch (state) {
            case 'initial':
                uploadButtonLabel.style.background = 'linear-gradient(45deg, #ec4899, #f97316)'; // Pink to Orange
                uploadButtonLabel.innerHTML = '<i class="bi bi-camera-fill me-2"></i> Upload Biene Photo!';
                submitButton.textContent = 'Confirm Upload';
                fileToUpload = null;
                break;
            case 'compressing':
                uploadButtonLabel.style.background = 'linear-gradient(45deg, #60a5fa, #3b82f6)'; // Blue gradient
                uploadButtonLabel.innerHTML = '<i class="bi bi-arrow-down-up me-2"></i> Compressing...';
                submitButton.textContent = 'Processing...';
                break;
            case 'ready':
                uploadButtonLabel.style.background = 'linear-gradient(45deg, #10b981, #22c55e)'; // Green gradient
                uploadButtonLabel.innerHTML = '<i class="bi bi-check-lg me-2"></i> File Ready!';
                submitButton.textContent = 'Confirm Upload';
                submitButton.disabled = false;
                submitButton.classList.add('pulse-animation');
                break;
            case 'error':
                uploadButtonLabel.style.background = 'linear-gradient(45deg, #f43f5e, #ef4444)'; // Red gradient
                uploadButtonLabel.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Error';
                submitButton.textContent = 'Upload Failed';
                fileNameDisplay.textContent = message || 'An error occurred.';
                 fileNameDisplay.style.color = '#fca5a5'; // Light red for error
                fileToUpload = null;
                break;
        }
    }


    if (fileInput && fileNameDisplay && uploadForm && uploadButtonLabel && submitButton) {

        // Inicializar UI
        updateUploadUI('initial');

        fileInput.addEventListener('change', async function() {
            if (this.files && this.files.length > 0) {
                const originalFile = this.files[0];
                const originalFileName = originalFile.name;
                const originalFileSizeMB = (originalFile.size / 1024 / 1024).toFixed(2);

                let fileDetails = `Selected: ${originalFileName} (${originalFileSizeMB} MB)`;

                // Verificar tamaño original
                if (originalFile.size > MAX_ORIGINAL_SIZE_BYTES) {
                    fileDetails += ` - Size is large, attempting compression...`;
                    updateUploadUI('compressing', '', fileDetails);

                    console.log(`Original size ${originalFileSizeMB}MB exceeds limit of ${MAX_ORIGINAL_SIZE_MB}MB. Compressing...`);

                    try {
                        const compressedFile = await imageCompression(originalFile, COMPRESSION_OPTIONS);
                        const compressedFileName = originalFileName; // Mantener nombre original o modificar si se quiere
                        const compressedFileSizeMB = (compressedFile.size / 1024 / 1024).toFixed(2);

                        console.log(`Compression successful. New size: ${compressedFileSizeMB}MB`);
                        fileToUpload = new File([compressedFile], compressedFileName, { type: compressedFile.type }); // Crear objeto File
                        fileDetails = `Ready: ${compressedFileName} (Compressed to ${compressedFileSizeMB} MB)`;
                        updateUploadUI('ready', '', fileDetails);

                    } catch (error) {
                        console.error('Error during image compression:', error);
                        updateUploadUI('error', 'Compression failed. Please try a smaller image or upload anyway if allowed.', `File: ${originalFileName}`);
                        // Opcional: permitir subir el original si falla la compresión?
                        // fileToUpload = originalFile;
                        // updateUploadUI('ready', '', `Compression failed. Ready to upload original: ${originalFileName} (${originalFileSizeMB} MB)`);
                        fileToUpload = null; // No subir si falla la compresión (más seguro)
                    }

                } else {
                    // El archivo no es demasiado grande, usar el original
                    console.log(`Original size ${originalFileSizeMB}MB is within limits.`);
                    fileToUpload = originalFile;
                    updateUploadUI('ready', '', fileDetails);
                }

            } else {
                updateUploadUI('initial');
            }
        });

        // Interceptar el envío del formulario
        uploadForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevenir el envío normal

            if (!fileToUpload) {
                updateUploadUI('error', 'No valid file selected or compression failed.');
                return;
            }

            // Crear FormData
            const formData = new FormData();
            formData.append('image', fileToUpload, fileToUpload.name); // Añadir el archivo (original o comprimido)

            // Añadir token CSRF (leído desde la meta tag)
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Deshabilitar botón y mostrar estado de envío
            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';
            submitButton.classList.remove('pulse-animation');
            uploadButtonLabel.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i> Sending...';


            try {
                const response = await fetch(this.action, { // this.action es la URL del formulario
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json' // Esperar respuesta JSON (Laravel suele enviarla en redirects con error)
                    },
                    body: formData
                });

                if (response.ok) {
                    // ¡Éxito! Laravel normalmente redirige. La página se recargará
                    // o mostrará el mensaje flash 'success' en la recarga.
                    // Forzar recarga para ver el mensaje flash y la nueva imagen en la galería
                     console.log('Upload successful, reloading page...');
                     window.location.reload();

                } else {
                    // Hubo un error (validación, servidor, etc.)
                    let errorMessage = 'Upload failed. Server responded with an error.';
                    try {
                         // Intentar parsear errores de validación JSON de Laravel
                        const errorData = await response.json();
                        if (errorData.errors && errorData.errors.image) {
                            errorMessage = errorData.errors.image[0];
                        } else if (errorData.message) {
                            errorMessage = errorData.message; // Otro tipo de error JSON
                        }
                    } catch (e) {
                        // No era JSON o hubo otro error al parsear
                        console.error('Could not parse error response:', e);
                         errorMessage = `Upload failed. Status: ${response.status} ${response.statusText}`;
                    }
                     console.error('Upload failed:', errorMessage);
                    updateUploadUI('error', errorMessage, `File: ${fileToUpload.name}`);
                }

            } catch (error) {
                 console.error('Network error or other issue during fetch:', error);
                 updateUploadUI('error', 'Network error during upload. Please check connection.', `File: ${fileToUpload.name}`);
            }
        });

        // Añadir Keyframes para pulse (si no están en el CSS global)
        const styleSheet = document.styleSheets[0];
        if (styleSheet) {
            try {
                 // Comprobar si la regla ya existe podría ser más robusto
                 // Pero por simplicidad, intentamos insertarlas
                 let pulseExists = false;
                 let pulseAnimationExists = false;
                 for (let i = 0; i < styleSheet.cssRules.length; i++) {
                     if (styleSheet.cssRules[i].name === 'pulse') pulseExists = true;
                     if (styleSheet.cssRules[i].selectorText === '.pulse-animation') pulseAnimationExists = true;
                 }

                 if (!pulseExists) {
                    styleSheet.insertRule(`
                        @keyframes pulse {
                            0% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); }
                            50% { transform: scale(1.03); box-shadow: 0 0 15px rgba(250, 204, 21, 0.8); }
                            100% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); }
                        }
                    `, styleSheet.cssRules.length);
                 }
                 if (!pulseAnimationExists) {
                    styleSheet.insertRule(`.pulse-animation { animation: pulse 1.5s infinite ease-in-out; }`, styleSheet.cssRules.length);
                 }
             } catch (e) {
                 console.warn("Could not insert pulse animation rule (might already exist):", e);
             }
        }

    } else {
        console.error("One or more required elements for the upload script were not found.");
    }

</script>
@endpush