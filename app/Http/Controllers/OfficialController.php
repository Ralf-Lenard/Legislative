<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class OfficialController extends Controller
{
    /**
     * Display all officials
     */

    public function indexUser()
    {
        $officials = Official::with([
            'committees' => function ($q) {
                $q->withPivot('role');
            }
        ])->get();

        $presidingOfficer = $officials->first(function ($official) {
            return stripos($official->position, 'vice') !== false;
        });

        $councilMembers = $officials
            ->reject(function ($official) {
                return stripos($official->position, 'vice') !== false;
            })
            ->values();

        return Inertia::render('SB', [
            'canRegister' => Route::has('register'),
            'presidingOfficer' => $presidingOfficer ?: null,
            'councilMembers' => $councilMembers ?: collect(),
        ]);
    }


    // admin
    public function index(Request $request)
    {
        // 1. Get filters from the request
        $search = $request->input('search');
        $committeeFilter = $request->input('committee');
        $perPage = 10; // Standard pagination limit, adjust as needed

        // 2. Build the query with filtering
        $query = Official::query()->with('committees');

        // Apply search filter (Name, Position, Main Committee)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%')
                    ->orWhere('main_committee', 'like', '%' . $search . '%');
            });
        }

        // Apply committee filter
        if ($committeeFilter) {
            // Filter officials who are part of the selected committee
            $query->whereHas('committees', function ($q) use ($committeeFilter) {
                $q->where('name', $committeeFilter);
            });
        }

        // 3. Paginate the filtered results
        $officialsPaginated = $query->latest('created_at')->paginate($perPage)->withQueryString();

        // 4. Map the paginated data (similar to your original mapping, but on the collection)
        $mappedOfficials = $officialsPaginated->getCollection()->map(function ($official) {
            return [
                'id' => $official->id,
                'name' => $official->name,
                'position' => $official->position,
                'main_committee' => $official->main_committee,
                'image' => $official->image,
                'bio' => $official->bio,
                'created_at' => $official->created_at ? $official->created_at->format('Y-m-d H:i:s') : null,
                'committees' => $official->committees->map(function ($committee) {
                    return [
                        'id' => $committee->id,
                        'name' => $committee->name,
                        'focus' => $committee->focus, // <-- here
                        'pivot' => [
                            'role' => $committee->pivot->role,
                        ],
                    ];
                }),
            ];
        });


        // Replace the default collection with the mapped collection
        $officialsPaginated->setCollection($mappedOfficials);

        // 5. Get the list of all unique committees for the filter dropdown
        // Ensure you have a Committee model linked to a 'committees' table
        $committeesList = Committee::select('id', 'name')->orderBy('name')->get();


        // 6. Return the data to Inertia
        return Inertia::render('Admin/Officials', [
            // Pass the paginated data object
            'officials' => $officialsPaginated,

            // Pass current filter values
            'filters' => [
                'search' => $search,
                'committee' => $committeeFilter,
            ],

            // Pass the full list of committees for the dropdown
            'committeesList' => $committeesList,
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
            'main_committee' => 'nullable|string|max:255',
            'image' => 'nullable|file|image|max:51200', // validate file
            'bio' => 'nullable|string',
            'committees' => 'nullable|array',
            'committees.*.name' => 'required|string|max:255',
            'committees.*.role' => 'required|string|max:255',
            'committees.*.focus' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('officials', 'public');
            }

            // Create official
            $official = Official::create([
                'name' => $request->name,
                'position' => $request->position,
                'main_committee' => $request->main_committee,
                'image' => $imagePath,
                'bio' => $request->bio,
            ]);

            // Attach committees
            if ($request->filled('committees')) {
                foreach ($request->committees as $committeeData) {
                    $committee = Committee::firstOrCreate(
                        ['name' => $committeeData['name']],
                        ['focus' => $committeeData['focus'] ?? null]
                    );

                    $official->committees()->attach($committee->id, [
                        'role' => $committeeData['role'],
                    ]);
                }
            }
        });

        return redirect()
            ->route('officials.index')
            ->with('success', 'Official created successfully');
    }

    public function update(Request $request, $id)
    {
        $official = Official::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'main_committee' => 'nullable|string|max:255',
            'image' => 'nullable|file|image|max:51200',
            'keep_image' => 'nullable|boolean',
            'bio' => 'nullable|string',
            'committees' => 'nullable|array',
            'committees.*.name' => 'required|string|max:255',
            'committees.*.role' => 'required|string|max:255',
            'committees.*.focus' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $official) {

            // Handle image
            if ($request->hasFile('image')) {
                if ($official->image) {
                    Storage::disk('public')->delete($official->image);
                }
                $imagePath = $request->file('image')->store('officials', 'public');
            } elseif ($request->filled('keep_image')) {
                $imagePath = $official->image;
            } else {
                if ($official->image) {
                    Storage::disk('public')->delete($official->image);
                }
                $imagePath = null;
            }

            // Update official
            $official->update([
                'name' => $request->name,
                'position' => $request->position,
                'main_committee' => $request->main_committee,
                'image' => $imagePath,
                'bio' => $request->bio,
            ]);

            // Sync committees with role and update focus
            if ($request->has('committees')) {
                $syncData = [];
                foreach ($request->committees as $committeeData) {
                    $committee = Committee::firstOrCreate(
                        ['name' => $committeeData['name']]
                    );

                    // Always update focus
                    $committee->focus = $committeeData['focus'] ?? $committee->focus;
                    $committee->save();

                    $syncData[$committee->id] = [
                        'role' => $committeeData['role'],
                    ];
                }
                $official->committees()->sync($syncData);
            }
        });

        return redirect()
            ->route('officials.index')
            ->with('success', 'Official updated successfully');
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
