<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogPost::with(['user', 'comments'])->latest()->get();
        return view('blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $post = new BlogPost();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->user_id = Auth::id();
        $post->save();

        return redirect()->route('blog.show', $post)->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $post)
    {
        return view('blog.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $post)
    {
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('blog.index')->with('error', 'Unauthorized action.');
        }
        return view('blog.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $post)
    {
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('blog.index')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $post->update($validated);
        return redirect()->route('blog.show', $post)->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $post)
    {
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('blog.index')->with('error', 'Unauthorized action.');
        }

        $post->delete();
        return redirect()->route('blog.index')->with('success', 'Post deleted successfully!');
    }
}
