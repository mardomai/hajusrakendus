@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Blog Posts</h4>
                    <div class="col text-end">
                        <a href="{{ route('blog.create') }}" class="btn btn-dark">Create New Post</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($posts->isEmpty())
                        <p class="text-center">No blog posts yet.</p>
                    @else
                        <div class="row">
                            @foreach($posts as $post)
                            <div class="col-md-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <p class="card-text text-muted">
                                            Posted by {{ $post->user->name ?? 'Unknown' }} 
                                            on {{ $post->created_at->format('M d, Y') }}
                                        </p>
                                        <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('blog.show', $post) }}" class="btn btn-dark">Read More</a>
                                            @if(Auth::id() === $post->user_id)
                                                <div>
                                                    <a href="{{ route('blog.edit', $post) }}" class="btn btn-secondary">Edit</a>
                                                    <form action="{{ route('blog.delete', $post) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 