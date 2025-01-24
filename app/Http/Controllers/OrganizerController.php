<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News; // Import the News model

class OrganizerController extends Controller
{
    public function index()
    {
        // Fetch the news data to display on the organizer dashboard
        $news = News::with('admin')->latest()->take(3)->get(); // Assuming the News model has a relationship with Admin

        // Pass the news data to the organizer dashboard view
        return view('organizer.dashboard', compact('news'));
    }
}