<?php

namespace App\Http\Controllers;

use App\Models\Ordinance;
use App\Models\OrdinanceDownloadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class OrdinancesController extends Controller
{
    // ==========================
    // INDEX - Fetch all ordinances
    // ==========================

    public function indexUser(Request $request)
    {
        $query = Ordinance::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            // Important: wrap these in a closure to avoid mixing with the year filter
            $query->where(function ($q) use ($search) {
                $q->where('ordinance_number', 'like', "%{$search}%")
                    ->orWhere('title_ordinances', 'like', "%{$search}%")
                    ->orWhere('description_ordinances', 'like', "%{$search}%")
                    ->orWhere('author_ordinances', 'like', "%{$search}%");
            });
        }

        // ✅ Year filter
        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereYear('date_approved_ordinances', $year);
        }

        // ✅ Pagination with appended filters so search & year persist on links
        $ordinances = $query->paginate(10)->appends($request->only('search', 'year'));

        // Make sure $years is defined somewhere, e.g., all unique years in your table
        $years = Ordinance::selectRaw('YEAR(date_approved_ordinances) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return inertia('User/Ordinances', [
            'ordinances' => $ordinances,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
        ]);
    }


    public function index(Request $request)
    {
        $query = Ordinance::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            // Important: wrap these in a closure to avoid mixing with the year filter
            $query->where(function ($q) use ($search) {
                $q->where('ordinance_number', 'like', "%{$search}%")
                    ->orWhere('title_ordinances', 'like', "%{$search}%")
                    ->orWhere('description_ordinances', 'like', "%{$search}%")
                    ->orWhere('author_ordinances', 'like', "%{$search}%");
            });
        }

        // ✅ Year filter
        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereYear('date_approved_ordinances', $year);
        }

        // ✅ Pagination with appended filters so search & year persist on links
        $ordinances = $query->paginate(10)->appends($request->only('search', 'year'));

        // Make sure $years is defined somewhere, e.g., all unique years in your table
        $years = Ordinance::selectRaw('YEAR(date_approved_ordinances) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return inertia('Admin/Ordinances', [
            'ordinances' => $ordinances,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
        ]);
    }





    // ==========================
    // STORE - Create ordinance
    // ==========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ordinance_number' => 'required|unique:ordinances',
            'title_ordinances' => 'required',
            'description_ordinances' => 'nullable',
            'date_approved_ordinances' => 'nullable|date',

            'file_path_ordinances' => 'nullable|file|mimes:pdf',
            'image_ordinances' => 'nullable|image|mimes:jpg,png,jpeg,webp',

            'author_ordinances' => 'nullable',
        ]);

        // Upload PDF
        if ($request->hasFile('file_path_ordinances')) {
            $validated['file_path_ordinances'] = $request->file('file_path_ordinances')
                ->store('ordinances/pdf', 'public');
        }

        // Upload Image
        if ($request->hasFile('image_ordinances')) {
            $validated['image_ordinances'] = $request->file('image_ordinances')
                ->store('ordinances/images', 'public');
        }

        Ordinance::create($validated);

        return back()->with('success', 'Ordinance created successfully.');
    }

    // ==========================
    // UPDATE - Update ordinance
    // ==========================
    public function update(Request $request, $id)
    {
        $ordinance = Ordinance::findOrFail($id);

        $validated = $request->validate([
            'ordinance_number' => 'required|unique:ordinances,ordinance_number,' . $id,
            'title_ordinances' => 'required',
            'description_ordinances' => 'nullable',
            'date_approved_ordinances' => 'nullable|date',

            'file_path_ordinances' => 'nullable|file|mimes:pdf',
            'image_ordinances' => 'nullable|image|mimes:jpg,png,jpeg,webp',

            'author_ordinances' => 'nullable',
        ]);

        // Replace PDF
        if ($request->hasFile('file_path_ordinances')) {
            if ($ordinance->file_path_ordinances) {
                Storage::disk('public')->delete($ordinance->file_path_ordinances);
            }
            $validated['file_path_ordinances'] = $request->file('file_path_ordinances')
                ->store('ordinances/pdf', 'public');
        }

        // Replace image
        if ($request->hasFile('image_ordinances')) {
            if ($ordinance->image_ordinances) {
                Storage::disk('public')->delete($ordinance->image_ordinances);
            }
            $validated['image_ordinances'] = $request->file('image_ordinances')
                ->store('ordinances/images', 'public');
        }

        $ordinance->update($validated);

        return back()->with('success', 'Ordinance updated successfully.');
    }

    // ==========================
    // DELETE - Remove ordinance
    // ==========================
    public function destroy($id)
    {
        $ordinance = Ordinance::findOrFail($id);

        // Delete files
        if ($ordinance->file_path_ordinances) {
            Storage::disk('public')->delete($ordinance->file_path_ordinances);
        }

        if ($ordinance->image_ordinances) {
            Storage::disk('public')->delete($ordinance->image_ordinances);
        }

        $ordinance->delete();

        return back()->with('success', 'Ordinance deleted successfully.');
    }

    // requesting 

    public function indexRequest(Request $request)
    {
        // Only allow admin
        // if (auth()->user()->usertype !== 'admin') {
        //     abort(403, 'Unauthorized action.');
        // }

        $query = OrdinanceDownloadRequest::with(['user', 'ordinance']);

        // ------------------------------------
        // 🔍 SEARCH
        // ------------------------------------
        if ($request->filled('search')) {
            $search = $request->search;

            // Search on user name, ordinance title, or purpose
            $query->where(function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('ordinance', function ($o) use ($search) {
                        $o->where('title_ordinances', 'like', "%{$search}%")
                            ->orWhere('ordinance_number', 'like', "%{$search}%");
                    });
            });
        }

        // ------------------------------------
        // 📄 PAGINATION
        // ------------------------------------
        $requests = $query
            ->latest()
            ->paginate(10)
            ->appends($request->only('search'));

        return inertia('Admin/OrdinanceDownloadRequests', [
            'requests' => $requests,
            'filters' => $request->only('search'),
        ]);
    }



    public function submitRequest(Request $request, $id)
    {
        $request->validate([
            'purpose' => 'required|string|max:500',
        ]);

        OrdinanceDownloadRequest::create([
            'user_id' => auth()->id(),
            'ordinance_id' => $id,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('message', 'Request submitted. Wait for admin approval.');
    }

    public function approveDownloadRequest($id)
    {
        $request = OrdinanceDownloadRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        return back()->with('success', 'Request approved.');
    }

    public function rejectDownloadRequest(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $downloadRequest = OrdinanceDownloadRequest::findOrFail($id);
        $downloadRequest->status = 'rejected';
        $downloadRequest->rejection_reason = $request->reason;
        $downloadRequest->save();

        return back()->with('success', 'Request rejected.');
    }



}
