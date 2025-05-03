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
                            {{-- Added position:relative to contain the absolute delete button --}}
                            <div class="card polaroid-effect shadow-sm position-relative">

                                {{-- Delete Button - Visible only for user 'netraular' --}}
                                @auth
                                    @if (Auth::user()->name === 'netraular')
                                        <form method="POST" action="{{ route('images.destroy', $image->id) }}" class="position-absolute top-0 end-0 p-1" style="z-index: 1;" onsubmit="return confirm('Are you absolutely sure you want to delete this Biene sighting?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm p-1" title="Delete Image">
                                                <i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i> {{-- Smaller icon --}}
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                                {{-- End Delete Button --}}

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

{{-- SCRIPT ORIGINAL: Subida y compresión de imágenes --}}
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
    };
    // ---------------------

    let fileToUpload = null; // Variable para guardar el archivo final (original o comprimido)

    // Función para actualizar la UI del botón/label
    function updateUploadUI(state, message = '', details = '') {
        submitButton.disabled = true; // Deshabilitar por defecto
        submitButton.classList.remove('pulse-animation');
        fileNameDisplay.textContent = details;
        fileNameDisplay.style.color = '#9ca3af'; // Reset color

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
                        const compressedFileName = originalFileName;
                        const compressedFileSizeMB = (compressedFile.size / 1024 / 1024).toFixed(2);

                        console.log(`Compression successful. New size: ${compressedFileSizeMB}MB`);
                        fileToUpload = new File([compressedFile], compressedFileName, { type: compressedFile.type });
                        fileDetails = `Ready: ${compressedFileName} (Compressed to ${compressedFileSizeMB} MB)`;
                        updateUploadUI('ready', '', fileDetails);

                    } catch (error) {
                        console.error('Error during image compression:', error);
                        updateUploadUI('error', 'Compression failed. Please try a smaller image.', `File: ${originalFileName}`);
                        fileToUpload = null;
                    }

                } else {
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

            const formData = new FormData();
            formData.append('image', fileToUpload, fileToUpload.name);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';
            submitButton.classList.remove('pulse-animation');
            uploadButtonLabel.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i> Sending...';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                     console.log('Upload successful, reloading page...');
                     window.location.reload();
                } else {
                    let errorMessage = 'Upload failed. Server responded with an error.';
                    try {
                        const errorData = await response.json();
                        if (errorData.errors && errorData.errors.image) {
                            errorMessage = errorData.errors.image[0];
                        } else if (errorData.message) {
                            errorMessage = errorData.message;
                        }
                    } catch (e) {
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
                 let pulseExists = false;
                 let pulseAnimationExists = false;
                 // Avoid errors if cssRules is null or inaccessible (e.g., cross-origin stylesheets)
                 if (styleSheet.cssRules) {
                     for (let i = 0; i < styleSheet.cssRules.length; i++) {
                         if (styleSheet.cssRules[i].type === CSSRule.KEYFRAMES_RULE && styleSheet.cssRules[i].name === 'pulse') pulseExists = true;
                         if (styleSheet.cssRules[i].type === CSSRule.STYLE_RULE && styleSheet.cssRules[i].selectorText === '.pulse-animation') pulseAnimationExists = true;
                     }
                 }

                 if (!pulseExists) {
                    styleSheet.insertRule(`
                        @keyframes pulse {
                            0% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); }
                            50% { transform: scale(1.03); box-shadow: 0 0 15px rgba(250, 204, 21, 0.8); }
                            100% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); }
                        }
                    `, styleSheet.cssRules ? styleSheet.cssRules.length : 0);
                 }
                 if (!pulseAnimationExists) {
                    styleSheet.insertRule(`.pulse-animation { animation: pulse 1.5s infinite ease-in-out; }`, styleSheet.cssRules ? styleSheet.cssRules.length : 0);
                 }
             } catch (e) {
                 console.warn("Could not insert pulse animation rule (might already exist or other issue):", e);
             }
        }

    } else {
        console.error("One or more required elements for the upload script were not found.");
    }
</script>


{{-- NUEVO SCRIPT: Ocultar/Mostrar overlay con modales --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pageOverlay = document.getElementById('page-overlay');
        const modals = document.querySelectorAll('.modal');

        if (pageOverlay && modals.length > 0) {
            modals.forEach(modal => {
                // Cuando un modal empieza a mostrarse
                modal.addEventListener('show.bs.modal', function () {
                    if (pageOverlay) {
                        pageOverlay.style.display = 'none'; // Oculta el overlay
                        // console.log('Modal shown, hiding overlay.');
                    }
                });

                // Cuando un modal termina de ocultarse
                modal.addEventListener('hidden.bs.modal', function () {
                     // Pequeño retraso para dar tiempo a Bootstrap a quitar la clase 'modal-open' del body si es el último modal
                    setTimeout(() => {
                        // Verifica si la clase 'modal-open' ya NO está en el body
                        if (pageOverlay && !document.body.classList.contains('modal-open')) {
                            pageOverlay.style.display = 'block'; // Muestra el overlay SOLO si no hay otros modales abiertos
                            // console.log('Last modal hidden, showing overlay.');
                        } else {
                            // console.log('Modal hidden, but another might be open. Overlay remains hidden.');
                        }
                    }, 50); // 50ms de retraso
                });
            });
        } else {
             if (!pageOverlay) console.error("Element #page-overlay not found for modal interaction script.");
             if (modals.length === 0) console.warn("No modal elements found for interaction script. This might be okay if there are no modals on this specific page.");
        }
    });
</script>
@endpush