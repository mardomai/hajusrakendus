@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Map Markers</span>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addMarkerModal">
                        Add Marker
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div id="map" style="height: 500px;" data-markers="{{ json_encode($markers) }}"></div>

                    <div class="mt-4">
                        <h4>Marker List</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Coordinates</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($markers as $marker)
                                    <tr>
                                        <td>{{ $marker->name }}</td>
                                        <td>{{ $marker->description }}</td>
                                        <td>{{ $marker->latitude }}, {{ $marker->longitude }}</td>
                                        <td>
                                            <a href="{{ route('markers.edit', $marker) }}" class="btn btn-sm btn-dark">Edit</a>
                                            <form action="{{ route('markers.destroy', $marker) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Marker Modal -->
<div class="modal fade" id="addMarkerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Marker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('markers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
                    </div>
                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">Save Marker</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([58.3780, 26.7290], 13); // Centered on Tartu

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add existing markers
    const mapElement = document.getElementById('map');
    const markers = JSON.parse(mapElement.dataset.markers);
    markers.forEach(marker => {
        L.marker([marker.latitude, marker.longitude])
            .bindPopup(`<b>${marker.name}</b><br>${marker.description || ''}`)
            .addTo(map);
    });

    // Click on map to add marker
    map.on('click', function(e) {
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
        new bootstrap.Modal(document.getElementById('addMarkerModal')).show();
    });
});
</script>
@endpush 