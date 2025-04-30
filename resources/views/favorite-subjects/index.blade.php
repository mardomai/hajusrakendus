@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col">
            <h1>My Favorite Subjects</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('favorite-subjects.create') }}" class="btn btn-dark">Add New Subject</a>
        </div>
    </div>

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
                            <span class="badge bg-dark">{{ $subject->category }}</span>
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
                            <a href="{{ route('favorite-subjects.edit', $subject) }}" class="btn btn-dark btn-sm">Edit</a>
                            <form action="{{ route('favorite-subjects.destroy', $subject) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subject?')">Delete</button>
                            </form>
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
@endsection 