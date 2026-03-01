<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    /**
     * Show all users for management with filters and stats
     */
   public function index(Request $request)
    {
        // 1. Start query
        $query = User::whereIn('usertype', ['user', 'admin']);

        // 2. Apply Search Filter (Name or Email)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Apply Role Filter (admin / user)
        if ($request->filled('role')) {
            $query->where('usertype', $request->role);
        }

        // 4. Get Paginated Results
        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 5. Return to Inertia
        return Inertia::render('SuperAdmin/Users', [
            'users' => $users,
            'filters' => $request->only(['search', 'role']),
            
            // Stats
            'totalUsers' => User::where('usertype', 'user')->count(),
            'activeUsersCount' => User::where('status', 'active')->count(),
            'adminUsersCount' => User::where('usertype', 'admin')->count(),
            'newUsersThisMonth' => User::whereMonth('created_at', Carbon::now()->month)->count(),

            // These are the actual ROLES for the dropdown
            'roles' => ['admin', 'user'], 
        ]);
    }

    /**
     * Promote a regular user to admin
     */
    public function promoteToAdmin($id)
    {
        $user = User::findOrFail($id);
    
        // Prevent touching super admin
        if ($user->usertype === 'super_admin') {
            return redirect()->back()->with('error', 'Super admin role cannot be changed.');
        }
    
        if ($user->usertype !== 'user') {
            return redirect()->back()->with('error', 'Only regular users can be promoted.');
        }
    
        $user->update([
            'usertype' => 'admin',
        ]);
    
        return redirect()->back()->with('success', 'User promoted to admin successfully.');
    }

    public function promoteToUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent touching super admin
        if ($user->usertype === 'super_admin') {
            return redirect()->back()->with('error', 'Super admin role cannot be changed.');
        }

        if ($user->usertype !== 'admin') {
            return redirect()->back()->with('error', 'Only admins can be demoted.');
        }

        $user->update([
            'usertype' => 'user',
        ]);

        return redirect()->back()->with('success', 'Admin demoted to user successfully.');
    }

    

    /**
     * Ban a user
     */
    public function banUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->usertype !== 'user') {
            return redirect()->back()->with('error', 'Only regular users can be banned.');
        }

        $user->status = 'banned';
        $user->save();

        return redirect()->back()->with('success', 'User banned successfully.');
    }

    /**
     * Unban a user
     */
    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'User unbanned successfully.');
    }

    /**
     * Delete a user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted permanently.');
    }
}