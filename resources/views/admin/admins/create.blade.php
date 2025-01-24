<x-admin-layout>
    <x-slot:title>Add New Admin</x-slot>

    <x-slot:header>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Add New Admin</h1>
                <p class="mt-1 text-sm text-gray-600">Create a new administrator account</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-xl overflow-hidden">
            <!-- Form Header -->
            <div class="relative h-40 bg-gradient-to-r from-purple-600 via-purple-500 to-purple-400">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute -bottom-6 left-8">
                    <div class="bg-white rounded-xl p-2 shadow-lg inline-block">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-400 rounded-lg p-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-8 left-32">
                    <h2 class="text-2xl font-bold text-white">New Administrator</h2>
                    <p class="text-purple-100">Complete the form below to create a new admin account</p>
                </div>
            </div>

            <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data" 
                  class="p-8 pt-12 space-y-8">
                @csrf
                
                <!-- Personal Information Section -->
                <div class="bg-gray-50/50 rounded-xl p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-3">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="group">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <div class="relative">
                                <input type="text" name="name" id="name" required placeholder="Enter full name"
                                    class="block w-full px-4 py-3 rounded-lg border-0 bg-white/50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 group-hover:ring-purple-500/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-300 ease-in-out">
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="group">
                            <label for="matric_no" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <div class="relative">
                                <input type="text" name="matric_no" id="matric_no" required 
                                    placeholder="Choose a username" pattern="[a-zA-Z0-9_-]{3,20}"
                                    title="Username must be between 3-20 characters and can only contain letters, numbers, underscores and hyphens"
                                    class="block w-full px-4 py-3 rounded-lg border-0 bg-white/50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 group-hover:ring-purple-500/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-300 ease-in-out">
                                <p class="mt-1 text-xs text-gray-500">3-20 characters, letters, numbers, _ and - only</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" required placeholder="admin@example.com"
                                    class="block w-full px-4 py-3 rounded-lg border-0 bg-white/50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 group-hover:ring-purple-500/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-300 ease-in-out">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="group">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required placeholder="••••••••"
                                    class="block w-full px-4 py-3 rounded-lg border-0 bg-white/50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 group-hover:ring-purple-500/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-300 ease-in-out">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="group">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <div class="relative">
                                <input type="tel" name="phone" id="phone" required placeholder="+60 12-345-6789"
                                    class="block w-full px-4 py-3 rounded-lg border-0 bg-white/50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 group-hover:ring-purple-500/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-300 ease-in-out">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Information Section -->
                <div class="bg-gray-50/50 rounded-xl p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-3">Role Information</h3>
                    
                    <!-- Position -->
                    <div class="group">
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                        <div class="relative">
                            <input type="text" name="position" id="position" required placeholder="e.g. Senior Administrator"
                                class="block w-full px-4 py-3 rounded-lg border-0 bg-white/50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 group-hover:ring-purple-500/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-300 ease-in-out">
                        </div>
                    </div>

                    <!-- Admin Details -->
                    <div class="group">
                        <label for="detail" class="block text-sm font-medium text-gray-700 mb-2">Additional Details</label>
                        <textarea name="detail" id="detail" rows="4" placeholder="Enter any additional information..."
                            class="block w-full px-4 py-3 rounded-lg border-0 bg-white/50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 group-hover:ring-purple-500/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-300 ease-in-out"></textarea>
                    </div>
                </div>

                <!-- Profile Image Section -->
                <div class="bg-gray-50/50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-3 mb-6">Profile Image</h3>
                    
                    <div class="relative" x-data="imageUpload">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg hover:border-purple-400 transition-colors duration-200"
                             @dragover.prevent="isHovered = true"
                             @dragleave.prevent="isHovered = false"
                             @drop.prevent="isHovered = false; handleImageDrop($event, 'image_preview')"
                             :class="{ 'border-purple-400 bg-purple-50': isHovered }">

                            <div class="space-y-2 text-center">
                                <!-- Upload UI -->
                                <div x-show="!hasImage" class="flex flex-col items-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload an image</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*"
                                                   @change="previewImage($event, 'image_preview')">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                </div>

                                <!-- Image Preview -->
                                <div x-show="hasImage" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="relative group">
                                    <img x-ref="image_preview" 
                                         class="max-h-64 rounded-lg object-contain mx-auto" />
                                    
                                    <!-- Remove Image Button -->
                                    <button type="button" 
                                            @click="hasImage = false; $refs.image_preview.src = ''; document.getElementById('image').value = '';"
                                            class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="rounded-lg px-6 py-3 text-sm font-medium text-gray-700 hover:text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 ease-in-out">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="rounded-lg relative px-6 py-3 text-sm font-medium text-white overflow-hidden transition-all duration-300 ease-in-out
                                   before:absolute before:inset-0 before:bg-gradient-to-r before:from-[#9d00ff] before:via-purple-500 before:to-[#9d00ff]
                                   hover:before:scale-x-[1.15] hover:before:scale-y-[1.1] hover:shadow-purple-500/50
                                   focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <span class="relative">Create Administrator</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add this script section -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('imageUpload', () => ({
                hasImage: false,
                isHovered: false,
                previewImage(event, previewId) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.$refs[previewId].src = e.target.result;
                            this.hasImage = true;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                handleImageDrop(event, previewId) {
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const input = document.getElementById('image');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.$refs[previewId].src = e.target.result;
                            this.hasImage = true;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }));
        });
    </script>
</x-admin-layout> 