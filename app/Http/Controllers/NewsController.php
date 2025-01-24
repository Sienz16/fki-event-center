<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Admin;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('admin')->get(); // Fetch all news items with associated admins
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $admins = Admin::all();
        return view('admin.news.create', compact('admins'));
    }
    
    public function store(Request $request)
    {
        // Validate the input fields, excluding the date
        $validated = $request->validate([
            'news_title' => 'required|string|max:255',
            'news_details' => 'required|string',
            'management_id' => 'required|exists:admins,management_id',
            'news_tag' => 'nullable|in:Update,Maintenance,Bugs', // Validate enum values
        ]);
    
        // Create the news record with the current date and time
        News::create([
            'news_title' => $validated['news_title'],
            'news_details' => $validated['news_details'],
            'date' => now(), // Automatically sets the current date and time
            'management_id' => $validated['management_id'],
            'news_tag' => $validated['news_tag'],
        ]);
    
        // Redirect to the news index with a success message
        return redirect()->route('admin.news.index')->with('success', 'News created successfully');
    }    
    

    public function edit(News $news)
    {
        $admins = Admin::all();
        return view('admin.news.edit', compact('news', 'admins'));
    }

    public function update(Request $request, News $news)
    {
        // Validate the incoming request data
        $request->validate([
            'news_title' => 'required|string|max:255',
            'news_details' => 'required|string',
            'news_tag' => 'nullable|in:Update,Maintenance,Bugs', // Validate enum values
        ]);
    
        // Update only the necessary fields
        $news->update([
            'news_title' => $request->input('news_title'),
            'news_details' => $request->input('news_details'),
            'news_tag' => $request->input('news_tag'),
            'updated_at' => now(), // Set the updated_at column to the current timestamp
        ]);
    
        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }   

    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);
            $news->delete();
            
            return redirect()->route('admin.news.index')
                ->with('success', 'News deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.news.index')
                ->with('error', 'Failed to delete news');
        }
    }
}
