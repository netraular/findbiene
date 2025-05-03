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
<script>
    // Script to show file name & trigger submit button style changes maybe
    const fileInput = document.getElementById('image');
    const fileNameDisplay = document.getElementById('fileName');
    const uploadButtonLabel = document.querySelector('label[for="image"]'); // The label acting as button
    const submitButton = document.querySelector('button[type="submit"]'); // Actual submit button

    if (fileInput && fileNameDisplay && uploadButtonLabel && submitButton) {
        // Initially hide submit button maybe? Or just disable?
        // submitButton.style.display = 'none'; // Example: Hide initially

        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = `Selected: ${this.files[0].name}`;
                uploadButtonLabel.style.background = 'linear-gradient(45deg, #10b981, #22c55e)'; // Change to green gradient
                uploadButtonLabel.innerHTML = '<i class="bi bi-check-lg me-2"></i> File Ready!';
                // submitButton.style.display = 'inline-block'; // Show submit button
                submitButton.disabled = false; // Enable submit button if it was disabled
                submitButton.classList.add('pulse-animation'); // Add pulsing effect maybe? Add pulse CSS keyframes if you do this.

            } else {
                fileNameDisplay.textContent = '';
                 uploadButtonLabel.style.background = 'linear-gradient(45deg, #ec4899, #f97316)'; // Revert gradient
                 uploadButtonLabel.innerHTML = '<i class="bi bi-camera-fill me-2"></i> Upload Biene Photo!';
                 // submitButton.style.display = 'none'; // Hide again
                 submitButton.disabled = true; // Disable submit button
                 submitButton.classList.remove('pulse-animation');
            }
        });

        // Optionally disable submit button initially until a file is selected
        submitButton.disabled = true;

        // Simple pulse animation class to add via JS (add @keyframes pulse in CSS)
        const styleSheet = document.styleSheets[0]; // Assuming your custom styles are first
        if (styleSheet) {
            try {
                styleSheet.insertRule(`
                    @keyframes pulse {
                        0% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); }
                        50% { transform: scale(1.03); box-shadow: 0 0 15px rgba(250, 204, 21, 0.8); }
                        100% { transform: scale(1); box-shadow: 0 0 8px rgba(250, 204, 21, 0.5); }
                    }
                `, styleSheet.cssRules.length);
                 styleSheet.insertRule(`.pulse-animation { animation: pulse 1.5s infinite ease-in-out; }`, styleSheet.cssRules.length);
             } catch (e) {
                 console.warn("Could not insert pulse animation rule:", e);
             }
        }
    }


</script>
@endpush