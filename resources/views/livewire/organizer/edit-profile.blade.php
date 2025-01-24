<div x-data="{ imagePreview: null }">
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="flex">
            <!-- Left Column: Profile Image -->
            <div class="flex flex-col justify-center items-center w-1/3 h-full mt-12">
                <div class="relative">
                    <img 
                        class="w-48 h-48 rounded-full object-cover cursor-pointer" 
                        :src="imagePreview || '{{ $org_img }}' || 'https://via.placeholder.com/150'"
                        alt="Profile Image"
                        @click="$refs.fileInput.click()"
                    >
                    <input 
                        type="file" 
                        wire:model="tempImage" 
                        class="hidden" 
                        x-ref="fileInput"
                        accept="image/*"
                        @change="const file = $event.target.files[0]; 
                                const reader = new FileReader();
                                reader.onload = (e) => { 
                                    imagePreview = e.target.result;
                                };
                                reader.readAsDataURL(file);"
                    >
                </div>
                <p class="text-gray-500 text-sm mt-4">Click the image to change your profile picture</p>
                @error('tempImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Right Column: Profile Details -->
            <div class="flex-1">
                <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg border border-gray-300">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Edit Organizer Details
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <!-- Name -->
                            <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <input type="text" wire:model="org_name" class="form-input w-full">
                                    @error('org_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </dd>
                            </div>

                            <!-- Age -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Age</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <input type="number" wire:model="org_age" class="form-input w-full">
                                    @error('org_age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </dd>
                            </div>

                            <!-- Course -->
                            <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Course</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <input type="text" wire:model="org_course" class="form-input w-full">
                                    @error('org_course') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </dd>
                            </div>

                            <!-- Position -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Position</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <input type="text" wire:model="org_position" class="form-input w-full">
                                    @error('org_position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </dd>
                            </div>

                            <!-- Phone Number -->
                            <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <input type="text" wire:model="org_phoneNo" class="form-input w-full">
                                    @error('org_phoneNo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </dd>
                            </div>

                            <!-- About -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">About</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <textarea wire:model="org_detail" class="form-input w-full" rows="3"></textarea>
                                    @error('org_detail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

    <!-- Loading State -->
    <div wire:loading wire:target="save, tempImage" class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-purple-500 overflow-hidden">
            <div class="w-full h-full origin-left bg-purple-300 animate-loading-bar"></div>
        </div>
    </div>
</div>
