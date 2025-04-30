<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $markers = Marker::all();
        return view('markers.index', compact('markers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('markers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string'
        ]);

        Marker::create($validated);

        return redirect()->route('markers.index')
            ->with('success', 'Marker created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marker $marker)
    {
        return view('markers.show', compact('marker'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marker $marker)
    {
        return view('markers.edit', compact('marker'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marker $marker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string'
        ]);

        $marker->update($validated);

        return redirect()->route('markers.index')
            ->with('success', 'Marker updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marker $marker)
    {
        $marker->delete();

        return redirect()->route('markers.index')
            ->with('success', 'Marker deleted successfully.');
    }
}
