<x-organizer-layout>
    <x-slot:title>
        Edit Organizer Profile
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Organizer Profile</h1>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
            <form action="{{ route('organizer.profile.update', $organizer->organizer_id) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  x-data="{ previewImage: function(event) {
                      const reader = new FileReader();
                      reader.onload = (e) => {
                          document.getElementById('profile_image_preview').src = e.target.result;
                      };
                      reader.readAsDataURL(event.target.files[0]);
                  }}">
                @csrf
                @method('PATCH')
            
                <div class="flex">
                    <!-- Left Column: Profile Image -->
                    <div class="flex flex-col justify-center items-center w-1/3 h-full mt-12">
                        <input type="file" 
                               name="org_img" 
                               class="hidden" 
                               id="org_img_input" 
                               @change="previewImage($event)">
                        <img id="profile_image_preview" 
                             class="w-48 h-48 rounded-full object-cover cursor-pointer hover:opacity-75 transition-opacity duration-150" 
                             src="{{ $organizer->org_img ? asset('storage/' . $organizer->org_img) : 'https://via.placeholder.com/150' }}" 
                             alt="Profile Image" 
                             onclick="document.getElementById('org_img_input').click();">
                        <p class="text-gray-500 text-sm mt-4">Click the image to change your profile picture</p>
                    </div>
            
                    <!-- Vertical Divider -->
                    <div class="w-px bg-gray-200 mx-8"></div>
            
                    <!-- Right Column: Editable Organizer Details -->
                    <div class="flex-1">
                        <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg border border-gray-300">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Edit Organizer Details
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="org_name" value="{{ $organizer->org_name }}" class="form-input w-full rounded-md">
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Age</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="number" name="org_age" value="{{ $organizer->org_age }}" class="form-input w-full rounded-md">
                                        </dd>
                                    </div>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Course</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="org_course" value="{{ $organizer->org_course }}" class="form-input w-full rounded-md">
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Position</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="org_position" value="{{ $organizer->org_position }}" class="form-input w-full rounded-md">
                                        </dd>
                                    </div>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="org_phoneNo" value="{{ $organizer->org_phoneNo }}" class="form-input w-full rounded-md">
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">About</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <textarea name="org_detail" 
                                                      class="form-input w-full rounded-md" 
                                                      rows="4">{{ $organizer->org_detail ?? 'No details available.' }}</textarea>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="flex justify-end mt-8">
                    <button type="submit" 
                            class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-150">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-toast />
</x-organizer-layout>