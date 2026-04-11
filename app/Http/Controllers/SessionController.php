<?php

namespace App\Http\Controllers;

use App\Models\LegislativeSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SessionController extends Controller
{

    public function indexUser(Request $request)
    {

        if (Auth::check() && Auth::user()->status === 'banned' && Auth::user()->usertype === 'user') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Your account has been banned.'
            ]);
        }

        $query = LegislativeSession::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('session_number', 'like', "%{$search}%")
                    ->orWhere('session_title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('session_type', 'like', "%{$search}%");
            });
        }

        // ✅ Year filter (date_of_session is cast as date — works correctly)
        if ($request->filled('year')) {
            $query->whereYear('date_of_session', $request->year);
        }

        // ✅ Pagination + persist filters
        $sessions = $query
            ->orderBy('date_of_session', 'desc')
            ->paginate(10)
            ->withQueryString(); // cleaner than appends()

        // ✅ Distinct years for dropdown
        $years = LegislativeSession::query()
            ->selectRaw('YEAR(date_of_session) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return Inertia::render('User/Sessions', [
            'sessions' => $sessions,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'canRegister' => Route::has('register'),
        ]);
    }

    // public function showUser($id)
    // {
    //     $session = LegislativeSession::findOrFail($id);

    //     return Inertia::render('User/SessionDetails', [
    //         'session' => [
    //             'id' => $session->id,
    //             'session_number' => $session->session_number,
    //             'session_title' => $session->session_title,
    //             'date_of_session' => $session->date_of_session,
    //             'session_type' => $session->session_type,
    //             'summary' => $session->summary,

    //             // 🔥 FIX HERE
    //             'images' => collect($session->images ?? [])->map(function ($image) {
    //                 return [
    //                     'url' => asset('storage/' . $image['file_path']),
    //                     'alt' => $image['alt'] ?? 'Session Image',
    //                 ];
    //             })->values(),

    //             'created_at' => $session->created_at,
    //         ],
    //         'canRegister' => Route::has('register'),
    //     ]);
    // }

   public function showUser($id)
{
    $session = LegislativeSession::findOrFail($id);

    return Inertia::render('User/SessionDetails', [
        'session' => [
            'id' => $session->id,
            'session_number' => $session->session_number,
            'session_title' => $session->session_title,
            'date_of_session' => $session->date_of_session,
            'session_type' => $session->session_type,
            'summary' => $session->summary,

            // Images
            'images' => collect($session->images ?? [])->map(function ($image) {
                if (!$image || !isset($image['file_path'])) {
                    return null;
                }
                return [
                    'url' => asset('storage/' . $image['file_path']),
                    'alt' => $image['alt'] ?? 'Session Image',
                ];
            })->filter()->values(),

            // ✅ Videos (same logic)
            'videos' => collect($session->videos ?? [])->map(function ($video) {
                if (!$video || !isset($video['file_path'])) {
                    return null;
                }
                return [
                    'url' => asset('storage/' . $video['file_path']),
                    'title' => $video['title'] ?? 'Session Video',
                ];
            })->filter()->values(),

            'created_at' => $session->created_at,
        ],
        'canRegister' => Route::has('register'),
    ]);
}


    /**
     * Display a listing of sessions.
     */
    // public function index(Request $request)
    // {
    //     $query = LegislativeSession::query();

    //     // ✅ Search filter
    //     if ($request->filled('search')) {
    //         $search = $request->search;

    //         $query->where(function ($q) use ($search) {
    //             $q->where('session_number', 'like', "%{$search}%")
    //                 ->orWhere('session_title', 'like', "%{$search}%")
    //                 ->orWhere('summary', 'like', "%{$search}%")
    //                 ->orWhere('session_type', 'like', "%{$search}%");
    //         });
    //     }

    //     // ✅ Year filter (date_of_session is cast as date — works correctly)
    //     if ($request->filled('year')) {
    //         $query->whereYear('date_of_session', $request->year);
    //     }

    //     // ✅ Pagination + persist filters
    //     $sessions = $query
    //         ->orderBy('date_of_session', 'desc')
    //         ->paginate(10)
    //         ->withQueryString(); // cleaner than appends()

    //     // ✅ Distinct years for dropdown
    //     $years = LegislativeSession::query()
    //         ->selectRaw('YEAR(date_of_session) as year')
    //         ->distinct()
    //         ->orderByDesc('year')
    //         ->pluck('year');

    //     return Inertia::render('Admin/Sessions', [
    //         'sessions' => $sessions,
    //         'filters' => $request->only('search', 'year'),
    //         'years' => $years,
    //         'canRegister' => Route::has('register'),
    //     ]);
    // }

    public function index(Request $request)
    {
        $query = LegislativeSession::query();

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('session_number', 'like', "%{$search}%")
                    ->orWhere('session_title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('session_type', 'like', "%{$search}%");
            });
        }

        // ✅ Year filter
        if ($request->filled('year')) {
            $query->whereYear('date_of_session', $request->year);
        }

        // ✅ ORDER: NEWEST → OLDEST
        $sessions = $query
            ->orderByDesc('date_of_session') // ← THIS is the key line
            ->paginate(10)
            ->withQueryString();

        // ✅ Year dropdown values
        $years = LegislativeSession::query()
            ->selectRaw('YEAR(date_of_session) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return Inertia::render('Admin/Sessions', [
            'sessions' => $sessions,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'canRegister' => Route::has('register'),
        ]);
    }

    /**
     * Store a newly created session.
     */
    // public function store(Request $request)
    // {
    //     // Capture the validated data into a variable
    //     $validated = $request->validate([
    //         'session_number' => 'required|unique:legislative_sessions,session_number',
    //         'session_title' => 'required|string|max:255',
    //         'date_of_session' => 'required|date',
    //         'session_type' => 'required|in:Regular,Special',
    //         'summary' => 'required|string',
    //         'images' => 'nullable|array',
    //         'images.*.file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    //         'images.*.alt' => 'required|string|max:255',
    //     ]);

    //     $images = [];
    //     if ($request->has('images')) {
    //         foreach ($request->images as $img) {
    //             $path = $img['file']->store('sessions', 'public');
    //             $images[] = [
    //                 'file_path' => $path,
    //                 'alt' => $img['alt'],
    //             ];
    //         }
    //     }

    //     // Use the $validated variable instead of calling $request->validated()
    //     LegislativeSession::create(array_merge($validated, ['images' => $images]));

    //     return redirect()
    //         ->route('sessions.index')
    //         ->with('success', 'Session created successfully');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_number' => 'required|unique:legislative_sessions,session_number',
            'session_title' => 'required|string|max:255',
            'date_of_session' => 'required|date',
            'session_type' => 'required|in:Regular,Special',
            'summary' => 'required|string',

            // Images
            'images' => 'nullable|array',
            'images.*.file' => 'required|image|mimes:jpg,jpeg,png,webp|max:51200',
            'images.*.alt' => 'required|string|max:255',

            // ✅ Videos (same structure as images)
            'videos' => 'nullable|array',
            'videos.*.file' => 'required|file|mimes:mp4,mov,avi,webm|max:512000', // 10MB max
            'videos.*.title' => 'required|string|max:255',
        ]);

        // Handle Images
        $images = [];
        if ($request->has('images')) {
            foreach ($request->images as $img) {
                $path = $img['file']->store('sessions/images', 'public');
                $images[] = [
                    'file_path' => $path,
                    'alt' => $img['alt'],
                ];
            }
        }

        // ✅ Handle Videos (same logic)
        $videos = [];
        if ($request->has('videos')) {
            foreach ($request->videos as $vid) {
                $path = $vid['file']->store('sessions/videos', 'public');
                $videos[] = [
                    'file_path' => $path,
                    'title' => $vid['title'],
                ];
            }
        }

        LegislativeSession::create(array_merge($validated, [
            'images' => $images,
            'videos' => $videos, // ✅ save videos
        ]));

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Session created successfully');
    }

    // public function update(Request $request, $id)
    // {
    //     $session = LegislativeSession::findOrFail($id);

    //     // Capture the validated data into a variable
    //     $validated = $request->validate([
    //         'session_number' => 'required|unique:legislative_sessions,session_number,' . $id,
    //         'session_title' => 'required|string|max:255',
    //         'date_of_session' => 'required|date',
    //         'session_type' => 'required|in:Regular,Special',
    //         'summary' => 'required|string',
    //         'images' => 'nullable|array',
    //         'images.*.file' => 'required_with:images|image|mimes:jpg,jpeg,png,webp|max:2048',
    //         'images.*.alt' => 'required_with:images|string|max:255',
    //         'existing_images' => 'nullable|array',
    //     ]);

    //     $finalImages = [];

    //     // 1. Keep selected existing images
    //     if ($request->has('existing_images')) {
    //         foreach ($request->existing_images as $img) {
    //             $finalImages[] = [
    //                 'file_path' => $img['file_path'],
    //                 'alt' => $img['alt'] ?? '',
    //             ];
    //         }
    //     }

    //     // 2. Add newly uploaded images
    //     if ($request->has('images')) {
    //         foreach ($request->images as $imgData) {
    //             $path = $imgData['file']->store('sessions', 'public');
    //             $finalImages[] = [
    //                 'file_path' => $path,
    //                 'alt' => $imgData['alt'],
    //             ];
    //         }
    //     }

    //     // 3. Update using the $validated data and merged images
    //     $session->update(array_merge($validated, [
    //         'images' => $finalImages
    //     ]));

    //     return redirect()
    //         ->route('sessions.index')
    //         ->with('success', 'Session updated successfully');
    // }

    public function update(Request $request, $id)
    {
        $session = LegislativeSession::findOrFail($id);

        $validated = $request->validate([
            'session_number' => 'required|unique:legislative_sessions,session_number,' . $id,
            'session_title' => 'required|string|max:255',
            'date_of_session' => 'required|date',
            'session_type' => 'required|in:Regular,Special',
            'summary' => 'required|string',

            // Images
            'images' => 'nullable|array',
            'images.*.file' => 'required_with:images|image|mimes:jpg,jpeg,png,webp|max:51200',
            'images.*.alt' => 'required_with:images|string|max:255',
            'existing_images' => 'nullable|array',

            // ✅ Videos
            'videos' => 'nullable|array',
            'videos.*.file' => 'required_with:videos|file|mimes:mp4,mov,avi,webm|max:512000',
            'videos.*.title' => 'required_with:videos|string|max:255',
            'existing_videos' => 'nullable|array',
        ]);

        // ======================
        // IMAGES
        // ======================
        $finalImages = [];

        // 1. Keep selected existing images
        if ($request->has('existing_images')) {
            foreach ($request->existing_images as $img) {
                $finalImages[] = [
                    'file_path' => $img['file_path'],
                    'alt' => $img['alt'] ?? '',
                ];
            }
        }

        // 2. Add newly uploaded images
        if ($request->has('images')) {
            foreach ($request->images as $imgData) {
                $path = $imgData['file']->store('sessions/images', 'public');
                $finalImages[] = [
                    'file_path' => $path,
                    'alt' => $imgData['alt'],
                ];
            }
        }

        // ======================
        // ✅ VIDEOS
        // ======================
        $finalVideos = [];

        // 1. Keep selected existing videos
        if ($request->has('existing_videos')) {
            foreach ($request->existing_videos as $vid) {
                $finalVideos[] = [
                    'file_path' => $vid['file_path'],
                    'title' => $vid['title'] ?? '',
                ];
            }
        }

        // 2. Add newly uploaded videos
        if ($request->has('videos')) {
            foreach ($request->videos as $vidData) {
                $path = $vidData['file']->store('sessions/videos', 'public');
                $finalVideos[] = [
                    'file_path' => $path,
                    'title' => $vidData['title'],
                ];
            }
        }

        // ======================
        // UPDATE
        // ======================
        $session->update(array_merge($validated, [
            'images' => $finalImages,
            'videos' => $finalVideos, // ✅ include videos
        ]));

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Session updated successfully');
    }
    /**
     * Remove the specified session.
     */
    public function destroy($id)
    {
        $session = LegislativeSession::findOrFail($id);
        $session->delete();

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Session deleted successfully!');
    }
}
