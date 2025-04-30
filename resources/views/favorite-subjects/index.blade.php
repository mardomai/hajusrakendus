@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1>My Favorite Subjects</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('favorite-subjects.create') }}" class="btn btn-primary">Add New Subject</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @forelse($subjects as $subject)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($subject->image)
                        <img src="{{ $subject->image }}" class="card-img-top" alt="{{ $subject->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $subject->title }}</h5>
                        <p class="card-text">{{ Str::limit($subject->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ $subject->category }}</span>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $subject->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group w-100">
                            <button class="btn btn-primary btn-sm edit-subject" data-id="{{ $subject->id }}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-subject" data-id="{{ $subject->id }}">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <div class="alert alert-info">
                    No favorite subjects added yet.
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $subjects->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete subject
    document.querySelectorAll('.delete-subject').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this subject?')) {
                const id = this.dataset.id;
                fetch(`/api/favorite-subjects/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        });
    });

    // Edit subject (redirect to edit page)
    document.querySelectorAll('.edit-subject').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            window.location.href = `/favorite-subjects/${id}/edit`;
        });
    });
});
</script>
@endpush
@endsection 