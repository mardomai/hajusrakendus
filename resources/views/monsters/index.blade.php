@extends('layouts.app')

@section('title', 'Monsters')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Api view</h1>
    <div class="row g-4" id="monsters-container">
        <!-- Monsters will be loaded here -->
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('https://hajusrakendused.tak22parnoja.itmajakas.ee/current/public/index.php/api/monsters')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('monsters-container');
            data.forEach(monster => {
                const card = `
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">${monster.title}</h5>
                                <p class="card-text">${monster.description}</p>
                                ${monster.habitat ? `<p class="card-text"><small class="text-muted">Habitat: ${monster.habitat}</small></p>` : ''}
                                <div class="mt-3">
                                    <h6 class="mb-2">Behavior:</h6>
                                    <p class="card-text">${monster.behavior}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', card);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('monsters-container').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Failed to load monsters data. Please try again later.
                    </div>
                </div>
            `;
        });
});
</script>
@endpush
@endsection 