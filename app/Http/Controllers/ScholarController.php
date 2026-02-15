<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ScholarController extends Controller
{
    // Show all scholars using Inertia
    public function index()
    {
        $scholars = Scholar::all(); // You can also paginate if needed
        return Inertia::render('Scholars/Index', [
            'scholars' => $scholars,
        ]);
    }

    // Store a new scholar
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('scholars', 'public');
        }

        Scholar::create([
            'full_name' => $request->full_name,
            'image' => $imagePath,
            'course' => $request->course,
            'year_level' => $request->year_level,
        ]);

        return redirect()->back()->with('success', 'Scholar added successfully!');
    }

    // Update an existing scholar
    // Update an existing scholar by ID
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
        ]);

        $scholar = Scholar::findOrFail($id);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($scholar->image && Storage::disk('public')->exists($scholar->image)) {
                Storage::disk('public')->delete($scholar->image);
            }
            $scholar->image = $request->file('image')->store('scholars', 'public');
        }

        $scholar->full_name = $request->full_name;
        $scholar->course = $request->course;
        $scholar->year_level = $request->year_level;
        $scholar->save();

        return redirect()->back()->with('success', 'Scholar updated successfully!');
    }

    // Delete a scholar by ID
    public function destroy($id)
    {
        $scholar = Scholar::findOrFail($id);

        // Delete image if exists
        if ($scholar->image && Storage::disk('public')->exists($scholar->image)) {
            Storage::disk('public')->delete($scholar->image);
        }

        $scholar->delete();

        return redirect()->back()->with('success', 'Scholar deleted successfully!');
    }
}
