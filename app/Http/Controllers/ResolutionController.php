<?php

namespace App\Http\Controllers;

use App\Events\ResolutionDownloadRequestSubmitted;
use App\Events\ResolutionDownloadStatusUpdated;
use App\Models\Resolution;
use App\Models\ResolutionDownloadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;


class ResolutionController extends Controller
{

    // index user
    public function indexUser(Request $request)
    {

        if (Auth::check() && Auth::user()->status === 'banned' && Auth::user()->usertype === 'user') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Your account has been banned.'
            ]);
        }

        $query = Resolution::query();

        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('resolutions_number', 'like', "%{$search}%")
                    ->orWhere('title_resolutions', 'like', "%{$search}%")
                    ->orWhere('description_resolutions', 'like', "%{$search}%")
                    ->orWhere('author_resolutions', 'like', "%{$search}%");
            });
        }

        // 📅 Year filter
        if ($request->filled('year')) {
            $query->whereYear('date_approved_resolutions', $request->year);
        }

        // 📄 Pagination
        $resolutions = $query
            ->paginate(10)
            ->appends($request->only('search', 'year'));

        // Get all resolution requests of the user, keyed by resolution_id
        $userRequests = ResolutionDownloadRequest::where('user_id', auth()->id())
            ->get()
            ->keyBy('resolution_id');

        // Attach user request status to every resolution
        foreach ($resolutions as $resolution) {
            $resolution->status = $userRequests[$resolution->id]->status ?? null;
        }

        // 🔥 Year dropdown list
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
    // public function index(Request $request)
    // {
    //     $query = Resolution::query();

    //     // ✅ Search filter
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('resolutions_number', 'like', "%{$search}%")
    //                 ->orWhere('title_resolutions', 'like', "%{$search}%")
    //                 ->orWhere('description_resolutions', 'like', "%{$search}%")
    //                 ->orWhere('author_resolutions', 'like', "%{$search}%");
    //         });
    //     }

    //     // ✅ Year filter
    //     if ($request->filled('year')) {
    //         $year = $request->year;
    //         $query->whereYear('date_approved_resolutions', $year);
    //     }

    //     // ✅ Order by latest approved date first
    //     $query->orderBy('date_approved_resolutions', 'desc');

    //     // ✅ Pagination with appended filters
    //     $resolutions = $query->paginate(10)->appends($request->only('search', 'year'));

    //     // ✅ Unique years for dropdown
    //     $years = Resolution::selectRaw('YEAR(date_approved_resolutions) as year')
    //         ->distinct()
    //         ->orderBy('year', 'desc')
    //         ->pluck('year');

    //     return inertia('Admin/Resolutions', [
    //         'resolutions' => $resolutions,
    //         'filters' => $request->only('search', 'year'),
    //         'years' => $years,
    //     ]);
    // }

    public function index(Request $request)
    {
        $query = Resolution::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;
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

        // ✅ Order by latest approved date first
        $query->orderBy('date_approved_resolutions', 'desc');

        // ✅ Pagination with appended filters
        $resolutions = $query->paginate(10)->appends($request->only('search', 'year'));

        // ✅ Unique years for dropdown
        $years = Resolution::selectRaw('YEAR(date_approved_resolutions) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // ✅ Dashboard counts
        $totalResolutions = Resolution::count();
        $latestYearResolutionsCount = $years->isNotEmpty()
            ? Resolution::whereYear('date_approved_resolutions', $years[0])->count()
            : 0;
        $resolutionsWithPdfCount = Resolution::whereNotNull('file_path_resolutions')->count();
        $resolutionsWithImageCount = Resolution::whereNotNull('image_resolutions')->count();

        return inertia('Admin/Resolutions', [
            'resolutions' => $resolutions,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'totalResolutions' => $totalResolutions,
            'latestYearResolutionsCount' => $latestYearResolutionsCount,
            'resolutionsWithPdfCount' => $resolutionsWithPdfCount,
            'resolutionsWithImageCount' => $resolutionsWithImageCount,
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
            'image_resolutions' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:51200',

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

        return redirect()
            ->route('resolutions.index')
            ->with('success', 'Resolution created successfully.');
    }

    // ==========================
    // UPDATE - Update resolution
    // ==========================
    public function update(Request $request, $id)
    {
        $resolution = Resolution::findOrFail($id);

        $validated = $request->validate([
            'resolutions_number' => 'required|unique:resolutions,resolutions_number,' . $id,
            'title_resolutions' => 'required',
            'description_resolutions' => 'nullable',
            'date_approved_resolutions' => 'nullable|date',

            'file_path_resolutions' => 'nullable|file|mimes:pdf',
            'image_resolutions' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:51200',

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

        return redirect()
            ->route('resolutions.index')
            ->with('success', 'Resolution updated successfully.');
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

        return redirect()
            ->route('resolutions.index')
            ->with('success', 'Resolution deleted successfully.');
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

        // Counts for all requests
        $totalRequests = ResolutionDownloadRequest::count();
        $pendingCount = ResolutionDownloadRequest::where('status', 'pending')->count();
        $approvedCount = ResolutionDownloadRequest::where('status', 'approved')->count();
        $rejectedCount = ResolutionDownloadRequest::where('status', 'rejected')->count();

        return inertia('Admin/ResolutionDownloadRequest', [
            'requests' => $requests,
            'filters' => $filters,
            'counts' => [
                'total' => $totalRequests,
                'pending' => $pendingCount,
                'approved' => $approvedCount,
                'rejected' => $rejectedCount,
            ],
        ]);
    }

    // public function submitResolutionRequest(Request $request, $id)
    // {
    //     $validIdTypes = [
    //         'PhilSys National ID',
    //         'Passport',
    //         'Driver’s License',
    //         'UMID',
    //         'Voter’s ID',
    //         'Postal ID',
    //         'PRC ID',
    //         'Senior Citizen ID',
    //         'PWD ID',
    //         'SSS ID',
    //         'GSIS ID',
    //         'TIN ID',
    //         'PhilHealth ID',
    //     ];

    //     $request->validate([
    //         'purpose' => 'required|string|max:500',
    //         'valid_id_type' => ['required', Rule::in($validIdTypes)],
    //         'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:20480', // 20MB
    //     ]);


    //     // Fetch the resolution first
    //     $resolution = Resolution::findOrFail($id);

    //     // 📁 Store Valid ID
    //     $validIdPath = $request->file('valid_id')
    //         ->store('valid_ids/ordinance_requests', 'public');

    //     // Create the download request
    //     $downloadRequest = ResolutionDownloadRequest::create([
    //         'user_id' => auth()->id(),
    //         'resolution_id' => $id,
    //         'purpose' => $request->purpose,  
    //         // ✅ Save ID data
    //         'valid_id_type' => $request->valid_id_type,
    //         'valid_id_path' => $validIdPath,

    //         'status' => 'pending',
    //     ]);

    //     // 🔔 Notify admins & super admins in real-time
    //     event(new ResolutionDownloadRequestSubmitted($downloadRequest));

    //     return redirect()
    //         ->back()
    //         ->with(
    //             'success',
    //             'Request for Resolution No. ' . $resolution->resolutions_number . ' submitted successfully.'
    //         );
    // }



    public function submitResolutionRequest(Request $request, $id)
    {
        // 🔐 CAPTCHA CHECK (FIRST LINE)
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->recaptcha_token,
            'remoteip' => $request->ip(),
        ]);

        if (
            !$response->json('success') ||
            ($response->json('score') ?? 0) < 0.5
        ) {
            return back()->withErrors([
                'captcha' => 'Suspicious activity detected. Please try again.'
            ]);
        }

        // -----------------------------
        // YOUR ORIGINAL VALIDATION
        // -----------------------------

        $validIdTypes = [
            'PhilSys National ID',
            'Passport',
            'Driver’s License',
            'UMID',
            'Voter’s ID',
            'Postal ID',
            'PRC ID',
            'Senior Citizen ID',
            'PWD ID',
            'SSS ID',
            'GSIS ID',
            'TIN ID',
            'PhilHealth ID',
        ];

        $request->validate([
            'purpose' => 'required|string|max:500',
            'valid_id_type' => ['required', Rule::in($validIdTypes)],
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:20480',
        ]);

        // Fetch resolution
        $resolution = Resolution::findOrFail($id);

        // 📁 Store Valid ID
        $validIdPath = $request->file('valid_id')
            ->store('valid_ids/resolution_requests', 'public'); // (recommended fix)

        // Create request
        $downloadRequest = ResolutionDownloadRequest::create([
            'user_id' => auth()->id(),
            'resolution_id' => $id,
            'purpose' => $request->purpose,

            'valid_id_type' => $request->valid_id_type,
            'valid_id_path' => $validIdPath,

            'status' => 'pending',
        ]);

        // 🔔 Notify admins
        event(new ResolutionDownloadRequestSubmitted($downloadRequest));

        return redirect()
            ->back()
            ->with(
                'success',
                'Request for Resolution No. ' . $resolution->resolutions_number . ' submitted successfully.'
            );
    }


    /**
     * Approve resolution download request (ADMIN)
     */
    public function approveDownloadRequest($id)
    {
        $request = ResolutionDownloadRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        // 🔔 Fire notification + broadcast
        event(new ResolutionDownloadStatusUpdated($request));

        return redirect()
            ->route('resolutions.indexRequest')
            ->with('success', 'Request approved.');
    }


    /**
     * Reject resolution download request (ADMIN)
     */
    public function rejectDownloadRequest(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $downloadRequest = ResolutionDownloadRequest::findOrFail($id);
        $downloadRequest->status = 'rejected';
        $downloadRequest->rejection_reason = $request->reason;
        $downloadRequest->save();

        // 🔔 Fire notification + broadcast
        event(new ResolutionDownloadStatusUpdated($downloadRequest));

        return redirect()
            ->route('resolutions.indexRequest')
            ->with('success', 'Request rejected.');
    }


    /**
     * Download resolution (USER)
     */
    public function download($id)
    {
        $resolution = Resolution::findOrFail($id);

        // Find ANY request by the user for this resolution
        $request = ResolutionDownloadRequest::where('user_id', auth()->id())
            ->where('resolution_id', $id)
            ->first();

        // 1. No request at all
        if (!$request) {
            return redirect()
                ->back()
                ->with('error', 'You must request access to this resolution first.');
        }

        // 2. Still pending
        if ($request->status === 'pending') {
            return redirect()
                ->back()
                ->with('warning', 'Your download request is still pending approval. Please wait for an update.');
        }

        // 3. Not approved
        if ($request->status !== 'approved') {
            return redirect()
                ->back()
                ->with('error', 'Your request has not been approved yet. Current status: ' . ucfirst($request->status));
        }

        // 4. Already downloaded
        if ($request->is_downloaded) {
            return redirect()
                ->back()
                ->with('error', 'You have already downloaded this resolution. Please submit a new request.');
        }

        // 5. File existence check
        if (
            !$resolution->file_path_resolutions ||
            !Storage::disk('public')->exists($resolution->file_path_resolutions)
        ) {
            abort(404, 'File not found.');
        }

        // 6. Mark as downloaded
        $request->is_downloaded = true;
        $request->save();

        // 7. Success flash message
        session()->flash(
            'success',
            "Download successful! Resolution No. {$resolution->resolutions_number} is now downloading."
        );

        // 8. Download file
        return Storage::disk('public')->download($resolution->file_path_resolutions);
    }
}
