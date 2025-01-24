<?php

namespace App\Http\Controllers;

use App\Models\News;  // Import the News model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Fetch the latest 3 news items with their associated admin details
        $news = News::with('admin')->latest()->take(3)->get();

        // Pass the news data to the dashboard view
        return view('admin.dashboard', compact('news'));
    }

    public function users()
    {
        // Code to fetch users
        return view('admin.users');
    }

    public function settings()
    {
        // Code to fetch settings
        return view('admin.settings');
    }

    public function index()
    {
        $admins = Admin::with('user')->latest()->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matric_no' => ['required', 'string', 'min:3', 'max:20', 'unique:users', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'detail' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'matric_no' => $validated['matric_no'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Handle image upload if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profile_images', 'public');
            }

            // Assign admin role
            $user->roles()->attach(Role::where('role_name', 'admin')->first());

            // Create admin profile
            Admin::create([
                'user_id' => $user->id,
                'manage_name' => $validated['name'],
                'manage_phoneNo' => $validated['phone'],
                'manage_email' => $validated['email'],
                'manage_position' => $validated['position'],
                'manage_img' => $imagePath,
                'manage_detail' => $validated['detail'],
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Admin account created successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create admin account. Please try again.');
        }
    }

    public function edit()
    {
        $admin = Admin::where('user_id', Auth::id())->firstOrFail();
        return view('admin.profile.edit', compact('admin'));
    }
}
