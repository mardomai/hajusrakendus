@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">{{ $post->title }}</h2>
                    <p class="text-muted">
                        Posted by {{ $post->user->name }} 
                        on {{ $post->created_at->format('M d, Y') }}
                    </p>
                    
                    <div class="blog-content mb-4">
                        {{ $post->description }}
                    </div>

                    @if(Auth::id() === $post->user_id)
                        <div class="mb-4">
                            <a href="{{ route('blog.edit', $post) }}" class="btn btn-secondary">Edit Post</a>
                            <form action="{{ route('blog.delete', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</button>
                            </form>
                        </div>
                    @endif

                    <hr>

                    <!-- Comments Section -->
                    <div class="comments-section">
                        <h4>Comments</h4>
                        
                        @auth
                            <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                        name="content" rows="3" placeholder="Write a comment..." required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Post Comment</button>
                            </form>
                        @else
                            <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to post a comment.</p>
                        @endauth

                        <div class="comments-list">
                            @forelse($post->comments as $comment)
                                <div class="comment card mb-3">
                                    <div class="card-body">
                                        <p class="mb-1">{{ $comment->content }}</p>
                                        <small class="text-muted">
                                            {{ $comment->user->name }} - 
                                            {{ $comment->created_at->diffForHumans() }}
                                            
                                            @if(Auth::id() === $comment->user_id || Auth::id() === $post->user_id)
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline float-end">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No comments yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('blog.index') }}" class="btn btn-secondary">Back to Posts</a>
            </div>
        </div>
    </div>
</div>
@endsection 