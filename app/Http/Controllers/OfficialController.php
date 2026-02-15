<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Committee;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class OfficialController extends Controller
{
    /**
     * Display all officials
     */

    // public function indexUser()
    // {

    //     if (Auth::check() && Auth::user()->status === 'banned' && Auth::user()->usertype === 'user') {
    //         Auth::logout();
    //         return redirect('/login')->withErrors([
    //             'email' => 'Your account has been banned.'
    //         ]);
    //     }

    //     $officials = Official::with([
    //         'committees' => function ($q) {
    //             $q->withPivot('role');
    //         }
    //     ])->get();

    //     $presidingOfficer = $officials->first(function ($official) {
    //         return stripos($official->position, 'vice') !== false;
    //     });

    //     $councilMembers = $officials
    //         ->reject(function ($official) {
    //             return stripos($official->position, 'vice') !== false;
    //         })
    //         ->values();

    //     return Inertia::render('SB', [
    //         'canRegister' => Route::has('register'),
    //         'presidingOfficer' => $presidingOfficer ?: null,
    //         'councilMembers' => $councilMembers ?: collect(),
    //     ]);
    // }

    public function indexUser()
    {
        if (Auth::check() && Auth::user()->status === 'banned' && Auth::user()->usertype === 'user') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Your account has been banned.'
            ]);
        }
    
        // ✅ Get page content (single CMS row)
        $pageContent = PageContent::first();
    
        // Load all officials with committees
        $officials = Official::with([
            'committees' => fn($q) => $q->withPivot('role')
        ])
            ->orderByRaw("CASE WHEN position LIKE '%vice%' THEN 0 ELSE 1 END")
            ->orderBy('position')
            ->get();
    
        $mappedOfficials = $officials->map(function ($official) {
            return [
                'id' => $official->id,
                'name' => $official->name,
                'position' => $official->position,
                'type' => $official->type,
                'division' => $official->division,
                'main_committee' => $official->main_committee,
                'image' => $official->image,
                'bio' => $official->bio,
                'committees' => $official->type === 'official'
                    ? $official->committees->map(fn($c) => [
                        'id' => $c->id,
                        'name' => $c->name,
                        'focus' => $c->focus,
                        'pivot' => ['role' => $c->pivot->role],
                    ])
                    : collect(),
            ];
        });
    
        $presidingOfficer = $mappedOfficials
            ->first(fn($o) => stripos($o['position'], 'vice') !== false);
    
        $councilMembers = $mappedOfficials
            ->reject(fn($o) => stripos($o['position'], 'vice') !== false && $o['type'] === 'official')
            ->values();
    
        $employees = $mappedOfficials
            ->filter(fn($o) => $o['type'] === 'employee')
            ->values();
    
        return Inertia::render('User/OrganizationalChart', [
            'canRegister' => Route::has('register'),
            'presidingOfficer' => $presidingOfficer ?: null,
            'councilMembers' => $councilMembers ?: collect(),
            'employees' => $employees ?: collect(),
    
            // ✅ ADD THIS
            'organizationalChart' => $pageContent && $pageContent->organizational_chart
                ? asset('storage/' . $pageContent->organizational_chart)
                : null,
        ]);
    }
    



    // admin
    // public function index(Request $request)
    // {
    //     // 1. Get filters from the request
    //     $search = $request->input('search');
    //     $committeeFilter = $request->input('committee');
    //     $perPage = 10; // Standard pagination limit, adjust as needed

    //     // 2. Build the query with filtering
    //     $query = Official::query()->with('committees');

    //     // Apply search filter (Name, Position, Main Committee)
    //     if ($search) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'like', '%' . $search . '%')
    //                 ->orWhere('position', 'like', '%' . $search . '%')
    //                 ->orWhere('main_committee', 'like', '%' . $search . '%');
    //         });
    //     }

    //     // Apply committee filter
    //     if ($committeeFilter) {
    //         // Filter officials who are part of the selected committee
    //         $query->whereHas('committees', function ($q) use ($committeeFilter) {
    //             $q->where('name', $committeeFilter);
    //         });
    //     }

    //     // 3. Paginate the filtered results
    //     $officialsPaginated = $query->latest('created_at')->paginate($perPage)->withQueryString();

    //     // 4. Map the paginated data (similar to your original mapping, but on the collection)
    //     $mappedOfficials = $officialsPaginated->getCollection()->map(function ($official) {
    //         return [
    //             'id' => $official->id,
    //             'name' => $official->name,
    //             'position' => $official->position,
    //             'main_committee' => $official->main_committee,
    //             'image' => $official->image,
    //             'bio' => $official->bio,
    //             'created_at' => $official->created_at ? $official->created_at->format('Y-m-d H:i:s') : null,
    //             'committees' => $official->committees->map(function ($committee) {
    //                 return [
    //                     'id' => $committee->id,
    //                     'name' => $committee->name,
    //                     'focus' => $committee->focus, // <-- here
    //                     'pivot' => [
    //                         'role' => $committee->pivot->role,
    //                     ],
    //                 ];
    //             }),
    //         ];
    //     });


    //     // Replace the default collection with the mapped collection
    //     $officialsPaginated->setCollection($mappedOfficials);

    //     // 5. Get the list of all unique committees for the filter dropdown
    //     // Ensure you have a Committee model linked to a 'committees' table
    //     $committeesList = Committee::select('id', 'name')->orderBy('name')->get();


    //     // 6. Return the data to Inertia
    //     return Inertia::render('Admin/Officials', [
    //         // Pass the paginated data object
    //         'officials' => $officialsPaginated,

    //         // Pass current filter values
    //         'filters' => [
    //             'search' => $search,
    //             'committee' => $committeeFilter,
    //         ],

    //         // Pass the full list of committees for the dropdown
    //         'committeesList' => $committeesList,
    //     ]);
    // }

    public function index(Request $request)
    {
        // 1. Get filters from the request
        $search = $request->input('search');
        $committeeFilter = $request->input('committee');
        $typeFilter = $request->input('type'); // official or employee
        $divisionFilter = $request->input('division'); // only for employees
        $perPage = 10; // Adjust as needed

        // 2. Build the query with eager loading
        $query = Official::query()->with('committees');

        // Filter by type
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        // Filter by division (only applies to employees)
        if ($divisionFilter) {
            $query->where('division', $divisionFilter);
        }

        // Apply search filter (Name, Position, Main Committee)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%')
                    ->orWhere('main_committee', 'like', '%' . $search . '%');
            });
        }

        // Apply committee filter (only for officials)
        if ($committeeFilter) {
            $query->whereHas('committees', function ($q) use ($committeeFilter) {
                $q->where('name', $committeeFilter);
            });
        }

        // ===============================
        // Custom Ordering: Officials first, Vice Mayor first
        // ===============================
        $query->orderByRaw("
            CASE 
                WHEN position = 'Vice Mayor' THEN 1
                WHEN type = 'official' THEN 2
                ELSE 3
            END ASC
        ")->latest('created_at');

        // 3. Paginate results
        $officialsPaginated = $query->paginate($perPage)->withQueryString();

        // 4. Map the paginated data
        $mappedOfficials = $officialsPaginated->getCollection()->map(function ($official) {
            $data = [
                'id' => $official->id,
                'name' => $official->name,
                'position' => $official->position,
                'type' => $official->type,
                'division' => $official->division,
                'main_committee' => $official->main_committee,
                'image' => $official->image,
                'bio' => $official->bio,
                'created_at' => $official->created_at ? $official->created_at->format('Y-m-d H:i:s') : null,
            ];

            // Only map committees if type = official
            if ($official->type === 'official') {
                $data['committees'] = $official->committees->map(function ($committee) {
                    return [
                        'id' => $committee->id,
                        'name' => $committee->name,
                        'focus' => $committee->focus,
                        'pivot' => [
                            'role' => $committee->pivot->role,
                        ],
                    ];
                });
            } else {
                $data['committees'] = [];
            }

            return $data;
        });

        $officialsPaginated->setCollection($mappedOfficials);

        // 5. Get all unique committees for filter dropdown
        $committeesList = Committee::select('id', 'name')->orderBy('name')->get();

        // 6. Return to Inertia
        return Inertia::render('Admin/Officials', [
            'officials' => $officialsPaginated,
            'filters' => [
                'search' => $search,
                'committee' => $committeeFilter,
                'type' => $typeFilter,
                'division' => $divisionFilter,
            ],
            'committeesList' => $committeesList,
            'divisionOptions' => [
                'Public Library Service',
                'Legislative Research, Records & Archives',
                'Support Services',
                'Office of the SB Secretary'
            ],
        ]);
    }


    /**
     * Store a new official
     */
    // Inside OfficialController.php
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'type' => 'required|in:official,employee',
        'division' => [
            'required_if:type,employee',
            'nullable',
            'string',
            // Updated the validation list here
            'in:Public Library Service,Legislative Research, Records & Archives,Support Services,Office of the SB Secretary,N/A'
        ],
        'image' => 'nullable|image|max:51200', // 5MB limit
    ]);

    // Update this array to match the new allowed divisions
    $validDivisions = [
        'Public Library Service',
        'Legislative Research, Records & Archives',
        'Support Services',
        'Office of the SB Secretary', // Added this
        'N/A'                          // Added this
    ];

    // This was the part blocking your request!
    if ($request->type === 'employee' && !in_array($request->division, $validDivisions)) {
        return back()->withErrors(['division' => 'Please select a valid division.']);
    }

    DB::transaction(function () use ($request) {
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('officials', 'public')
            : null;

        if ($request->type === 'employee') {
            Official::create([
                'type' => 'employee',
                'name' => $request->name,
                'position' => $request->position,
                'division' => $request->division,
                'image' => $imagePath,
                'main_committee' => null,
                'bio' => null,
            ]);
        } else {
            $official = Official::create([
                'type' => 'official',
                'name' => $request->name,
                'position' => $request->position,
                'main_committee' => $request->main_committee,
                'image' => $imagePath,
                'bio' => $request->bio,
                'division' => null,
            ]);

            if ($request->filled('committees')) {
                foreach ($request->committees as $committeeData) {
                    $committee = Committee::firstOrCreate(['name' => $committeeData['name']]);
                    $official->committees()->attach($committee->id, ['role' => $committeeData['role']]);
                }
            }
        }
    });

    return redirect()->route('officials.index')->with('success', 'Record created successfully');
}

    public function update(Request $request, $id)
    {
        $official = Official::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'type' => 'required|in:official,employee',
            'main_committee' => 'nullable|string|max:255',
            'division' => [
                'required_if:type,employee',
                'nullable',
                'string',
                // Add 'Office of the SB Secretary' and 'N/A' to this list
                'in:Public Library Service,Legislative Research, Records & Archives,Support Services,Office of the SB Secretary,N/A'
            ],
            'image' => 'nullable|file|image|max:51200',
            'keep_image' => 'nullable',
            'bio' => 'nullable|string',
            'committees' => 'nullable|array',
            'committees.*.name' => 'required_if:type,official|string|max:255',
            'committees.*.role' => 'required_if:type,official|string|max:255',
        ]);

        // Validation limits for officials
        if ($request->type === 'official') {
            $existing = Official::where('position', $request->position)->where('id', '!=', $official->id);
            if ($request->position === 'Vice Mayor' && $existing->exists()) {
                return back()->withErrors(['position' => 'Only one Vice Mayor is allowed.']);
            }
            if ($request->position === 'Sangguniang Bayan Member' && $existing->count() >= 8) {
                return back()->withErrors(['position' => 'Only 8 Sangguniang Bayan Members are allowed.']);
            }
        }

        DB::transaction(function () use ($request, $official) {
            // Image handling
            if ($request->hasFile('image')) {
                if ($official->image) Storage::disk('public')->delete($official->image);
                $imagePath = $request->file('image')->store('officials', 'public');
            } elseif ($request->keep_image == '1') {
                $imagePath = $official->image;
            } else {
                if ($official->image) Storage::disk('public')->delete($official->image);
                $imagePath = null;
            }

            if ($request->type === 'employee') {
                // Update as Employee
                $official->update([
                    'type' => 'employee',
                    'name' => $request->name,
                    'position' => $request->position,
                    'division' => $request->division,
                    'image' => $imagePath,
                    'main_committee' => null,
                    'bio' => null,
                ]);
                $official->committees()->detach(); // Employees don't have committees
            } else {
                // Update as Official
                $official->update([
                    'type' => 'official',
                    'name' => $request->name,
                    'position' => $request->position,
                    'main_committee' => $request->main_committee,
                    'division' => null,
                    'image' => $imagePath,
                    'bio' => $request->bio,
                ]);

                $syncData = [];
                if ($request->has('committees')) {
                    foreach ($request->committees as $committeeData) {
                        $committee = Committee::firstOrCreate(['name' => $committeeData['name']]);
                        $syncData[$committee->id] = ['role' => $committeeData['role']];
                    }
                }
                $official->committees()->sync($syncData);
            }
        });

        return redirect()->route('officials.index')->with('success', 'Record updated successfully');
    }

    /**
     * Delete an official
     */
    public function destroy($id)
    {
        $official = Official::findOrFail($id);

        // Delete image if exists
        if ($official->image) {
            Storage::disk('public')->delete($official->image);
        }

        $official->delete();

        return redirect()
            ->route('officials.index')
            ->with('success', 'Official deleted successfully');
    }
}
