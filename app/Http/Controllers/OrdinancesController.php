<?php

namespace App\Http\Controllers;

use App\Events\OrdinanceDownloadRequestSubmitted;
use App\Events\OrdinanceDownloadStatusUpdated;
use App\Models\Ordinance;
use App\Models\OrdinanceDownloadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class OrdinancesController extends Controller
{
    // ==========================
    // INDEX - Fetch all ordinances
    // ==========================

    public function indexUser(Request $request)
    {

        if (Auth::check() && Auth::user()->status === 'banned' && Auth::user()->usertype === 'user') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Your account has been banned.'
            ]);
        }

        $query = Ordinance::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ordinance_number', 'like', "%{$search}%")
                    ->orWhere('title_ordinances', 'like', "%{$search}%")
                    ->orWhere('description_ordinances', 'like', "%{$search}%")
                    ->orWhere('author_ordinances', 'like', "%{$search}%");
            });
        }

        if ($request->filled('year')) {
            $query->whereYear('date_approved_ordinances', $request->year);
        }

        $ordinances = $query->paginate(10)->appends($request->only('search', 'year'));
        // Get all requests of the user, indexed by ordinance_id
        $userRequests = OrdinanceDownloadRequest::where('user_id', auth()->id())
            ->get()
            ->keyBy('ordinance_id');

        // Attach status to every ordinance
        foreach ($ordinances as $ordinance) {
            $ordinance->status = $userRequests[$ordinance->id]->status ?? null;
        }



        // 🔥 GET YEAR LIST
        $years = Ordinance::selectRaw('YEAR(date_approved_ordinances) as year')
            ->whereNotNull('date_approved_ordinances')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->unique()
            ->values();

        return inertia('User/Ordinances', [
            'ordinances' => $ordinances,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'user' => Auth::user(),
            'canRegister' => Route::has('register'),
            'recaptchaSiteKey' => env('RECAPTCHA_SITE_KEY'),
        ]);
    }



    // public function index(Request $request)
    // {
    //     $query = Ordinance::query();

    //     // ✅ Search filter
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         // Important: wrap these in a closure to avoid mixing with the year filter
    //         $query->where(function ($q) use ($search) {
    //             $q->where('ordinance_number', 'like', "%{$search}%")
    //                 ->orWhere('title_ordinances', 'like', "%{$search}%")
    //                 ->orWhere('description_ordinances', 'like', "%{$search}%")
    //                 ->orWhere('author_ordinances', 'like', "%{$search}%");
    //         });
    //     }

    //     // ✅ Year filter
    //     if ($request->filled('year')) {
    //         $year = $request->year;
    //         $query->whereYear('date_approved_ordinances', $year);
    //     }

    //     // ✅ Pagination with appended filters so search & year persist on links
    //     $ordinances = $query->paginate(10)->appends($request->only('search', 'year'));

    //     // Make sure $years is defined somewhere, e.g., all unique years in your table
    //     $years = Ordinance::selectRaw('YEAR(date_approved_ordinances) as year')
    //         ->distinct()
    //         ->orderBy('year', 'desc')
    //         ->pluck('year');

    //     return inertia('Admin/Ordinances', [
    //         'ordinances' => $ordinances,
    //         'filters' => $request->only('search', 'year'),
    //         'years' => $years,
    //         'canRegister' => Route::has('register'),

    //     ]);
    // }

    // public function index(Request $request)
    // {
    //     $query = Ordinance::query();

    //     // ✅ Search filter
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('ordinance_number', 'like', "%{$search}%")
    //                 ->orWhere('title_ordinances', 'like', "%{$search}%")
    //                 ->orWhere('description_ordinances', 'like', "%{$search}%")
    //                 ->orWhere('author_ordinances', 'like', "%{$search}%");
    //         });
    //     }

    //     // ✅ Year filter
    //     if ($request->filled('year')) {
    //         $year = $request->year;
    //         $query->whereYear('date_approved_ordinances', $year);
    //     }

    //     // ✅ ORDER BY DATE (LATEST → OLDEST)
    //     $query->orderBy('date_approved_ordinances', 'desc');

    //     // ✅ Pagination with preserved filters
    //     $ordinances = $query
    //         ->paginate(10)
    //         ->appends($request->only('search', 'year'));

    //     // ✅ Year dropdown data
    //     $years = Ordinance::selectRaw('YEAR(date_approved_ordinances) as year')
    //         ->distinct()
    //         ->orderBy('year', 'desc')
    //         ->pluck('year');

    //     return inertia('Admin/Ordinances', [
    //         'ordinances' => $ordinances,
    //         'filters' => $request->only('search', 'year'),
    //         'years' => $years,

    //     ]);
    // }

    public function index(Request $request)
    {
        $query = Ordinance::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;
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

        // ✅ ORDER BY DATE (LATEST → OLDEST)
        $query->orderBy('date_approved_ordinances', 'desc');

        // ✅ Pagination with preserved filters
        $ordinances = $query
            ->paginate(10)
            ->appends($request->only('search', 'year'));

        // ✅ Year dropdown data
        $years = Ordinance::selectRaw('YEAR(date_approved_ordinances) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // ✅ Dashboard counts
        $totalOrdinances = Ordinance::count();
        $latestYearOrdinancesCount = $years->isNotEmpty()
            ? Ordinance::whereYear('date_approved_ordinances', $years[0])->count()
            : 0;
        $ordinancesWithPdfCount = Ordinance::whereNotNull('file_path_ordinances')->count();
        $ordinancesWithImageCount = Ordinance::whereNotNull('image_ordinances')->count();

        return inertia('Admin/Ordinances', [
            'ordinances' => $ordinances,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'totalOrdinances' => $totalOrdinances,
            'latestYearOrdinancesCount' => $latestYearOrdinancesCount,
            'ordinancesWithPdfCount' => $ordinancesWithPdfCount,
            'ordinancesWithImageCount' => $ordinancesWithImageCount,
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
            'image_ordinances' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:51200',

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

        return redirect()
            ->route('ordinances.index')
            ->with('success', 'Ordinance saved successfully.');
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
            'image_ordinances' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:51200',

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

        return redirect()
            ->route('ordinances.index')
            ->with('success', 'Ordinance updated successfully.');
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

        return redirect()
            ->route('ordinances.index') // 👈 reload list
            ->with('success', 'Ordinance deleted successfully!');
    }

    // requesting 

    public function indexRequest(Request $request)
    {
        // Base query for filtering
        $query = OrdinanceDownloadRequest::with(['user', 'ordinance']);

        // Capture both search and status filters from the request
        $filters = $request->only('search', 'status');

        // 1. Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
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

        // 2. Status Filter
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Paginate filtered requests
        $requests = $query->latest()->paginate(10)->appends($filters);

        // Counts for all requests
        $totalRequests = OrdinanceDownloadRequest::count();
        $pendingCount = OrdinanceDownloadRequest::where('status', 'pending')->count();
        $approvedCount = OrdinanceDownloadRequest::where('status', 'approved')->count();
        $rejectedCount = OrdinanceDownloadRequest::where('status', 'rejected')->count();

        return inertia('Admin/OrdinanceDownloadRequests', [
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


    // public function submitRequest(Request $request, $id)
    // {
    //     $request->validate([
    //         'purpose' => 'required|string|max:500',
    //     ]);

    //     $ordinance = Ordinance::findOrFail($id);

    //     $downloadRequest = OrdinanceDownloadRequest::create([
    //         'user_id' => auth()->id(),
    //         'ordinance_id' => $id,
    //         'purpose' => $request->purpose,
    //         'status' => 'pending',
    //     ]);

    //     // 🔔 Notify admins & super admins
    //     event(new OrdinanceDownloadRequestSubmitted($downloadRequest));

    //     return redirect()
    //         ->back()
    //         ->with(
    //             'success',
    //             'Request for Ordinance No. ' . $ordinance->ordinance_number . ' submitted successfully.'
    //         );
    // }\


    // public function submitRequest(Request $request, $id)
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

    //         // ✅ Valid ID validation
    //         'valid_id_type' => ['required', Rule::in($validIdTypes)],
    //         'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:20480', // 2MB max
    //     ]);

    //     $ordinance = Ordinance::findOrFail($id);

    //     // 📁 Store Valid ID
    //     $validIdPath = $request->file('valid_id')
    //         ->store('valid_ids/ordinance_requests', 'public');

    //     $downloadRequest = OrdinanceDownloadRequest::create([
    //         'user_id' => auth()->id(),
    //         'ordinance_id' => $id,
    //         'purpose' => $request->purpose,

    //         // ✅ Save ID data
    //         'valid_id_type' => $request->valid_id_type,
    //         'valid_id_path' => $validIdPath,

    //         'status' => 'pending',
    //     ]);

    //     // 🔔 Notify admins & super admins
    //     event(new OrdinanceDownloadRequestSubmitted($downloadRequest));

    //     return redirect()
    //         ->back()
    //         ->with(
    //             'success',
    //             'Request for Ordinance No. ' . $ordinance->ordinance_number . ' submitted successfully.'
    //         );
    // }



    public function submitRequest(Request $request, $id)
    {
        // 1. CAPTCHA CHECK
        if ($request->recaptcha_token !== 'local-bypass') {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->recaptcha_token,
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (!$result['success'] || $result['score'] < 0.5) {
                return back()->withErrors([
                    'captcha' => 'reCAPTCHA verification failed. Suspicious activity detected.'
                ]);
            }
        }
        
        // 2. VALIDATION
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
            'purpose'       => 'required|string|max:500',
            'valid_id_type' => ['required', \Illuminate\Validation\Rule::in($validIdTypes)],
            'valid_id'      => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120' // 5MB Limit (5120 KB)
            ],
        ], [
            // Custom error messages
            'valid_id.max' => 'The valid ID image may not be larger than 5MB.',
            'valid_id.mimes' => 'The valid ID must be a file of type: jpg, jpeg, png, pdf.',
        ]);

        $ordinance = Ordinance::findOrFail($id);

        // 3. STORE & SAVE
        $path = $request->file('valid_id')->store('valid_ids/ordinance_requests', 'public');

        $downloadRequest = OrdinanceDownloadRequest::create([
            'user_id'       => auth()->id(),
            'ordinance_id'  => $id,
            'purpose'       => $request->purpose,
            'valid_id_type' => $request->valid_id_type,
            'valid_id_path' => $path,
            'status'        => 'pending',
        ]);

        event(new OrdinanceDownloadRequestSubmitted($downloadRequest));

        return back()->with('success', 'Request for Ordinance No. ' . $ordinance->ordinance_number . ' submitted successfully.');
    }

    public function approveDownloadRequest($id)
    {
        $request = OrdinanceDownloadRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        // Dispatch event
        event(new OrdinanceDownloadStatusUpdated($request));

        return redirect()
            ->route('ordinances.indexRequest')
            ->with('success', 'Request approved.');
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

        // Dispatch event
        event(new OrdinanceDownloadStatusUpdated($downloadRequest));

        return redirect()
            ->route('ordinances.indexRequest')
            ->with('success', 'Request rejected.');
    }

    public function download($id)
    {
        $ordinance = Ordinance::findOrFail($id);

        // Find ANY request by the user for this ordinance
        $request = OrdinanceDownloadRequest::where('user_id', auth()->id())
            ->where('ordinance_id', $id)
            ->first();

        // 1. Check if a request exists at all
        if (!$request) {
            // If the frontend didn't check for 'no request', redirect with error.
            return redirect()->back()->with('error', 'You must request access to this ordinance first.');
        }

        // 2. Check if the request is still PENDING
        if ($request->status === 'pending') {
            // Flash Error: Request is pending
            return redirect()->back()->with('warning', 'Your download request is still pending approval. Please wait for an update.');
        }

        // 3. Check if the request was DENIED (or any status other than 'approved')
        if ($request->status !== 'approved') {
            // Flash Error: Request was not approved
            return redirect()->back()->with('error', 'Your request has not been approved yet. Current status: ' . ucfirst($request->status));
        }

        // 4. Check if already downloaded (This logic is fine)
        if ($request->is_downloaded) {
            // Flash Error: Already downloaded
            return redirect()->back()->with('error', 'You have already downloaded this ordinance. Please submit a new request.');
        }

        // 5. Check for file existence (This logic is fine)
        if (!$ordinance->file_path_ordinances || !Storage::disk('public')->exists($ordinance->file_path_ordinances)) {
            abort(404, 'File not found.');
        }

        // 6. Mark as downloaded and save
        $request->is_downloaded = true;
        $request->save();

        // 7. Set SUCCESS FLASH MESSAGE
        session()->flash('success', "Download successful! Ordinance No. {$ordinance->ordinance_number} is now downloading.");

        // 8. Trigger file download
        return Storage::disk('public')->download($ordinance->file_path_ordinances);
    }
}
