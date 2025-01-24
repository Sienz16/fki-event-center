<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Venue;
use App\models\Event;
use App\Models\Admin;
use App\Models\EventOrganizer;
use App\Models\Student; // Include Student model

class ProfileController extends Controller
{
    /**
     * Show the profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $selectedRole = session('selected_role'); // Fetch the selected role from the session
    
        if ($selectedRole === 'admin') {
            $admin = Auth::user()->admin;
            if (!$admin) {
                return redirect()->route('home')->with('error', 'Admin profile not found.');
            }
            return view('admin.profile.index', compact('admin'));
    
        } elseif ($selectedRole === 'event_organizer') {
            $organizer = Auth::user()->organizer;
            if (!$organizer) {
                return redirect()->route('home')->with('error', 'Organizer profile not found.');
            }
            return view('organizer.profile.index', compact('organizer'));
        } elseif ($selectedRole === 'student') {
            $student = Auth::user()->student;
            if (!$student) {
                return redirect()->route('home')->with('error', 'Student profile not found.');
            }
    
            // Fetch the events the student has registered for via the Attendance table
            $events = $student->events()->paginate(9); // Paginate results
    
            return view('student.profile.index', compact('student', 'events'));
        } else {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $selectedRole = session('selected_role'); // Fetch the selected role from the session

        if ($selectedRole === 'admin') {
            $admin = Auth::user()->admin;
            if (!$admin) {
                return redirect()->route('home')->with('error', 'Admin profile not found.');
            }
            return view('admin.profile.edit', compact('admin'));

        } elseif ($selectedRole === 'event_organizer') {
            $organizer = Auth::user()->organizer;
            if (!$organizer) {
                return redirect()->route('home')->with('error', 'Organizer profile not found.');
            }
            return view('organizer.profile.edit', compact('organizer'));

        } elseif ($selectedRole === 'student') {
            $student = Auth::user()->student;
            if (!$student) {
                return redirect()->route('home')->with('error', 'Student profile not found.');
            }
            return view('student.profile.edit', compact('student'));

        } else {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
    }

    /**
     * Update the profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $selectedRole = session('selected_role'); // Fetch the selected role from the session

        if ($selectedRole === 'admin') {
            $admin = Auth::user()->admin;
            $this->validateProfile($request, $admin->management_id, 'admin');
            $this->updateProfile($admin, $request, 'admin');
            return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully.');

        } elseif ($selectedRole === 'event_organizer') {
            $organizer = Auth::user()->organizer;
            $this->validateProfile($request, $organizer->organizer_id, 'event_organizer');
            $this->updateProfile($organizer, $request, 'event_organizer');
            return redirect()->route('organizer.profile.index')->with('success', 'Profile updated successfully.');

        } elseif ($selectedRole === 'student') {
            $student = Auth::user()->student;
            $this->validateProfile($request, $student->stud_id, 'student');
            $this->updateProfile($student, $request, 'student');
            return redirect()->route('student.profile.index')->with('success', 'Profile updated successfully.');

        } else {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
    }

    /**
     * Validate the profile fields.
     */
    protected function validateProfile(Request $request, $id, $role)
    {
        if ($role === 'admin') {
            $request->validate([
                'manage_name' => 'required|string|max:255',
                'manage_position' => 'required|string|max:255',
                'manage_phoneNo' => 'required|string|max:15',
                'manage_email' => 'required|string|email|max:255|unique:admins,manage_email,' . $id . ',management_id',
                'manage_img' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Increased max size to 10MB
                'manage_detail' => 'nullable|string',
            ]);
        } elseif ($role === 'event_organizer') {
            $request->validate([
                'org_name' => 'required|string|max:255',
                'org_age' => 'required|integer|min:18',
                'org_course' => 'required|string|max:255',
                'org_position' => 'required|string|max:255',
                'org_phoneNo' => 'required|string|max:15',
                'org_detail' => 'nullable|string',
                'org_img' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Image validation
            ]);
        } elseif ($role === 'student') {
            $request->validate([
                'stud_name' => 'required|string|max:255',
                'stud_age' => 'required|integer|min:18',
                'stud_course' => 'required|string|max:255',
                'stud_phoneNo' => 'required|string|max:15',
                'stud_detail' => 'nullable|string',
                'stud_img' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Image validation
            ]);
        }
    }

    /**
     * Update the profile fields.
     */
    protected function updateProfile($user, Request $request, $role)
    {
        // Handle profile image upload
        $imgField = ($role === 'admin') ? 'manage_img' : (($role === 'event_organizer') ? 'org_img' : 'stud_img');
        if ($request->hasFile($imgField)) {
            if ($user->$imgField && Storage::exists($user->$imgField)) {
                Storage::delete($user->$imgField);
            }
            $profileImagePath = $request->file($imgField)->store('profile_images', 'public');
            $user->$imgField = $profileImagePath;
        }

        // Update user details based on role
        if ($role === 'admin') {
            $user->manage_name = $request->manage_name;
            $user->manage_position = $request->manage_position;
            $user->manage_phoneNo = $request->manage_phoneNo;
            $user->manage_email = $request->manage_email;
            $user->manage_detail = $request->manage_detail;
        } elseif ($role === 'event_organizer') {
            $user->org_name = $request->org_name;
            $user->org_age = $request->org_age;
            $user->org_course = $request->org_course;
            $user->org_position = $request->org_position;
            $user->org_phoneNo = $request->org_phoneNo;
            $user->org_detail = $request->org_detail;
        } elseif ($role === 'student') {
            $user->stud_name = $request->stud_name;
            $user->stud_age = $request->stud_age;
            $user->stud_course = $request->stud_course;
            $user->stud_phoneNo = $request->stud_phoneNo;
            $user->stud_detail = $request->stud_detail;
        }

        $user->save();
    }

    /**
     * Delete the profile.
     */
    protected function deleteProfile($user)
    {
        if ($user->manage_img && Storage::exists($user->manage_img)) {
            Storage::delete($user->manage_img);
        }
        $user->delete();
    }
}