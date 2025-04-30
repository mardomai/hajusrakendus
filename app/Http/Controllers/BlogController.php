<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BlogController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = BlogPost::with(['user', 'comments'])->latest()->paginate(10);
        return view('blog.index', compact('posts'));
    }

    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $validated['user_id'] = Auth::id();
        $post = BlogPost::create($validated);

        return redirect()->route('blog.show', $post)
            ->with('success', 'Blog post created successfully.');
    }

    public function show(BlogPost $post)
    {
        $post->load(['user', 'comments.user']);
        return view('blog.show', compact('post'));
    }

    public function edit(BlogPost $post)
    {
        $this->authorize('update', $post);
        return view('blog.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $post->update($validated);

        return redirect()->route('blog.show', $post)
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        $this->authorize('delete', $post);
        
        $post->comments()->delete();
        $post->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Blog post deleted successfully.');
    }
} 