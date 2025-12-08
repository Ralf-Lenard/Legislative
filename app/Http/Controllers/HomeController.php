<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function indexAdmin()
    {
        $user = Auth::user();
       
        if ($user->usertype === 'admin') {
            return Inertia::render('Admin/Dashboard'); // optional named route
        } 

    }

    public function welcome()
    {
        // If logged in, route based on user type
        if (Auth::check()) {
            $user = Auth::user();
    
            // If normal user → Home page
            if ($user->usertype === 'user') {
                return Inertia::render('Home');
            }
    
            // If admin or other → Dashboard (optional)
            return redirect()->route('dashboard');
        }
    
        // Guest users see Welcome page
        return Inertia::render('Welcome', [
            'canRegister' => Route::has('register'),
        ]);
    }
    

    
}
