@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10"> {{-- Ancho un poco mayor para la galería --}}

            <div class="text-center mb-5">
                <img src="https://via.placeholder.com/100?text=Biene" alt="Logo Biene" class="mb-3 rounded-circle"> {{-- Reemplaza con un logo real si tienes --}}
                <h1>Hack UPC 2025 - ¿Dónde está Biene?</h1>
                <p class="lead">¡El reto de encontrar a nuestra mascota ha comenzado!</p>
                <h2>¿Ya has encontrado a Biene?</h2>
                <p>¡Sube tu foto aquí y comparte con todos dónde la has visto!</p>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Formulario de subida (solo para usuarios logueados) --}}
            @auth
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">{{ __('Sube tu foto con Biene') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('images.upload') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="image" class="form-label">{{ __('Selecciona la imagen') }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" required>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('¿Dónde la encontraste? (Opcional)') }}</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Ej: Cerca del stand de MLH">
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <button type="submit" class="btn btn-primary">
                                {{ __('Subir Foto') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            {{-- Mensaje para usuarios no logueados --}}
            @guest
                <div class="alert alert-info text-center">
                    Para poder subir tu foto con Biene, por favor <a href="{{ route('login') }}">inicia sesión</a>.
                </div>
            @endguest

            <hr class="my-4">

            {{-- Galería de imágenes --}}
            <h2 class="text-center mb-4">Últimos avistamientos de Biene</h2>
            @if($images->count())
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @foreach ($images as $image)
                        <div class="col">
                            <div class="card shadow-sm">
                                {{-- Usamos Storage::url para obtener la URL pública correcta --}}
                                <img src="{{ Storage::url($image->path) }}" alt="Biene encontrada {{ $image->description ? 'en '.$image->description : '' }}" class="gallery-img">
                                {{-- <div class="card-body">
                                    @if($image->description)
                                        <p class="card-text">{{ $image->description }}</p>
                                    @endif
                                    <small class="text-muted">Subida {{ $image->created_at->diffForHumans() }}</small>
                                    {{-- Podrías añadir el nombre del usuario si quieres: by {{ $image->user->name }} --}}
                                {{--</div> --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted">Aún no se ha subido ninguna foto de Biene. ¡Sé el primero!</p>
            @endif

        </div>
    </div>
</div>
@endsection