<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditProfile extends Component
{
    use WithFileUploads;

    public $org_name;
    public $org_age;
    public $org_course;
    public $org_position;
    public $org_phoneNo;
    public $org_detail;
    public $tempImage;
    public $org_img;
    public $isLoading = false;

    public function mount($profile)
    {
        // Initialize properties with the profile data
        $this->org_name = $profile->org_name;
        $this->org_age = $profile->org_age;
        $this->org_course = $profile->org_course;
        $this->org_position = $profile->org_position;
        $this->org_phoneNo = $profile->org_phoneNo;
        $this->org_detail = $profile->org_detail;
        $this->org_img = $profile->org_img ? Storage::url($profile->org_img) : null;
    }

    public function save()
    {
        $this->validate([
            'org_name' => 'required|string|max:255',
            'org_age' => 'required|integer|min:18',
            'org_course' => 'required|string|max:255',
            'org_position' => 'required|string|max:255',
            'org_phoneNo' => 'required|string|max:15',
            'org_detail' => 'nullable|string',
            'tempImage' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Image validation
        ]);

        $organizer = Auth::user()->organizer;

        // Handle profile image upload
        if ($this->tempImage) {
            if ($organizer->org_img && Storage::exists($organizer->org_img)) {
                Storage::delete($organizer->org_img);
            }
            $organizer->org_img = $this->tempImage->store('profile_images', 'public');
        }

        // Update organizer details
        $organizer->org_name = $this->org_name;
        $organizer->org_age = $this->org_age;
        $organizer->org_course = $this->org_course;
        $organizer->org_position = $this->org_position;
        $organizer->org_phoneNo = $this->org_phoneNo;
        $organizer->org_detail = $this->org_detail;

        $organizer->save();

        // Flash success message
        session()->flash('success', 'Profile updated successfully.');

        // Redirect to the index page
        return redirect()->route('organizer.profile.index'); // Adjust the route name as necessary
    }

    public function render()
    {
        return view('livewire.organizer.edit-profile');
    }
}