<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OrdinanceDownloadRequest;
use App\Models\ResolutionDownloadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Show the logged-in user's profile
     */
    public function edit()
    {
        return Inertia::render('User/Profile', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|max:51200',
            'contact_number' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);

        $user->fill($request->only(['name', 'email', 'contact_number', 'birthdate', 'address']));

        if ($request->hasFile('profile_photo')) {
            // Delete old photo from disk if it exists
            if ($user->profile_photo) {
                // Clean the path to ensure we are deleting from the 'public' disk root
                $cleanOldPath = str_replace('/storage/', '', $user->profile_photo);
                Storage::disk('public')->delete($cleanOldPath);
            }

            // Store file and get path: "profile-photos/abc.jpg"
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Save ONLY the relative path
            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()
            ->route('user.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }


    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Password updated successfully.');
    }

    public function editAdmin()
    {
        $admin = Auth::user();

        return Inertia::render('Admin/Profile', [
            'admin' => $admin,
        ]);
    }

    /**
     * Update admin profile
     */
    public function updateAdmin(Request $request)
    {
        $admin = Auth::user();

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
                'profile_photo' => 'nullable|image|max:51200',
                'contact_number' => 'nullable|digits:11',
                'birthdate' => 'nullable|date',
                'address' => 'nullable|string|max:255',
            ],
            [
                'contact_number.digits' => 'Contact number must be exactly 11 digits.',
            ]
        );

        $admin->fill($request->only([
            'name',
            'email',
            'contact_number',
            'birthdate',
            'address'
        ]));

        if ($request->hasFile('profile_photo')) {
            if ($admin->profile_photo) {
                $cleanOldPath = str_replace('/storage/', '', $admin->profile_photo);
                Storage::disk('public')->delete($cleanOldPath);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $admin->profile_photo = $path;
        }

        $admin->save();


        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }


      public function documentRequest()
    {
        $user = Auth::user();

        // =========================
        // ORDINANCE REQUESTS
        // =========================
        $ordinanceRequests = OrdinanceDownloadRequest::with('ordinance:id,title_ordinances')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($request) {
                return [
                    'type'        => 'Ordinance',
                    'title'       => optional($request->ordinance)->title_ordinances ?? 'Unknown',
                    'status'      => $request->status,
                    'purpose'     => $request->purpose,
                    'created_at'  => $request->created_at->format('M d, Y h:i A'),
                ];
            });

        // =========================
        // RESOLUTION REQUESTS
        // =========================
        $resolutionRequests = ResolutionDownloadRequest::with('resolution:id,title_resolutions')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($request) {
                return [
                    'type'        => 'Resolution',
                    'title'       => optional($request->resolution)->title_resolutions ?? 'Unknown',
                    'status'      => $request->status,
                    'purpose'     => $request->purpose,
                    'created_at'  => $request->created_at->format('M d, Y h:i A'),
                ];
            });

        // =========================
        // MERGE BOTH
        // =========================
        $allRequests = $ordinanceRequests
            ->merge($resolutionRequests)
            ->sortByDesc('created_at')
            ->values();

        return Inertia::render('User/DocumentRequests', [
            'requests' => $allRequests,
        ]);
    }
}
