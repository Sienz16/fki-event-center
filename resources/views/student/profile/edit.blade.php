{{-- <!doctype html>
<html class="h-full bg-purple-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student Profile</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
<div class="min-h-full">
    @include('student.layouts.student_nav')  <!-- Including the navigation bar -->

    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Student Profile</h1>
      </div>
    </header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                <form action="{{ route('student.profile.update', $student->stud_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                
                    <div class="flex">
                        <!-- Left Column: Profile Image -->
                        <div class="flex flex-col justify-center items-center w-1/3 h-full mt-12">
                            <input type="file" name="stud_img" class="hidden" id="stud_img_input" onchange="previewImage(event)">
                            <img id="profile_image_preview" class="w-48 h-48 rounded-full object-cover cursor-pointer" src="{{ $student->stud_img ? asset('storage/' . $student->stud_img) : 'https://via.placeholder.com/150' }}" alt="Profile Image" onclick="document.getElementById('stud_img_input').click();">
                            <p class="text-gray-500 text-sm mt-4">Click the image to change your profile picture</p>
                        </div>
                
                        <!-- Vertical Divider -->
                        <div class="w-px bg-gray-200 mx-8"></div>
                
                        <!-- Right Column: Editable Student Details -->
                        <div class="flex-1">
                            <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg border border-gray-300">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Edit Student Details
                                    </h3>
                                </div>
                                <div class="border-t border-gray-200">
                                    <dl>
                                        <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Full Name
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="text" name="stud_name" value="{{ $student->stud_name }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Age
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="number" name="stud_age" value="{{ $student->stud_age }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Course
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="text" name="stud_course" value="{{ $student->stud_course }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Phone Number
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="text" name="stud_phoneNo" value="{{ $student->stud_phoneNo }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                About
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <textarea name="stud_detail" class="form-input w-full">{{ $student->stud_detail ?? 'No details available.' }}</textarea>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Save Changes
                        </button>
                    </div>
                </form>
                
                <script>
                    function previewImage(event) {
                        var reader = new FileReader();
                        reader.onload = function() {
                            var output = document.getElementById('profile_image_preview');
                            output.src = reader.result;
                        };
                        reader.readAsDataURL(event.target.files[0]);
                    }
                </script>                               
            </div>
        </div>
    </main>
</div>

@include('layouts.footer')  <!-- Including the footer -->
</body>
</html> --}}

<x-student-layout>
    <x-slot:title>
        Edit Student Profile
    </x-slot>

    <x-slot:header>
        Edit Student Profile
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]">
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
            <form action="{{ route('student.profile.update', $student->stud_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
            
                <div class="flex">
                    <!-- Left Column: Profile Image -->
                    <div class="flex flex-col justify-center items-center w-1/3 h-full mt-12">
                        <input type="file" name="stud_img" class="hidden" id="stud_img_input" onchange="previewImage(event)">
                        <img id="profile_image_preview" class="w-48 h-48 rounded-full object-cover cursor-pointer" src="{{ $student->stud_img ? asset('storage/' . $student->stud_img) : 'https://via.placeholder.com/150' }}" alt="Profile Image" onclick="document.getElementById('stud_img_input').click();">
                        <p class="text-gray-500 text-sm mt-4">Click the image to change your profile picture</p>
                    </div>
            
                    <!-- Vertical Divider -->
                    <div class="w-px bg-gray-200 mx-8"></div>
            
                    <!-- Right Column: Editable Student Details -->
                    <div class="flex-1">
                        <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg border border-gray-300">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Edit Student Details
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Full Name
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="stud_name" value="{{ $student->stud_name }}" class="form-input w-full">
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Age
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="number" name="stud_age" value="{{ $student->stud_age }}" class="form-input w-full">
                                        </dd>
                                    </div>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Course
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="stud_course" value="{{ $student->stud_course }}" class="form-input w-full">
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Phone Number
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="stud_phoneNo" value="{{ $student->stud_phoneNo }}" class="form-input w-full">
                                        </dd>
                                    </div>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            About
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <textarea name="stud_detail" class="form-input w-full">{{ $student->stud_detail ?? 'No details available.' }}</textarea>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Save Changes
                    </button>
                </div>
            </form>
            
            <script>
                function previewImage(event) {
                    var reader = new FileReader();
                    reader.onload = function() {
                        var output = document.getElementById('profile_image_preview');
                        output.src = reader.result;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            </script>                               
        </div>
    </div>
</x-student-layout>