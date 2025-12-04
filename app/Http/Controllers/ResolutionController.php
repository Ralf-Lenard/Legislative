<?php

namespace App\Http\Controllers;

use App\Models\Resolution;
use App\Models\ResolutionDownloadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ResolutionController extends Controller
{

    // index user
    public function indexUser(Request $request)
    {
        $query = Resolution::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('resolutions_number', 'like', "%{$search}%")
                    ->orWhere('title_resolutions', 'like', "%{$search}%")
                    ->orWhere('description_resolutions', 'like', "%{$search}%")
                    ->orWhere('author_resolutions', 'like', "%{$search}%");
            });
        }

        // Year filter
        if ($request->filled('year')) {
            $query->whereYear('date_approved_resolutions', $request->year);
        }

        // Paginate results
        $resolutions = $query->paginate(10)->appends($request->only('search', 'year'));

        // Get unique years for filter dropdown
        $years = Resolution::selectRaw('YEAR(date_approved_resolutions) as year')
            ->whereNotNull('date_approved_resolutions')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->unique()
            ->values();

        return inertia('User/Resolutions', [
            'resolutions' => $resolutions,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'user' => Auth::user(),
            'canRegister' => Route::has('register'),
        ]);
    }

    // admin index
    public function index(Request $request)
    {
        $query = Resolution::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            // Important: wrap these in a closure to avoid mixing with the year filter
            $query->where(function ($q) use ($search) {
                $q->where('resolutions_number', 'like', "%{$search}%")
                    ->orWhere('title_resolutions', 'like', "%{$search}%")
                    ->orWhere('description_resolutions', 'like', "%{$search}%")
                    ->orWhere('author_resolutions', 'like', "%{$search}%");
            });
        }

        // ✅ Year filter
        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereYear('date_approved_resolutions', $year);
        }

        // ✅ Pagination with appended filters so search & year persist on links
        $resolutions = $query->paginate(10)->appends($request->only('search', 'year'));

        // Make sure $years is defined somewhere, e.g., all unique years in your table
        $years = Resolution::selectRaw('YEAR(date_approved_resolutions) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return inertia('Admin/Resolutions', [
            'resolutions' => $resolutions,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'canRegister' => Route::has('register'),
        ]);
    }





    // ==========================
    // STORE - Create resolution
    // ==========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resolutions_number' => 'required|unique:resolutions',
            'title_resolutions' => 'required',
            'description_resolutions' => 'nullable',
            'date_approved_resolutions' => 'nullable|date',

            'file_path_resolutions' => 'nullable|file|mimes:pdf',
            'image_resolutions' => 'nullable|image|mimes:jpg,png,jpeg,webp',

            'author_resolutions' => 'nullable',
        ]);

        // Upload PDF
        if ($request->hasFile('file_path_resolutions')) {
            $validated['file_path_resolutions'] = $request->file('file_path_resolutions')
                ->store('resolutions/pdf', 'public');
        }

        // Upload Image
        if ($request->hasFile('image_resolutions')) {
            $validated['image_resolutions'] = $request->file('image_resolutions')
                ->store('resolutions/images', 'public');
        }

        Resolution::create($validated);

        return back()->with('success', 'Resolution created successfully.');
    }

    // ==========================
    // UPDATE - Update resolution
    // ==========================
    public function update(Request $request, $id)
    {
        $resolution = Resolution::findOrFail($id);

        $validated = $request->validate([
            'rresolutions_number' => 'required|unique:resolutions,rresolutions_number,' . $id,
            'title_resolutions' => 'required',
            'description_resolutions' => 'nullable',
            'date_approved_resolutions' => 'nullable|date',

            'file_path_resolutions' => 'nullable|file|mimes:pdf',
            'image_resolutions' => 'nullable|image|mimes:jpg,png,jpeg,webp',

            'author_resolutions' => 'nullable',
        ]);

        // Replace PDF
        if ($request->hasFile('file_path_resolutions')) {
            if ($resolution->file_path_resolutions) {
                Storage::disk('public')->delete($resolution->file_path_resolutions);
            }
            $validated['file_path_resolutions'] = $request->file('file_path_resolutions')
                ->store('resolutions/pdf', 'public');
        }

        // Replace image
        if ($request->hasFile('image_resolutions')) {
            if ($resolution->image_resolutions) {
                Storage::disk('public')->delete($resolution->image_resolutions);
            }
            $validated['image_resolutions'] = $request->file('image_resolutions')
                ->store('resolutions/images', 'public');
        }

        $resolution->update($validated);

        return back()->with('success', 'Resolution updated successfully.');
    }

    // ==========================
    // DELETE - Remove resolution
    // ==========================
    public function destroy($id)
    {
        $resolution = Resolution::findOrFail($id);

        // Delete files
        if ($resolution->file_path_resolutions) {
            Storage::disk('public')->delete($resolution->file_path_resolutions);
        }

        if ($resolution->image_resolutions) {
            Storage::disk('public')->delete($resolution->image_resolutions);
        }

        $resolution->delete();

        return back()->with('success', 'Resolution deleted successfully.');
    }


    public function indexRequest(Request $request)
    {
        // Ensure only authorized users (e.g., admin) can access this route
        // Gate::authorize('view-ordinance-requests'); 

        $query = ResolutionDownloadRequest::with(['user', 'resolution']);
        
        // Capture both search and status filters from the request
        $filters = $request->only('search', 'status');

        // 1. Search Filter (Filtering by user name, resolution title/number, or purpose)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('resolution', function ($o) use ($search) {
                        // **FIX: Changed 'title_ordinances' to 'title_resolutions'**
                        $o->where('title_resolutions', 'like', "%{$search}%")
                            ->orWhere('resolutions_number', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Status Filter (New: Filtering by pending, approved, or rejected)
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $requests = $query
            ->latest()
            ->paginate(10)
            // Append all active filters to the pagination links
            ->appends($filters);

        return inertia('Admin/ResolutionDownloadRequest', [
            'requests' => $requests,
            'filters' => $filters, // Pass all active filters back to the front-end
        ]);
    }

}
