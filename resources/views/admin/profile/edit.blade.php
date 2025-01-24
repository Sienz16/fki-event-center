<x-admin-layout>
    <div x-data="{ 
        previewImage(event) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('profile_image_preview').src = e.target.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    }">
        <x-slot:title>
            Edit Admin Profile
        </x-slot>

        <x-slot:header>
            Edit Admin Profile
        </x-slot>

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update', $admin->management_id) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                
                    <div class="flex">
                        <!-- Left Column: Profile Image -->
                        <div class="flex flex-col justify-center items-center w-1/3 h-full mt-12">
                            <input type="file" 
                                   name="manage_img" 
                                   class="hidden" 
                                   id="manage_img_input" 
                                   accept="image/*"
                                   @change="previewImage($event)">
                            <img id="profile_image_preview" 
                                 class="w-48 h-48 rounded-full object-cover cursor-pointer" 
                                 src="{{ $admin->manage_img ? asset('storage/' . $admin->manage_img) : 'https://via.placeholder.com/150' }}" 
                                 alt="Profile Image" 
                                 @click="document.getElementById('manage_img_input').click()">
                            <p class="text-gray-500 text-sm mt-4">Click the image to change your profile picture</p>
                            @error('manage_img')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                
                        <!-- Vertical Divider -->
                        <div class="w-px bg-gray-200 mx-8"></div>
                
                        <!-- Right Column: Editable Admin Details -->
                        <div class="flex-1">
                            <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg border border-gray-300">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Edit Admin Details
                                    </h3>
                                </div>
                                <div class="border-t border-gray-200">
                                    <dl>
                                        <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Full Name
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="text" name="manage_name" value="{{ $admin->manage_name }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Position
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="text" name="manage_position" value="{{ $admin->manage_position }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Phone Number
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="text" name="manage_phoneNo" value="{{ $admin->manage_phoneNo }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Email Address
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <input type="email" name="manage_email" value="{{ $admin->manage_email }}" class="form-input w-full">
                                            </dd>
                                        </div>
                                        <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                About
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <textarea name="manage_detail" class="form-input w-full">{{ $admin->manage_detail ?? 'No details available.' }}</textarea>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="flex justify-end mt-8">
                        <button type="submit" 
                                class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-toast />
</x-admin-layout>