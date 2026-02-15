<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function indexUser(Request $request)
    {
        // 🚫 Ban check
        if (Auth::check() && Auth::user()->status === 'banned' && Auth::user()->usertype === 'user') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Your account has been banned.'
            ]);
        }

        $query = Book::query();

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 📅 FILTER BY YEAR
        if ($request->filled('year')) {
            $query->where('published_year', $request->year);
        }

        // 📄 PAGINATION
        $books = $query->paginate(12)->appends($request->only('search', 'year'));

        // 🔥 GET DISTINCT YEARS FOR DROPDOWN
        $years = Book::select('published_year')
            ->whereNotNull('published_year')
            ->orderBy('published_year', 'desc')
            ->pluck('published_year')
            ->unique()
            ->values();

        return inertia('User/Library', [
            'books' => $books,
            'filters' => $request->only('search', 'year'),
            'years' => $years,
            'user' => Auth::user(),
            'canRegister' => Route::has('register'),
        ]);
    }

    // 📖 Display all books
    public function index(Request $request)
    {
        // 1. Grab and sanitize inputs
        $search = $request->input('search');
        $categoryFilter = $request->input('category');
        $perPage = 10;

        // 2. Build the query
        $query = Book::query();

        // Apply search (Title or Author)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
            });
        }

        // Apply category filter
        if (!empty($categoryFilter)) {
            $query->where('category', $categoryFilter);
        }

        // 3. Execute pagination with query string persistence
        // latest() is a shorthand for orderBy('created_at', 'desc')
        $booksPaginated = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        // 4. Transform the data while maintaining pagination structure
        $booksPaginated->through(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'category' => $book->category,
                'description' => $book->description,
                'published_year' => $book->published_year,
                // Ensure image path is correct; uses storage disk
                'image' => $book->image ? asset('storage/' . $book->image) : null,
                'created_at' => $book->created_at ? $book->created_at->toDateTimeString() : null,
            ];
        });

        // 5. Get Unique categories for dropdown
        $categoriesList = Book::select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->get();

        // 6. Stats logic
        $recentlyAddedCount = Book::where('created_at', '>=', now()->subDays(7))->count();
        $totalCount = Book::count(); // <--- ADD THIS

        // 7. Return to Inertia
        return Inertia::render('Admin/Library', [
            'books' => $booksPaginated,
            'totalBooksCount' => $totalCount,
            'filters' => [
                'search' => $search ?? '',
                'category' => $categoryFilter ?? '',
            ],
            'categoriesList' => $categoriesList,
            'recentlyAddedCount' => $recentlyAddedCount,
        ]);
    }

    // ➕ Store new book
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480', // max 20MB
        ]);
    
        $data = $request->only([
            'title',
            'author',
            'category',
            'published_year',
            'description'
        ]);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('books', 'public');
        }
    
        Book::create($data);
    
        return redirect()
            ->route('books.index')
            ->with('success', 'Book added successfully!');
    }
    
    
    // ✏ Update book
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
    
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480', // max 20MB
            'keep_image' => 'nullable|string',
        ]);
    
        $data = $request->only([
            'title',
            'author',
            'category',
            'published_year',
            'description'
        ]);
    
        // Handle image update
        if ($request->hasFile('image')) {
    
            // Delete old image if exists
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
    
            $data['image'] = $request->file('image')->store('books', 'public');
    
        } elseif ($request->input('keep_image')) {
    
            // Keep existing image
            $data['image'] = $book->image;
    
        } else {
    
            // Remove image if not keeping
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
    
            $data['image'] = null;
        }
    
        $book->update($data);
    
        return redirect()
            ->back()
            ->with('success', 'Book updated successfully!');
    }

    // ❌ Delete book
    public function destroy(Book $book)
    {
        // Delete image if exists
        if ($book->image && Storage::disk('public')->exists($book->image)) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return redirect()->back()->with('success', 'Book deleted successfully!');
    }
}
