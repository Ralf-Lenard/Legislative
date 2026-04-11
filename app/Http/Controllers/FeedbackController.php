<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    /**
     * Store feedback (anonymous or logged-in)
     */
    public function store(Request $request)
    {
        $request->validate([
            // Validating the category against your migration options
            'category' => 'required|string|in:suggestion,concern,commendation,inquiry,other',
            'message'  => 'required|string|max:2000',
        ]);

        Feedback::create([
            'user_id'  => Auth::id(), // Returns null automatically if not logged in
            'category' => $request->category,
            'message'  => $request->message,
        ]);

        return back()->with('success', 'Feedback submitted successfully!');
    }

    /**
     * Admin: show all feedback
     */
  public function index(Request $request)
    {
        $query = Feedback::with('user:id,name,email');

        // ✅ Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // ✅ Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // ✅ ORDER: NEWEST → OLDEST (same as sessions style)
        $feedback = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Optional: category list for dropdown filter
        $categories = Feedback::query()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return Inertia::render('Admin/Feedback', [
            'feedback' => $feedback,
            'filters' => $request->only('search', 'category'),
            'categories' => $categories,
        ]);
    }

    /**
     * Admin: delete feedback
     */
    public function destroy(Feedback $feedback) // Used Route Model Binding for cleaner code
    {
        $feedback->delete();

        return back()->with('success', 'Feedback deleted successfully!');
    }
}