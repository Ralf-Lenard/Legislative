<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

use App\Models\Ordinance;
use App\Models\Resolution;
use App\Models\OrdinanceDownloadRequest;
use App\Models\PageContent;
use App\Models\ResolutionDownloadRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    // public function indexAdmin()
    // {
    //     $user = Auth::user();

    //     // ========================
    //     // ALLOWED ROLES
    //     // ========================
    //     if (!in_array($user->usertype, ['admin', 'super_admin'])) {
    //         abort(403);
    //     }

    //     // ========================
    //     // SHARED STATS (ADMIN + SUPER ADMIN)
    //     // ========================
    //     $totalOrdinances  = Ordinance::count();
    //     $totalResolutions = Resolution::count();

    //     $ordinanceRequestStatus = [
    //         'pending'  => OrdinanceDownloadRequest::where('status', 'pending')->count(),
    //         'approved' => OrdinanceDownloadRequest::where('status', 'approved')->count(),
    //         'rejected' => OrdinanceDownloadRequest::where('status', 'rejected')->count(),
    //     ];

    //     $resolutionRequestStatus = [
    //         'pending'  => ResolutionDownloadRequest::where('status', 'pending')->count(),
    //         'approved' => ResolutionDownloadRequest::where('status', 'approved')->count(),
    //         'rejected' => ResolutionDownloadRequest::where('status', 'rejected')->count(),
    //     ];

    //     // ========================
    //     // MONTH LABELS
    //     // ========================
    //     $months = collect(range(1, 12))->map(
    //         fn($m) =>
    //         Carbon::create()->month($m)->format('M')
    //     );

    //     // ========================
    //     // MONTHLY REQUESTS (SHARED)
    //     // ========================
    //     $ordinanceMonthly = OrdinanceDownloadRequest::selectRaw(
    //         'MONTH(created_at) as month, COUNT(*) as total'
    //     )->groupBy('month')->pluck('total', 'month');

    //     $resolutionMonthly = ResolutionDownloadRequest::selectRaw(
    //         'MONTH(created_at) as month, COUNT(*) as total'
    //     )->groupBy('month')->pluck('total', 'month');

    //     $monthlyRequests = $months->map(function ($monthName, $index) use ($ordinanceMonthly, $resolutionMonthly) {
    //         $month = $index + 1;

    //         return [
    //             'month'       => $monthName,
    //             'ordinances'  => $ordinanceMonthly[$month] ?? 0,
    //             'resolutions' => $resolutionMonthly[$month] ?? 0,
    //         ];
    //     });

    //     // ========================
    //     // SUPER ADMIN ONLY STATS
    //     // ========================
    //     $userStats   = null;
    //     $userMonthly = null;

    //     if ($user->usertype === 'super_admin') {
    //         // BASIC USER COUNTS
    //         $userStats = [
    //             'totalUsers' => User::count(),
    //             'admins'     => User::where('usertype', 'admin')->count(),
    //             'users'      => User::where('usertype', 'user')->count(),
    //         ];

    //         // MONTHLY USER REGISTRATIONS
    //         $userMonthlyRaw = User::selectRaw(
    //             'MONTH(created_at) as month, COUNT(*) as total'
    //         )->groupBy('month')->pluck('total', 'month');

    //         $userMonthly = $months->map(function ($monthName, $index) use ($userMonthlyRaw) {
    //             $month = $index + 1;

    //             return [
    //                 'month' => $monthName,
    //                 'total' => $userMonthlyRaw[$month] ?? 0,
    //             ];
    //         });
    //     }

    //     // ========================
    //     // RESPONSE
    //     // ========================
    //     return Inertia::render('Admin/Dashboard', [
    //         'role' => $user->usertype,

    //         // SHARED
    //         'stats' => [
    //             'totalOrdinances'  => $totalOrdinances,
    //             'totalResolutions' => $totalResolutions,
    //         ],
    //         'ordinanceRequestStatus'  => $ordinanceRequestStatus,
    //         'resolutionRequestStatus' => $resolutionRequestStatus,
    //         'monthlyRequests'         => $monthlyRequests,

    //         // SUPER ADMIN ONLY
    //         'userStats'   => $userStats,
    //         'userMonthly' => $userMonthly,
    //     ]);
    // }


    public function indexAdmin()
{
    $user = Auth::user();

    // ========================
    // ALLOWED ROLES
    // ========================
    if (!in_array($user->usertype, ['admin', 'super_admin'])) {
        abort(403);
    }

    // ========================
    // SHARED STATS (ADMIN + SUPER ADMIN)
    // ========================
    $totalOrdinances  = Ordinance::count();
    $totalResolutions = Resolution::count();

    $ordinanceRequestStatus = [
        'pending'  => OrdinanceDownloadRequest::where('status', 'pending')->count(),
        'approved' => OrdinanceDownloadRequest::where('status', 'approved')->count(),
        'rejected' => OrdinanceDownloadRequest::where('status', 'rejected')->count(),
    ];

    $resolutionRequestStatus = [
        'pending'  => ResolutionDownloadRequest::where('status', 'pending')->count(),
        'approved' => ResolutionDownloadRequest::where('status', 'approved')->count(),
        'rejected' => ResolutionDownloadRequest::where('status', 'rejected')->count(),
    ];

    // ========================
    // MONTH LABELS
    // ========================
    $months = collect(range(1, 12))->map(
        fn($m) => Carbon::create()->month($m)->format('M')
    );

    // ========================
    // MONTHLY REQUESTS
    // ========================
    $ordinanceMonthly = OrdinanceDownloadRequest::selectRaw(
            'MONTH(created_at) as month, COUNT(*) as total'
        )
        ->groupBy('month')
        ->pluck('total', 'month');

    $resolutionMonthly = ResolutionDownloadRequest::selectRaw(
            'MONTH(created_at) as month, COUNT(*) as total'
        )
        ->groupBy('month')
        ->pluck('total', 'month');

    $monthlyRequests = $months->map(function ($monthName, $index) use ($ordinanceMonthly, $resolutionMonthly) {
        $month = $index + 1;

        return [
            'month'       => $monthName,
            'ordinances'  => $ordinanceMonthly[$month] ?? 0,
            'resolutions' => $resolutionMonthly[$month] ?? 0,
        ];
    });

    // ========================
    // MOST REQUESTED ORDINANCES
    // ========================
    $topOrdinances = OrdinanceDownloadRequest::selectRaw(
            'ordinance_id, COUNT(*) as total'
        )
        ->groupBy('ordinance_id')
        ->orderByDesc('total')
        ->with('ordinance:id,title_ordinances')
        ->take(5)
        ->get()
        ->map(function ($item) {
            return [
                'title' => optional($item->ordinance)->title_ordinances ?? 'Unknown',
                'total' => $item->total,
            ];
        });

    // ========================
    // MOST REQUESTED RESOLUTIONS
    // ========================
    $topResolutions = ResolutionDownloadRequest::selectRaw(
            'resolution_id, COUNT(*) as total'
        )
        ->groupBy('resolution_id')
        ->orderByDesc('total')
        ->with('resolution:id,title_resolutions')
        ->take(5)
        ->get()
        ->map(function ($item) {
            return [
                'title' => optional($item->resolution)->title_resolutions ?? 'Unknown',
                'total' => $item->total,
            ];
        });

    // ========================
    // SUPER ADMIN ONLY STATS
    // ========================
    $userStats   = null;
    $userMonthly = null;

    if ($user->usertype === 'super_admin') {

        $userStats = [
            'totalUsers' => User::count(),
            'admins'     => User::where('usertype', 'admin')->count(),
            'users'      => User::where('usertype', 'user')->count(),
        ];

        $userMonthlyRaw = User::selectRaw(
                'MONTH(created_at) as month, COUNT(*) as total'
            )
            ->groupBy('month')
            ->pluck('total', 'month');

        $userMonthly = $months->map(function ($monthName, $index) use ($userMonthlyRaw) {
            $month = $index + 1;

            return [
                'month' => $monthName,
                'total' => $userMonthlyRaw[$month] ?? 0,
            ];
        });
    }

    // ========================
    // RESPONSE
    // ========================
    return Inertia::render('Admin/Dashboard', [
        'role' => $user->usertype,

        // SHARED
        'stats' => [
            'totalOrdinances'  => $totalOrdinances,
            'totalResolutions' => $totalResolutions,
        ],
        'ordinanceRequestStatus'  => $ordinanceRequestStatus,
        'resolutionRequestStatus' => $resolutionRequestStatus,
        'monthlyRequests'         => $monthlyRequests,

        // NEW GRAPH DATA
        'topOrdinances'   => $topOrdinances,
        'topResolutions'  => $topResolutions,

        // SUPER ADMIN
        'userStats'   => $userStats,
        'userMonthly' => $userMonthly,
    ]);
}

    // public function welcome()
    // {
    //     // Fetch the Vice Mayor
    //     $viceMayor = Official::where('position', 'LIKE', '%Vice Mayor%')->first();

    //     // If logged in, route based on user type
    //     if (Auth::check()) {
    //         $user = Auth::user();

    //         // If normal user → Home page
    //         if ($user->usertype === 'user') {
    //             return Inertia::render('Home', [
    //                 'canRegister' => Route::has('register'),
    //                 'viceMayor' => $viceMayor ? [
    //                     'name' => $viceMayor->name,
    //                     'image' => $viceMayor->image ? asset('storage/' . $viceMayor->image) : null,
    //                 ] : null,
    //             ]);
    //         }

    //         // Admin or other → Dashboard (optional)
    //         return redirect()->route('dashboard');
    //     }

    //     // Guest users see Welcome page
    //     return Inertia::render('Welcome', [
    //         'canRegister' => Route::has('register'),
    //         'viceMayor' => $viceMayor ? [
    //             'name' => $viceMayor->name,
    //             'image' => $viceMayor->image ? asset('storage/' . $viceMayor->image) : null,
    //         ] : null,
    //     ]);
    // }

    public function welcome()
    {

         // 🔥 Auto-logout banned users
        if (Auth::check() && Auth::user()->status === 'banned' && Auth::user()->usertype === 'user') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Your account has been banned.'
            ]);
        }
        // Fetch Vice Mayor
        $viceMayor = Official::where('position', 'LIKE', '%Vice Mayor%')->first();

        // Fetch Page Content (single row CMS)
        $pageContent = PageContent::first();

        $content = $pageContent ? [
            'welcome_image' => $pageContent->welcome_image
                ? asset('storage/' . $pageContent->welcome_image)
                : null,

            'about_us_image' => $pageContent->about_us_image
                ? asset('storage/' . $pageContent->about_us_image)
                : null,

            'organizational_chart' => $pageContent->organizational_chart
                ? asset('storage/' . $pageContent->organizational_chart)
                : null,

            'vice_mayor_image' => $pageContent->vice_mayor_image
                ? asset('storage/' . $pageContent->vice_mayor_image)
                : null,

            'vice_mayor_message' => $pageContent->vice_mayor_message,
            'about_us' => $pageContent->about_us,
            'mission' => $pageContent->mission,
            'vision' => $pageContent->vision,

            'gallery_images' => collect($pageContent->gallery_images)->map(
                fn($img) => asset('storage/' . $img)
            ),
        ] : null;

        // Redirect admins
        if (Auth::check() && Auth::user()->usertype !== 'user') {
            return redirect()->route('dashboard');
        }

        // Guests + users → same Welcome page
        return Inertia::render('Welcome', [
            'canRegister' => Route::has('register'),
            'viceMayor' => $viceMayor ? [
                'name' => $viceMayor->name,
                'image' => $viceMayor->image
                    ? asset('storage/' . $viceMayor->image)
                    : null,
            ] : null,
            'pageContent' => $content,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'welcome_image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
            'about_us_image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
            'organizational_chart' => 'nullable|image|mimes:jpg,jpeg,png|max:51200', // added
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
    
            'vice_mayor_message' => 'nullable|string',
            'about_us' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
        ]);
    
        $data = $request->only([
            'vice_mayor_message',
            'about_us',
            'mission',
            'vision',
        ]);
    
        // Upload single images (including organizational chart)
        foreach (['welcome_image', 'about_us_image', 'organizational_chart'] as $image) {
            if ($request->hasFile($image)) {
                $data[$image] = $request->file($image)->store('page', 'public');
            }
        }
    
        // Upload gallery images
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('page/gallery', 'public');
            }
            $data['gallery_images'] = $gallery;
        }
    
        PageContent::create($data);
    
        return redirect()
            ->route('admin.IndexAdminPageContent')
            ->with('success', 'Page content saved successfully.');
    }
    
    
    // public function update(Request $request, $id)
    // {
    //     $content = PageContent::findOrFail($id);
    
    //     $request->validate([
    //         'welcome_image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
    //         'about_us_image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
    //         'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
    
    //         'vice_mayor_message' => 'nullable|string',
    //         'about_us' => 'nullable|string',
    //         'mission' => 'nullable|string',
    //         'vision' => 'nullable|string',
    
    //         // delete flags
    //         'delete_welcome_image' => 'nullable|boolean',
    //         'delete_about_us_image' => 'nullable|boolean',
    //         'delete_gallery_images' => 'nullable|array',
    //     ]);
    
    //     $data = $request->only([
    //         'vice_mayor_message',
    //         'about_us',
    //         'mission',
    //         'vision',
    //     ]);
    
    //     /* =============================
    //      | DELETE SINGLE IMAGES
    //      ============================= */
    
    //     if ($request->boolean('delete_welcome_image') && $content->welcome_image) {
    //         Storage::disk('public')->delete($content->welcome_image);
    //         $data['welcome_image'] = null;
    //     }
    
    //     if ($request->boolean('delete_about_us_image') && $content->about_us_image) {
    //         Storage::disk('public')->delete($content->about_us_image);
    //         $data['about_us_image'] = null;
    //     }
    
    //     /* =============================
    //      | REPLACE SINGLE IMAGES
    //      ============================= */
    
    //     foreach (['welcome_image', 'about_us_image'] as $image) {
    //         if ($request->hasFile($image)) {
    //             if ($content->$image) {
    //                 Storage::disk('public')->delete($content->$image);
    //             }
    //             $data[$image] = $request->file($image)->store('page', 'public');
    //         }
    //     }
    
    //     /* =============================
    //      | DELETE SELECTED GALLERY IMAGES
    //      ============================= */
    
    //     // Inside your Controller Update method
    //     $gallery = $content->gallery_images ?? [];

    //     if ($request->filled('delete_gallery_images')) {
    //         foreach ($request->delete_gallery_images as $img) {
    //             // 1. Delete the physical file
    //             Storage::disk('public')->delete($img);
                
    //             // 2. Remove from the local array variable
    //             $gallery = array_values(array_filter($gallery, fn($item) => $item !== $img));
    //         }
    //     }

    //     // 3. Update the data array before saving
    //     $data['gallery_images'] = $gallery;
    //     /* =============================
    //      | APPEND NEW GALLERY IMAGES
    //      ============================= */
    
    //     if ($request->hasFile('gallery_images')) {
    //         foreach ($request->file('gallery_images') as $image) {
    //             $gallery[] = $image->store('page/gallery', 'public');
    //         }
    //     }
    
    //     $data['gallery_images'] = $gallery;
    
    //     $content->update($data);
    
    //     return redirect()
    //         ->route('admin.IndexAdminPageContent')
    //         ->with('success', 'Page content updated successfully.');
    // }

    public function update(Request $request, $id)
    {
        $content = PageContent::findOrFail($id);

        $request->validate([
            'welcome_image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
            'about_us_image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
            'organizational_chart' => 'nullable|image|mimes:jpg,jpeg,png|max:51200', // added
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',

            'vice_mayor_message' => 'nullable|string',
            'about_us' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',

            // delete flags
            'delete_welcome_image' => 'nullable|boolean',
            'delete_about_us_image' => 'nullable|boolean',
            'delete_organizational_chart' => 'nullable|boolean', // added
            'delete_gallery_images' => 'nullable|array',
        ]);

        $data = $request->only([
            'vice_mayor_message',
            'about_us',
            'mission',
            'vision',
        ]);

        /* =============================
        | DELETE SINGLE IMAGES
        ============================= */

        if ($request->boolean('delete_welcome_image') && $content->welcome_image) {
            Storage::disk('public')->delete($content->welcome_image);
            $data['welcome_image'] = null;
        }

        if ($request->boolean('delete_about_us_image') && $content->about_us_image) {
            Storage::disk('public')->delete($content->about_us_image);
            $data['about_us_image'] = null;
        }

        if ($request->boolean('delete_organizational_chart') && $content->organizational_chart) {
            Storage::disk('public')->delete($content->organizational_chart);
            $data['organizational_chart'] = null;
        }

        /* =============================
        | REPLACE SINGLE IMAGES
        ============================= */

        foreach (['welcome_image', 'about_us_image', 'organizational_chart'] as $image) {
            if ($request->hasFile($image)) {
                if ($content->$image) {
                    Storage::disk('public')->delete($content->$image);
                }
                $data[$image] = $request->file($image)->store('page', 'public');
            }
        }

        /* =============================
        | DELETE SELECTED GALLERY IMAGES
        ============================= */

        $gallery = $content->gallery_images ?? [];

        if ($request->filled('delete_gallery_images')) {
            foreach ($request->delete_gallery_images as $img) {
                // 1. Delete the physical file
                Storage::disk('public')->delete($img);

                // 2. Remove from the local array
                $gallery = array_values(array_filter($gallery, fn($item) => $item !== $img));
            }
        }

        /* =============================
        | APPEND NEW GALLERY IMAGES
        ============================= */

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('page/gallery', 'public');
            }
        }

        $data['gallery_images'] = $gallery;

        /* =============================
        | UPDATE THE CONTENT
        ============================= */
        $content->update($data);

        return redirect()
            ->route('admin.IndexAdminPageContent')
            ->with('success', 'Page content updated successfully.');
    }

    
    
    public function IndexAdminPageContent()
    {
        // Fetch single CMS record
        $pageContent = PageContent::first();

        return Inertia::render('Admin/PageContent', [
            'pageContent' => $pageContent ? [
                'id' => $pageContent->id,

                'welcome_image' => $pageContent->welcome_image
                    ? asset('storage/' . $pageContent->welcome_image)
                    : null,

                'about_us_image' => $pageContent->about_us_image
                    ? asset('storage/' . $pageContent->about_us_image)
                    : null,

                'vice_mayor_image' => $pageContent->vice_mayor_image
                    ? asset('storage/' . $pageContent->vice_mayor_image)
                    : null,

                'organizational_chart' => $pageContent->organizational_chart
                    ? asset('storage/' . $pageContent->organizational_chart)
                    : null,

                'vice_mayor_message' => $pageContent->vice_mayor_message,
                'about_us' => $pageContent->about_us,
                'mission' => $pageContent->mission,
                'vision' => $pageContent->vision,

                'gallery_images' => collect($pageContent->gallery_images)->map(
                    fn($img) => asset('storage/' . $img)
                ),
            ] : null,
        ]);
    }
}
