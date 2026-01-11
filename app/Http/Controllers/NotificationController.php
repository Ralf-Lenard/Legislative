<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    // Show all notifications for authenticated user
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }
    // Mark a single notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['is_read' => now()]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    
        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    // API to get unread notification count
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->whereNull('is_read')
            ->count();

        return response()->json(['count' => $count]);
    }

    // Delete a single notification
    public function delete($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }

    // Clear all notifications for the authenticated user
    public function clearAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'All notifications cleared']);
    }

    // public function AdminStaffNotif()
    // {
    //     $user = auth()->user();

    //     $notifications = Notification::where('user_id', $user->id)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     if ($user->usertype === 'staff') {
    //         return Inertia::render('Staff/Notifications', [
    //             'notifications' => $notifications
    //         ]);
    //     } else {
    //         return Inertia::render('Admin/Notifications', [
    //             'notifications' => $notifications
    //         ]);
    //     }
    // }
}