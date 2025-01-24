<?php

namespace App\Http\Controllers;

use App\Models\CommunityForum;
use App\Models\ForumAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ForumLikeNotification;

class CommunityForumController extends Controller
{
    public function index()
    {
        $selectedRole = session('selected_role');

        if ($selectedRole === 'event_organizer') {
            $organizer = Auth::user()->organizer;

            if (!$organizer) {
                return redirect()->route('login')->with('error', 'Organizer not found.');
            }

            // Eager load the organizer relationship to get the organizer's name
            $communityPosts = CommunityForum::where('organizer_id', $organizer->organizer_id)
                                            ->with('organizer.user') // Eager load the organizer's user
                                            ->get();

            return view('organizer.community.index', compact('communityPosts'));
        } elseif ($selectedRole === 'student') {
            // Eager load the organizer relationship to get the organizer's name
            $communityPosts = CommunityForum::with('organizer.user')->get();
    
            // Check if the user is authenticated and then add liked_by_user
            foreach ($communityPosts as $post) {
                $post->liked_by_user = Auth::check() ? $post->isLikedBy(Auth::user()) : false;
            }
    
            return view('student.community.index', compact('communityPosts'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function create()
    {
        $organizer = Auth::user()->organizer;

        if (!$organizer) {
            return redirect()->route('login')->with('error', 'Organizer not found.');
        }

        return view('organizer.community.create');
    }

    public function store(Request $request)
    {
        $organizer = Auth::user()->organizer;

        if (!$organizer) {
            return redirect()->route('organizer.community.index')->with('error', 'You are not authorized to create a post.');
        }

        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'desc' => 'nullable|string',
        ]);

        $imageName = time() . '.' . $request->img->extension();  
        $request->img->move(public_path('images'), $imageName);

        CommunityForum::create([
            'organizer_id' => $organizer->organizer_id,
            'img' => $imageName,
            'desc' => $request->input('desc'),
        ]);

        return redirect()->route('organizer.community.index')
                         ->with('success', 'Post created successfully.');
    }

    public function destroy($id)
    {
        $selectedRole = session('selected_role');

        // Check if the user is an event organizer
        if ($selectedRole === 'event_organizer') {
            $organizer = Auth::user()->organizer;

            // Check if the organizer exists
            if (!$organizer) {
                return redirect()->route('login')->with('error', 'Organizer not found.');
            }

            // Find the community photo by organizer ID and community ID
            $photo = CommunityForum::where('organizer_id', $organizer->organizer_id)
                                ->where('com_id', $id)
                                ->first();

            // Check if the photo exists
            if (!$photo) {
                return redirect()->route('organizer.community.index')->with('error', 'Photo not found.');
            }

            // Delete the photo
            $photo->delete();

            // Redirect with success message
            return redirect()->route('organizer.community.index')
                            ->with('success', 'Photo deleted successfully.');
        } else {
            // Redirect if access is denied
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function toggleLike($id)
    {
        $user = Auth::user();
        $student = $user->student;
        
        $post = CommunityForum::findOrFail($id);
        
        $action = ForumAction::where('stud_id', $student->stud_id)
                            ->where('com_id', $id)
                            ->where('action_type', 'like')
                            ->first();
        
        if ($action) {
            // Unlike the post
            $action->delete();
            $post->decrement('likes');
            return response()->json(['status' => 'unliked', 'likes' => $post->likes]);
        } else {
            // Like the post
            ForumAction::create([
                'stud_id' => $student->stud_id,
                'com_id' => $id,
                'action_type' => 'like',
            ]);
            $post->increment('likes');

            // Notify the organizer about the like
            $post->organizer->user->notify(new ForumLikeNotification(
                'New Post Like',
                "{$student->stud_name} liked your forum post.",
                $post->com_id,
                $student->stud_id
            ));

            return response()->json(['status' => 'liked', 'likes' => $post->likes]);
        }
    }

    public function registerView($id)
    {
        $post = CommunityForum::findOrFail($id);
    
        // Get the authenticated student
        $user = Auth::user();
        $student = $user->student;
    
        // Check if the user has already viewed the post
        $action = ForumAction::where('stud_id', $student->stud_id)
                             ->where('com_id', $id)
                             ->where('action_type', 'view')
                             ->first();
    
        if (!$action) {
            // Increment view count if it's the first time the student views this post
            $post->increment('views');
    
            // Record the view in the action log
            ForumAction::create([
                'stud_id' => $student->stud_id,
                'com_id' => $id,
                'action_type' => 'view',
            ]);
        }
    
        return response()->json(['views' => $post->views]);
    }     
}