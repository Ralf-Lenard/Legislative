<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\Ordinance;
use App\Models\Resolution;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class AssistanceController extends Controller
{
    /**
     * Display a listing of the resource for users.
     */
   
    public function indexUser(Request $request)
{
    $type = $request->get('type', 'scholar'); // default tab
    $search = $request->get('search');

    $query = Assistance::query();

    // Filter by type
    if ($type) {
        $query->where('type', $type);
    }

    // Search by full name
    if ($search) {
        $query->where('full_name', 'like', '%' . $search . '%');
    }

    $assistances = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('User/Assistance', [
        'canRegister' => Route::has('register'),
        'assistances' => $assistances,
        'filters' => [
            'type' => $type,
            'search' => $search,
        ],
        'counts' => [
            'medical' => Assistance::where('type', 'medical')->count(),
            'legal' => Assistance::where('type', 'legal')->count(),
            'scholar' => Assistance::where('type', 'scholar')->count(),
        ]
    ]);
}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Assistance::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('barangay', 'like', "%{$search}%")
                ->orWhere('school', 'like', "%{$search}%");
            });
        }

        // ✅ Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // ✅ Order Latest First
        $query->latest();

        // ✅ Pagination with preserved filters
        $assistances = $query
            ->paginate(10)
            ->appends($request->only('search', 'type'));

        // ✅ Type dropdown data
        $types = Assistance::select('type')
            ->distinct()
            ->pluck('type');

        // ✅ Dashboard counts
        $totalAssistances = Assistance::count();
        $medicalCount = Assistance::where('type', 'medical')->count();
        $legalCount = Assistance::where('type', 'legal')->count();
        $scholarCount = Assistance::where('type', 'scholar')->count();

        return inertia('Admin/Assistances', [
            'assistances' => $assistances,
            'filters' => $request->only('search', 'type'),
            'types' => $types,
            'totalAssistances' => $totalAssistances,
            'medicalCount' => $medicalCount,
            'legalCount' => $legalCount,
            'scholarCount' => $scholarCount,
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:medical,legal,scholar',
            'full_name' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
        ]);

        Assistance::create($validated);

        return back()->with('success', 'Assistance record created successfully.');
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $assistance = Assistance::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|in:medical,legal,scholar',
            'full_name' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
        ]);

        $assistance->update($validated);

        return back()->with('success', 'Assistance record updated successfully.');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy($id)
    {
        $assistance = Assistance::findOrFail($id);
        $assistance->delete();

        return back()->with('success', 'Assistance record deleted successfully.');
    }


    // citizen charter
 public function citizenCharter()
{
    // =========================
    // LATEST 3 ORDINANCES
    // =========================
    $latestOrdinances = Ordinance::latest()
        ->take(3)
        ->get()
        ->map(function ($ordinance) {
            return [
                'id' => $ordinance->id,
                'ordinance_number' => $ordinance->ordinance_number,
                'title' => $ordinance->title_ordinances,
                'description' => $ordinance->description_ordinances,
            ];
        });

    // =========================
    // LATEST 3 RESOLUTIONS
    // =========================
    $latestResolutions = Resolution::latest()
        ->take(3)
        ->get()
        ->map(function ($resolution) {
            return [
                'id' => $resolution->id,
                'resolutions_number' => $resolution->resolutions_number,
                'title' => $resolution->title_resolutions,
                'description_resolutions' => $resolution->description_resolutions,
            ];
        });

    // =========================
    // LATEST 4 ASSISTANCE
    // =========================
    $latestAssistances = Assistance::latest()
        ->take(4)
        ->get()
        ->map(function ($assist) {
            return [
                'id' => $assist->id,
                'type' => ucfirst($assist->type),
                'full_name' => $assist->full_name,
                'barangay' => $assist->barangay,
                'school' => $assist->school,
                'date' => $assist->created_at->format('M d, Y'),
            ];
        });

    // =========================
    // ASSISTANCE TOTAL COUNTS
    // =========================
    $assistanceTotals = [
        'medical' => Assistance::where('type', 'medical')->count(),
        'legal'   => Assistance::where('type', 'legal')->count(),
        'scholar' => Assistance::where('type', 'scholar')->count(),
        'overall' => Assistance::count(),
    ];

    return Inertia::render('User/CitizenChart', [
        'canRegister' => Route::has('register'),

        'latestOrdinances'  => $latestOrdinances,
        'latestResolutions' => $latestResolutions,
        'latestAssistances' => $latestAssistances,
        'assistanceTotals'  => $assistanceTotals,
    ]);
}
}