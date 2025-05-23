<?php

namespace App\Http\Controllers;

use App\Models\MyFavoriteSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MyFavoriteSubjectController extends Controller
{
    public function indexView()
    {
        $subjects = MyFavoriteSubject::latest()->paginate(10);
        return view('favorite-subjects.index', compact('subjects'));
    }

    public function createView()
    {
        return view('favorite-subjects.create');
    }

    public function editView(MyFavoriteSubject $myFavoriteSubject)
    {
        return view('favorite-subjects.edit', compact('myFavoriteSubject'));
    }

    public function storeView(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:2048',
            'category' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $imagePath = $request->file('image')->store('subjects', 'public');
        $validated['image'] = Storage::url($imagePath);

        MyFavoriteSubject::create($validated);

        Cache::forget('favorite_subjects_list');

        return redirect()->route('favorite-subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        
        $subjects = Cache::remember('favorite_subjects_list', 3600, function () use ($limit) {
            return MyFavoriteSubject::latest()->take($limit)->get();
        });

        return response()->json($subjects);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:2048',
            'category' => 'required|string|max:255',
            'rating' => 'required|integer|min:0|max:5'
        ]);

        $imagePath = $request->file('image')->store('subjects', 'public');
        $validated['image'] = Storage::url($imagePath);

        $subject = MyFavoriteSubject::create($validated);

        Cache::forget('favorite_subjects_list');

        return response()->json($subject, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MyFavoriteSubject $myFavoriteSubject)
    {
        return response()->json($myFavoriteSubject);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MyFavoriteSubject $myFavoriteSubject)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|required|image|max:2048',
            'category' => 'sometimes|required|string|max:255',
            'rating' => 'sometimes|required|integer|min:0|max:5'
        ]);

        if ($request->hasFile('image')) {
            if ($myFavoriteSubject->image) {
                Storage::delete(str_replace('/storage/', 'public/', $myFavoriteSubject->image));
            }
            
            $imagePath = $request->file('image')->store('subjects', 'public');
            $validated['image'] = Storage::url($imagePath);
        }

        $myFavoriteSubject->update($validated);

        Cache::forget('favorite_subjects_list');

        return response()->json($myFavoriteSubject);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MyFavoriteSubject $myFavoriteSubject)
    {
        if ($myFavoriteSubject->image) {
            Storage::delete(str_replace('/storage/', 'public/', $myFavoriteSubject->image));
        }

        $myFavoriteSubject->delete();
        Cache::forget('favorite_subjects_list');

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('favorite-subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }

    public function updateView(Request $request, MyFavoriteSubject $myFavoriteSubject)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        if ($request->hasFile('image')) {
            if ($myFavoriteSubject->image) {
                Storage::delete(str_replace('/storage/', 'public/', $myFavoriteSubject->image));
            }
            
            $imagePath = $request->file('image')->store('subjects', 'public');
            $validated['image'] = Storage::url($imagePath);
        }

        $myFavoriteSubject->update($validated);

        Cache::forget('favorite_subjects_list');

        return redirect()->route('favorite-subjects.index')
            ->with('success', 'Subject updated successfully!');
    }
}
