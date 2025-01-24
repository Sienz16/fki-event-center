<x-admin-layout>
    <x-slot:title>
        Edit Venue
    </x-slot>

    <x-slot:header>
        Edit Venue
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg" x-data="venueEdit">
            <form action="{{ route('admin.venue.update', $venue->venue_id) }}" method="POST" enctype="multipart/form-data" 
                  x-data="venueEdit" class="max-w-4xl mx-auto space-y-8">
                @csrf
                @method('PUT')
                <input type="hidden" name="management_id" value="{{ $venue->management_id }}">

                <!-- Venue Name -->
                <div class="relative">
                    <label for="venue_name" class="block text-sm font-medium text-gray-700 mb-1">Venue Name</label>
                    <input type="text" name="venue_name" id="venue_name" value="{{ $venue->venue_name }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                </div>

                <!-- Venue Location -->
                <div class="relative">
                    <label for="venue_location" class="block text-sm font-medium text-gray-700 mb-1">Venue Location</label>
                    <textarea name="venue_location" id="venue_location" required rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">{{ $venue->venue_location }}</textarea>
                </div>

                <!-- Venue Status -->
                <div class="relative">
                    <label for="venue_status" class="block text-sm font-medium text-gray-700 mb-1">Venue Status</label>
                    <select name="venue_status" id="venue_status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                        <option value="Available" {{ $venue->venue_status == 'Available' ? 'selected' : '' }}>Available</option>
                        {{-- <option value="Booked" {{ $venue->venue_status == 'Booked' ? 'selected' : '' }}>Booked</option> --}}
                        <option value="Under Maintenance" {{ $venue->venue_status == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>

                <!-- Venue Details -->
                <div class="relative">
                    <label for="venue_details" class="block text-sm font-medium text-gray-700 mb-1">Venue Details</label>
                    <textarea name="venue_details" id="venue_details" rows="4"
                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">{{ $venue->venue_details }}</textarea>
                </div>

                <!-- Venue Capacity -->
                <div class="relative">
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                    <input type="number" 
                        name="capacity" 
                        id="capacity" 
                        value="{{ $venue->capacity }}" 
                        min="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"
                        placeholder="Enter venue capacity">
                    <p class="mt-1 text-sm text-gray-500">Number of people the venue can accommodate</p>
                </div>

                <!-- Venue Equipment -->
                <div class="relative">
                    <label for="equipment" class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
                    <textarea 
                        name="equipment" 
                        id="equipment" 
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"
                        placeholder="List available equipment">{{ $venue->equipment }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">List all available equipment, separated by commas</p>
                </div>

                <!-- Venue Image Upload -->
                <div class="relative" x-data="venueEdit">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Venue Image</label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors duration-200"
                         @dragover.prevent="isHovered = true"
                         @dragleave.prevent="isHovered = false"
                         @drop.prevent="isHovered = false; handleImageDrop($event)"
                         :class="{ 'border-purple-400 bg-purple-50': isHovered }">

                        <div class="space-y-2 text-center">
                            <!-- Upload UI -->
                            <div x-show="!hasImage" class="flex flex-col items-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="venue_image" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500">
                                        <span>Upload an image</span>
                                        <input id="venue_image" name="venue_image" type="file" class="sr-only" accept="image/*"
                                               @change="showPreview($event)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                            </div>

                            <!-- Image Preview -->
                            <div x-show="hasImage" class="relative group">
                                <img x-ref="venue_img_preview" :src="imagePreview" class="max-h-64 rounded-lg object-contain mx-auto" />
                                
                                <!-- Remove Image Button -->
                                <button type="button" 
                                        @click="hasImage = false; $refs.venue_img_preview.src = ''; document.getElementById('venue_image').value = '';"
                                        class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.venue.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Update Venue
                        <i class="fas fa-check ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('venueEdit', () => ({
                oldImage: '{{ asset("storage/" . $venue->venue_image) }}',
                imagePreview: '{{ asset("storage/" . $venue->venue_image) }}',
                hasImage: true,
    
                showPreview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imagePreview = URL.createObjectURL(file);
                        this.hasImage = true;
                    } else {
                        this.imagePreview = this.oldImage;
                        this.hasImage = true;
                    }
                },
    
                handleImageDrop(event) {
                    event.preventDefault();
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        this.imagePreview = URL.createObjectURL(file);
                        this.hasImage = true;
    
                        const input = document.getElementById('venue_image');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                    }
                }
            }));
    
            Alpine.data('imageUpload', () => ({
                isHovered: false,
                hasImage: false,
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
                        const input = document.getElementById('venue_image');
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