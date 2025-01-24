<div>
    <form wire:submit.prevent="save" enctype="multipart/form-data" x-data="{
        currentStep: @entangle('currentStep'),
        isMultipleDay: @entangle('isMultipleDay'),
        eventType: @entangle('eventType'),
        hasImage: false,
        isHovered: false,
        hasCertTemplate: false,
        isCertHovered: false,
        showModal: false,
        modalTitle: '',
        modalMessage: '',
        showValidationError(title, message) {
            this.modalTitle = title;
            this.modalMessage = message;
            this.showModal = true;
        },
        previewImage(event, previewId) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.$refs[previewId].src = e.target.result;
                    if (previewId === 'imagePreview') {
                        this.hasImage = true;
                    } else if (previewId === 'certPreview') {
                        this.hasCertTemplate = true;
                    }
                };
                reader.readAsDataURL(file);
            }
        },
        validateStep1() {
            if (!this.eventName) {
                this.showValidationError('Validation Error', 'Please enter an event name');
                return false;
            }
            if (!this.eventDesc) {
                this.showValidationError('Validation Error', 'Please enter an event description');
                return false;
            }
            if (this.isMultipleDay) {
                if (!this.eventStartDate) {
                    this.showValidationError('Validation Error', 'Please select a start date');
                    return false;
                }
                if (!this.eventEndDate) {
                    this.showValidationError('Validation Error', 'Please select an end date');
                    return false;
                }
            } else {
                if (!this.eventDate) {
                    this.showValidationError('Validation Error', 'Please select an event date');
                    return false;
                }
            }
            if (!this.eventStartTime) {
                this.showValidationError('Validation Error', 'Please select a start time');
                return false;
            }
            if (!this.eventEndTime) {
                this.showValidationError('Validation Error', 'Please select an end time');
                return false;
            }
            return true;
        },
        validateStep2() {
            if (this.eventType === 'physical' && !this.venueId) {
                this.showValidationError('Validation Error', 'Please select a venue');
                return false;
            }
            if (this.eventType === 'online' && !this.onlinePlatform) {
                this.showValidationError('Validation Error', 'Please enter the online platform');
                return false;
            }
            return true;
        }
    }" class="max-w-4xl mx-auto">
        <!-- Title and Description -->
        <div class="mb-10 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Create New Event</h2>
            <p class="text-gray-600">Fill in the details below to create your event</p>
        </div>

        <!-- Progress Bar -->
        <div class="max-w-2xl mx-auto mb-12 border-b-2 pb-6">
            <div class="flex pb-3">
                <div class="flex-1"></div>
                
                <!-- Step Circles -->
                <div class="flex-1">
                    <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300"
                        :class="{
                            'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 1,
                            'bg-white border-2 border-gray-300': currentStep < 1
                        }">
                        <span class="text-center w-full" :class="{ 'text-white': currentStep >= 1, 'text-gray-600': currentStep < 1 }">
                            <i class="fas fa-check w-full text-xl" x-show="currentStep > 1"></i>
                            <span x-show="currentStep <= 1">1</span>
                        </span>
                    </div>
                    <div class="text-xs font-medium text-gray-600 mt-2">Basic Info</div>
                </div>

                <div class="flex-1 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="h-1 w-full bg-gray-200" :class="{'bg-purple-600': currentStep > 1}"></div>
                    </div>
                    <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300 relative bg-white"
                        :class="{
                            'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 2,
                            'bg-white border-2 border-gray-300': currentStep < 2
                        }">
                        <span class="text-center w-full" :class="{ 'text-white': currentStep >= 2, 'text-gray-600': currentStep < 2 }">
                            <i class="fas fa-check w-full text-xl" x-show="currentStep > 2"></i>
                            <span x-show="currentStep <= 2">2</span>
                        </span>
                    </div>
                    <div class="text-xs font-medium text-gray-600 mt-2">Venue</div>
                </div>

                <div class="flex-1 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="h-1 w-full bg-gray-200" :class="{'bg-purple-600': currentStep > 2}"></div>
                    </div>
                    <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300 relative bg-white"
                        :class="{
                            'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 3,
                            'bg-white border-2 border-gray-300': currentStep < 3
                        }">
                        <span class="text-center w-full" :class="{ 'text-white': currentStep >= 3, 'text-gray-600': currentStep < 3 }">
                            <i class="fas fa-check w-full text-xl" x-show="currentStep > 3"></i>
                            <span x-show="currentStep <= 3">3</span>
                        </span>
                    </div>
                    <div class="text-xs font-medium text-gray-600 mt-2">Image</div>
                </div>

                <div class="flex-1 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="h-1 w-full bg-gray-200" :class="{'bg-purple-600': currentStep > 3}"></div>
                    </div>
                    <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300 relative bg-white"
                        :class="{
                            'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 4,
                            'bg-white border-2 border-gray-300': currentStep < 4
                        }">
                        <span class="text-center w-full" :class="{ 'text-white': currentStep >= 4, 'text-gray-600': currentStep < 4 }">
                            <i class="fas fa-check w-full text-xl" x-show="currentStep > 4"></i>
                            <span x-show="currentStep <= 4">4</span>
                        </span>
                    </div>
                    <div class="text-xs font-medium text-gray-600 mt-2">Certificate</div>
                </div>

                <div class="flex-1"></div>
            </div>
            
            <!-- Step Labels -->
            <div class="flex text-sm content-center text-center mt-4">
                <div class="w-1/4">Basic Information</div>
                <div class="w-1/4">Venue Details</div>
                <div class="w-1/4">Event Image</div>
                <div class="w-1/4">Certificate</div>
            </div>
        </div>

        <div class="mt-12">
            <!-- Step 1: Event Details -->
            <div x-show="currentStep === 1"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="space-y-8">
                
                <!-- Event Name -->
                <div>
                    <label for="eventName" class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" wire:model.live="eventName" id="eventName" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @error('eventName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Event Description -->
                <div>
                    <label for="eventDesc" class="block text-sm font-medium text-gray-700">Event Description</label>
                    <textarea wire:model.live="eventDesc" id="eventDesc" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    @error('eventDesc') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Event Duration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Duration</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model.live="isMultipleDay" value="0"
                                class="form-radio text-purple-600">
                            <span class="ml-2">Single Day</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model.live="isMultipleDay" value="1"
                                class="form-radio text-purple-600">
                            <span class="ml-2">Multiple Days</span>
                        </label>
                    </div>
                </div>

                <!-- Event Date(s) -->
                <div x-show="!isMultipleDay">
                    <label for="eventDate" class="block text-sm font-medium text-gray-700">Event Date</label>
                    <input type="date" wire:model.live="eventDate" id="eventDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @error('eventDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div x-show="isMultipleDay" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="eventStartDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" wire:model.live="eventStartDate" id="eventStartDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        @error('eventStartDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="eventEndDate" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" wire:model.live="eventEndDate" id="eventEndDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        @error('eventEndDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Event Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="eventStartTime" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" wire:model.live="eventStartTime" id="eventStartTime"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        @error('eventStartTime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="eventEndTime" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" wire:model.live="eventEndTime" id="eventEndTime"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        @error('eventEndTime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Step 2: Venue Information -->
            <div x-show="currentStep === 2"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="space-y-8">
                
                <!-- Event Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Type</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model.live="eventType" value="physical"
                                class="form-radio text-purple-600">
                            <span class="ml-2">Physical Event</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model.live="eventType" value="online"
                                class="form-radio text-purple-600">
                            <span class="ml-2">Online Event</span>
                        </label>
                    </div>
                </div>

                <!-- Physical Venue Selection -->
                <div x-show="eventType === 'physical'">
                    <label for="venueId" class="block text-sm font-medium text-gray-700">Select Venue</label>
                    <select wire:model.live="venueId" id="venueId"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="">Select a venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue['venue_id'] }}">
                                {{ $venue['venue_name'] }} ({{ $venue['venue_status'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('venueId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Online Platform Input -->
                <div x-show="eventType === 'online'">
                    <label for="onlinePlatform" class="block text-sm font-medium text-gray-700">Online Platform</label>
                    <input type="text" wire:model.live="onlinePlatform" id="onlinePlatform"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                        placeholder="e.g., Zoom, Google Meet">
                    @error('onlinePlatform') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Step 3: Event Image -->
            <div x-show="currentStep === 3"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="space-y-8">
                
                <div class="relative" x-data="{ hasImage: false, isHovered: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Image</label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg"
                        :class="{ 'border-purple-400': isHovered }"
                        @dragover.prevent="isHovered = true"
                        @dragleave.prevent="isHovered = false"
                        @drop.prevent="isHovered = false">

                        <div class="space-y-1 text-center">
                            <div x-show="!hasImage">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                        <span>Upload a file</span>
                                        <input type="file" wire:model.live="eventImage" class="sr-only" accept="image/*"
                                            @change="previewImage($event, 'imagePreview')">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                            
                            <div x-show="hasImage" class="relative">
                                <img x-ref="imagePreview" class="mx-auto max-h-64 rounded-lg">
                                <button type="button" @click="hasImage = false" class="absolute top-0 right-0 -mr-2 -mt-2 bg-red-100 text-red-600 rounded-full p-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('eventImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Step 4: Certificate -->
            <div x-show="currentStep === 4"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="space-y-8">
                
                <!-- Volunteer Capacity -->
                <div>
                    <label for="volunteerCapacity" class="block text-sm font-medium text-gray-700">Number of Volunteers Needed</label>
                    <input type="number" wire:model.live="volunteerCapacity" id="volunteerCapacity" min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @error('volunteerCapacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Certificate Template -->
                <div class="relative" x-data="{ hasCertTemplate: false, isCertHovered: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Template</label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg"
                        :class="{ 'border-purple-400': isCertHovered }"
                        @dragover.prevent="isCertHovered = true"
                        @dragleave.prevent="isCertHovered = false"
                        @drop.prevent="isCertHovered = false">

                        <div class="space-y-1 text-center">
                            <div x-show="!hasCertTemplate">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                        <span>Upload a file</span>
                                        <input type="file" wire:model.live="certTemplate" class="sr-only" accept="image/*"
                                            @change="previewImage($event, 'certPreview')">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                            
                            <div x-show="hasCertTemplate" class="relative">
                                <img x-ref="certPreview" class="mx-auto max-h-64 rounded-lg">
                                <button type="button" @click="hasCertTemplate = false" class="absolute top-0 right-0 -mr-2 -mt-2 bg-red-100 text-red-600 rounded-full p-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('certTemplate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Certificate Orientation -->
                <div>
                    <label for="certOrientation" class="block text-sm font-medium text-gray-700">Certificate Orientation</label>
                    <select wire:model.live="certOrientation" id="certOrientation"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="portrait">Portrait</option>
                        <option value="landscape">Landscape</option>
                    </select>
                    @error('certOrientation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-12 pt-6 border-t border-gray-200">
            <button type="button" 
                x-show="currentStep > 1"
                @click="$wire.previousStep()"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Previous
            </button>
            <div class="flex-1"></div>
            <button type="button" 
                x-show="currentStep < 4"
                @click="
                    if (currentStep === 1 && !validateStep1()) return;
                    if (currentStep === 2 && !validateStep2()) return;
                    $wire.nextStep()
                "
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Next
            </button>
            <button type="submit"
                x-show="currentStep === 4"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Create Event
            </button>
        </div>
    </form>

    <!-- Loading States -->
    <div wire:loading wire:target="save,nextStep,previousStep" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-purple-600"></div>
    </div>

    <!-- Validation Modal -->
    <div x-show="showModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto" 
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                aria-hidden="true"
                @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="modalTitle"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="modalMessage"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                        @click="showModal = false"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>