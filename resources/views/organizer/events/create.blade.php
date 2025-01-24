<x-organizer-layout>
    <x-slot:title>
        Create Event
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Create New Event</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
            <!-- Title and Description -->
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Create New Event</h2>
                <p class="text-gray-600">Fill in the details below to create your event</p>
            </div>

            <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" x-data="{
                currentStep: 1,
                isMultipleDay: false,
                eventType: 'physical',
                eventStartDate: '',
                eventEndDate: '',
                eventDate: '',
                hasImage: false,
                isHovered: false,
                volunteerCapacity: 0,
                showModal: false,
                modalTitle: '',
                modalMessage: '',
                hasCertTemplate: false,
                isCertHovered: false,
                venueType: 'internal',
                showValidationError(title, message) {
                    this.modalTitle = title;
                    this.modalMessage = message;
                    this.showModal = true;
                },
                previewImage(event, previewId) {
                    console.log('previewImage called', event, previewId);
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            console.log('FileReader onload', e);
                            this.$refs[previewId].src = e.target.result;
                            this.hasImage = true;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                updateEventType() {
                    this.eventType = document.querySelector('input[name=event_type]:checked').value;
                    if (this.eventType === 'physical') {
                        setTimeout(() => fetchAvailableVenues(), 100);
                    }
                },
                updateEventDate() {
                    if (this.isMultipleDay) {
                        this.eventStartDate = document.getElementById('event_start_date').value;
                        this.eventEndDate = document.getElementById('event_end_date').value;
                    } else {
                        this.eventDate = document.getElementById('event_date').value;
                    }
                },
                validateStep1() {
                    const startTime = document.getElementById('event_start_time').value;
                    const endTime = document.getElementById('event_end_time').value;
                    const eventDate = document.getElementById('event_date')?.value;
                    const startDate = document.getElementById('event_start_date')?.value;
                    const endDate = document.getElementById('event_end_date')?.value;
                    const eventName = document.getElementById('event_name').value;
                    const eventDesc = document.getElementById('event_desc').value;

                    if (!eventName || !eventDesc || !startTime || !endTime) {
                        this.showValidationError(
                            'Missing Required Fields',
                            'Please fill in all required fields (Event Name, Description, Start Time, and End Time)'
                        );
                        return false;
                    }

                    if (this.isMultipleDay) {
                        if (!startDate || !endDate) {
                            this.showValidationError(
                                'Date Selection Required',
                                'Please select both start and end dates'
                            );
                            return false;
                        }
                        if (startDate > endDate) {
                            this.showValidationError(
                                'Invalid Date Range',
                                'End date cannot be earlier than start date'
                            );
                            return false;
                        }
                    } else {
                        if (!eventDate) {
                            this.showValidationError(
                                'Date Required',
                                'Please select an event date'
                            );
                            return false;
                        }
                    }

                    if (startTime >= endTime) {
                        this.showValidationError(
                            'Invalid Time Range',
                            'End time must be later than start time'
                        );
                        return false;
                    }

                    return true;
                },
                validateStep2() {
                    if (this.eventType === 'physical') {
                        if (this.venueType === 'faculty' && !document.getElementById('venue_id').value) {
                            this.showValidationError(
                                'Venue Required',
                                'Please select a faculty venue before proceeding.'
                            );
                            return false;
                        } else if (this.venueType === 'other') {
                            const venueName = document.getElementById('other_venue_name').value;
                            if (!venueName) {
                                this.showValidationError(
                                    'Venue Name Required',
                                    'Please provide the venue name.'
                                );
                                return false;
                            }
                        }
                    } else if (this.eventType === 'online') {
                        const onlinePlatform = document.getElementById('online_platform');
                        if (!onlinePlatform.value.trim()) {
                            this.showValidationError(
                                'Online Platform Required',
                                'Please specify the online platform (e.g., Zoom, Microsoft Teams) before proceeding.'
                            );
                            return false;
                        }
                    }
                    return true;
                },
                nextStep() {
                    if (this.currentStep === 1) {
                        if (!this.validateStep1()) return;
                    } else if (this.currentStep === 2) {
                        if (!this.validateStep2()) return;
                    }
                    
                    if (this.currentStep < 4) {
                        this.currentStep++;
                        if (this.currentStep === 2 && this.eventType === 'physical') {
                            setTimeout(() => fetchAvailableVenues(), 100);
                        }
                    }
                },
                prevStep() {
                    if (this.currentStep > 1) this.currentStep--;
                },
                handleImageDrop(event, previewId) {
                    console.log('handleImageDrop called', event, previewId);
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const input = document.getElementById('event_img');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        this.previewImage({ target: { files: [file] } }, previewId);
                        this.hasImage = true;
                    }
                },
                handleSubmit(e) {
                    this.updateEventDate();
                    return true;
                },
                handleImageDrop(event, previewId) {
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const input = previewId === 'cert_template_preview' ? 
                            document.getElementById('cert_template') : 
                            document.getElementById('event_img');
                        input.files = event.dataTransfer.files;
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.$refs[previewId].src = e.target.result;
                            if (previewId === 'cert_template_preview') {
                                this.hasCertTemplate = true;
                            } else {
                                this.hasImage = true;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                },
                previewImage(event, previewId) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.$refs[previewId].src = e.target.result;
                            if (previewId === 'cert_template_preview') {
                                this.hasCertTemplate = true;
                            } else {
                                this.hasImage = true;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }" @submit="handleSubmit" class="max-w-4xl mx-auto">
                @csrf

                <!-- Progress Bar -->
                <div class="max-w-2xl mx-auto mb-12 border-b-2 pb-6">
                    <div class="flex pb-3">
                        <div class="flex-1"></div>

                        <!-- Step 1 Circle -->
                        <div class="flex-1">
                            <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300"
                                 x-bind:class="{ 'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 1, 'bg-white border-2 border-gray-300': currentStep < 1 }">
                                <span class="text-center w-full" x-bind:class="{ 'text-white': currentStep >= 1, 'text-gray-600': currentStep < 1 }">
                                    <i class="fas fa-info-circle w-full text-xl" x-show="currentStep > 1"></i>
                                    <span x-show="currentStep <= 1">1</span>
                                </span>
                            </div>
                        </div>

                        <!-- Line between 1 and 2 -->
                        <div class="w-1/6 align-center items-center align-middle content-center flex">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-purple-600 h-1.5 rounded-full transition-all duration-500 ease-in-out"
                                     x-bind:style="'width: ' + (currentStep > 1 ? '100' : '0') + '%'"></div>
                            </div>
                        </div>

                        <!-- Step 2 Circle -->
                        <div class="flex-1">
                            <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300"
                                 x-bind:class="{ 'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 2, 'bg-white border-2 border-gray-300': currentStep < 2 }">
                                <span class="text-center w-full" x-bind:class="{ 'text-white': currentStep >= 2, 'text-gray-600': currentStep < 2 }">
                                    <i class="fas fa-map-marker-alt w-full text-xl" x-show="currentStep > 2"></i>
                                    <span x-show="currentStep <= 2">2</span>
                                </span>
                            </div>
                        </div>

                        <!-- Line between 2 and 3 -->
                        <div class="w-1/6 align-center items-center align-middle content-center flex">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-purple-600 h-1.5 rounded-full transition-all duration-500 ease-in-out"
                                     x-bind:style="'width: ' + (currentStep > 2 ? '100' : '0') + '%'"></div>
                            </div>
                        </div>

                        <!-- Step 3 Circle -->
                        <div class="flex-1">
                            <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300"
                                 x-bind:class="{ 'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 3, 'bg-white border-2 border-gray-300': currentStep < 3 }">
                                <span class="text-center w-full" x-bind:class="{ 'text-white': currentStep >= 3, 'text-gray-600': currentStep < 3 }">
                                    <i class="fas fa-users w-full text-xl" x-show="currentStep > 3"></i>
                                    <span x-show="currentStep <= 3">3</span>
                                </span>
                            </div>
                        </div>

                        <!-- Line between 3 and 4 -->
                        <div class="w-1/6 align-center items-center align-middle content-center flex">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-purple-600 h-1.5 rounded-full transition-all duration-500 ease-in-out"
                                     x-bind:style="'width: ' + (currentStep > 3 ? '100' : '0') + '%'"></div>
                            </div>
                        </div>

                        <!-- Step 4 Circle -->
                        <div class="flex-1">
                            <div class="w-12 h-12 mx-auto rounded-full text-lg flex items-center transition-all duration-300"
                                 x-bind:class="{ 'bg-purple-600 shadow-lg ring-2 ring-purple-400 ring-offset-2': currentStep >= 4, 'bg-white border-2 border-gray-300': currentStep < 4 }">
                                <span class="text-center w-full" x-bind:class="{ 'text-white': currentStep >= 4, 'text-gray-600': currentStep < 4 }">
                                    <i class="fas fa-certificate w-full text-xl" x-show="currentStep > 4"></i>
                                    <span x-show="currentStep <= 4">4</span>
                                </span>
                            </div>
                        </div>

                        <div class="flex-1"></div>
                    </div>
                    
                    <!-- Step Labels -->
                    <div class="flex text-sm content-center text-center mt-4">
                        <div class="w-1/4">
                            <span class="transition-all duration-300"
                                  x-bind:class="{ 'text-purple-600 font-semibold': currentStep >= 1, 'text-gray-600': currentStep < 1 }">
                                Event Details
                            </span>
                        </div>
                        <div class="w-1/4">
                            <span class="transition-all duration-300"
                                  x-bind:class="{ 'text-purple-600 font-semibold': currentStep >= 2, 'text-gray-600': currentStep < 2 }">
                                Venue Information
                            </span>
                        </div>
                        <div class="w-1/4">
                            <span class="transition-all duration-300"
                                  x-bind:class="{ 'text-purple-600 font-semibold': currentStep >= 3, 'text-gray-600': currentStep < 3 }">
                                AJK Request
                            </span>
                        </div>
                        <div class="w-1/4">
                            <span class="transition-all duration-300"
                                  x-bind:class="{ 'text-purple-600 font-semibold': currentStep >= 4, 'text-gray-600': currentStep < 4 }">
                                E-Certificate
                            </span>
                        </div>
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
                    <div class="relative">
                        <label for="event_name" class="block text-sm font-medium text-gray-700 mb-1">Event Name</label>
                        <input type="text" id="event_name" name="event_name" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                required>
                    </div>

                    <!-- Event Duration -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Event Duration</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="event_duration" value="single" @click="isMultipleDay = false" :checked="!isMultipleDay" 
                                        class="form-radio text-purple-600 focus:ring-purple-500">
                                <span class="ml-2">One Day</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="event_duration" value="multiple" @click="isMultipleDay = true" :checked="isMultipleDay"
                                        class="form-radio text-purple-600 focus:ring-purple-500">
                                <span class="ml-2">Multiple Days</span>
                            </label>
                        </div>
                    </div>

                    <!-- Event Date(s) -->
                    <div x-show="!isMultipleDay" class="relative">
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                        <input type="date" id="event_date" name="event_date" x-bind:required="!isMultipleDay"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                @change="fetchAvailableVenues">
                    </div>

                    <div x-show="isMultipleDay" class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <label for="event_start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="event_start_date" name="event_start_date" x-bind:required="isMultipleDay"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    @change="fetchAvailableVenues">
                        </div>
                        <div class="relative">
                            <label for="event_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="event_end_date" name="event_end_date" x-bind:required="isMultipleDay"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    @change="fetchAvailableVenues">
                        </div>
                    </div>

                    <!-- Event Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <label for="event_start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input type="time" id="event_start_time" name="event_start_time" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    @change="fetchAvailableVenues">
                        </div>
                        <div class="relative">
                            <label for="event_end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time" id="event_end_time" name="event_end_time" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    @change="fetchAvailableVenues">
                        </div>
                    </div>

                    <!-- Event Description -->
                    <div class="relative">
                        <label for="event_desc" class="block text-sm font-medium text-gray-700 mb-1">Event Description</label>
                        <textarea id="event_desc" name="event_desc" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>

                    <!-- Event Image Upload -->
                    <div class="relative" x-data="{ hasImage: false, isHovered: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Image</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors duration-200"
                             @dragover.prevent="isHovered = true"
                             @dragleave.prevent="isHovered = false"
                             @drop.prevent="isHovered = false; handleImageDrop($event, 'event_img_preview')"
                             :class="{ 'border-purple-400 bg-purple-50': isHovered }">

                            <div class="space-y-2 text-center">
                                <!-- Upload UI -->
                                <div x-show="!hasImage" class="flex flex-col items-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="event_img" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload an image</span>
                                            <input id="event_img" name="event_img" type="file" class="sr-only" accept="image/*"
                                                   @change="previewImage($event, 'event_img_preview')">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                                </div>

                                <!-- Image Preview -->
                                <div x-show="hasImage" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="relative group">
                                    <img x-ref="event_img_preview" 
                                         class="max-h-64 rounded-lg object-contain mx-auto" />
                                    
                                    <!-- Remove Image Button -->
                                    <button type="button" 
                                            @click="hasImage = false; $refs.event_img_preview.src = ''; document.getElementById('event_img').value = '';"
                                            class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('event_img')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Step 2: Venue Information -->
                <div x-show="currentStep === 2"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        class="space-y-8">
                    <!-- Event Type -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Event Type</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="event_type" value="physical" @click="updateEventType()"
                                        :checked="eventType === 'physical'"
                                        class="form-radio text-purple-600 focus:ring-purple-500">
                                <span class="ml-2">Physical</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="event_type" value="online" @click="updateEventType()"
                                        :checked="eventType === 'online'"
                                        class="form-radio text-purple-600 focus:ring-purple-500">
                                <span class="ml-2">Online</span>
                            </label>
                        </div>
                    </div>

                    <!-- Online Platform -->
                    <div x-show="eventType === 'online'" class="relative">
                        <label for="online_platform" class="block text-sm font-medium text-gray-700 mb-1">Meeting Platform</label>
                        <input type="text" id="online_platform" name="online_platform"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                placeholder="e.g., Zoom, Microsoft Teams">
                    </div>

                    <!-- Venue Selection -->
                    <div x-show="eventType === 'physical'" class="space-y-4">
                        <!-- Venue Type Selection -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Venue Type</label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="venue_type" value="faculty" 
                                           x-model="venueType"
                                           class="form-radio text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2">Faculty Venue</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="venue_type" value="other" 
                                           x-model="venueType"
                                           class="form-radio text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2">Other Venue</span>
                                </label>
                            </div>
                        </div>

                        <!-- Faculty Venue Dropdown -->
                        <div x-show="venueType === 'faculty'" class="relative">
                            <label for="venue_id" class="block text-sm font-medium text-gray-700 mb-1">Select Faculty Venue</label>
                            <select id="venue_id" name="venue_id"
                                    x-bind:required="venueType === 'faculty'"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Select a venue</option>
                            </select>
                            @error('venue_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Other Venue Input -->
                        <div x-show="venueType === 'other'" class="relative">
                            <label for="other_venue_name" class="block text-sm font-medium text-gray-700 mb-1">Other Venue Name</label>
                            <input type="text" id="other_venue_name" name="other_venue_name"
                                   x-bind:required="venueType === 'other'"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                   placeholder="Enter venue name">
                            @error('other_venue_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Step 3: AJK Request -->
                <div x-show="currentStep === 3"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        class="space-y-8">
                    <!-- Volunteer Capacity -->
                    <div class="relative">
                        <label for="volunteer_capacity" class="block text-sm font-medium text-gray-700 mb-1">Any Volunteers Needed?</label>
                        <input type="number" id="volunteer_capacity" name="volunteer_capacity" min="0" value="0"
                                x-model="volunteerCapacity"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <p class="mt-1 text-sm text-gray-500">Enter 0 if no volunteers are needed for this event</p>
                    </div>

                    <!-- Notes to Volunteer -->
                    <div class="relative" x-show="parseInt(volunteerCapacity) > 0">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes to Volunteers</label>
                        <textarea id="notes" name="notes" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                placeholder="Describe the roles, responsibilities, and requirements for volunteers"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Include any specific requirements, duties, or important information for volunteers</p>
                    </div>
                </div>

                <!-- Step 4: E-Certificate -->
                <div x-show="currentStep === 4" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column: Upload Section -->
                        <div class="space-y-6">
                            <!-- Certificate Template Upload -->
                            <div class="relative" x-data="{ hasCertTemplate: false, isCertHovered: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Certificate Template 
                                    <span class="text-sm text-gray-500">(Optional)</span>
                                </label>
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors duration-200"
                                     @dragover.prevent="isCertHovered = true"
                                     @dragleave.prevent="isCertHovered = false"
                                     @drop.prevent="isCertHovered = false; handleImageDrop($event, 'cert_template_preview')"
                                     :class="{ 'border-purple-400 bg-purple-50': isCertHovered }">

                                    <div class="space-y-2 text-center">
                                        <!-- Upload UI -->
                                        <div x-show="!hasCertTemplate" class="flex flex-col items-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="cert_template" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                                    <span>Upload a template</span>
                                                    <input id="cert_template" 
                                                           name="cert_template" 
                                                           type="file" 
                                                           class="sr-only" 
                                                           accept="image/*"
                                                           @change="previewImage($event, 'cert_template_preview'); hasCertTemplate = true">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                                        </div>

                                        <!-- Template Preview -->
                                        <div x-show="hasCertTemplate" class="relative group">
                                            <img x-ref="cert_template_preview" 
                                                 class="max-h-64 rounded-lg object-contain mx-auto" 
                                                 alt="Certificate template preview" />
                                            
                                            <!-- Remove Template Button -->
                                            <button type="button" 
                                                    @click="hasCertTemplate = false; $refs.cert_template_preview.src = ''; document.getElementById('cert_template').value = '';"
                                                    class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @error('cert_template')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Certificate Orientation -->
                            <div class="relative">
                                <label for="cert_orientation" class="block text-sm font-medium text-gray-700 mb-1">Certificate Orientation</label>
                                <select id="cert_orientation" name="cert_orientation"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="portrait">Portrait</option>
                                    <option value="landscape">Landscape</option>
                                </select>
                            </div>

                            <!-- Preview of Default Template -->
                            <div class="mt-6">
                                <p class="text-sm font-medium text-gray-700 mb-2">Default Template Preview:</p>
                                <img src="{{ asset('images/default-certificate-portrait.png') }}" 
                                     alt="Default Certificate Template" 
                                     class="w-full rounded-lg border border-gray-200 shadow-sm">
                                <p class="mt-2 text-xs text-gray-500 text-center">This template will be used if no custom template is uploaded</p>
                            </div>
                        </div>

                        <!-- Right Column: Guidelines -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800 mb-4">Template Guidelines</h3>
                            
                            <div class="space-y-4 text-sm text-purple-900">
                                <div>
                                    <p class="font-medium mb-2">Required Specifications:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>File Format: PNG or JPG</li>
                                        <li>Resolution: Minimum 2000 x 1414 pixels (300 DPI)</li>
                                        <li>Maximum file size: 10MB</li>
                                    </ul>
                                </div>

                                <div>
                                    <p class="font-medium mb-2">Content Placement Areas:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Student Name: Center area, ~38% from top</li>
                                        <li>Matric Number: Below name, ~44% from top</li>
                                        <li>Event Name: ~53% from top</li>
                                        <li>Event Date: ~72% from top</li>
                                        <li>Unique Code: Bottom area, ~2% from bottom</li>
                                    </ul>
                                </div>

                                <!-- Add Design Tips Section -->
                                <div>
                                    <p class="font-medium mb-2">Design Tips:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Use high contrast colors for better text visibility</li>
                                        <li>Leave enough padding around text areas</li>
                                        <li>Consider both light and dark text colors</li>
                                        <li>Avoid placing important elements near edges</li>
                                    </ul>
                                </div>

                                <!-- Add Common Mistakes to Avoid -->
                                <div>
                                    <p class="font-medium mb-2">Common Mistakes to Avoid:</p>
                                    <ul class="list-disc pl-5 space-y-1 text-red-800">
                                        <li>Placing text too close to borders</li>
                                        <li>Using low-resolution images</li>
                                        <li>Overcrowding the design</li>
                                        <li>Using hard-to-read fonts</li>
                                    </ul>
                                </div>

                                <div class="flex items-start space-x-2 bg-white p-3 rounded-md border border-purple-100">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm">Ensure all content areas are clear and unobstructed in your template design.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-12 pt-6 border-t border-gray-200">
                    <button type="button" 
                            x-show="currentStep > 1" 
                            @click="prevStep()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Previous
                    </button>
                    <div class="flex justify-end">
                        <button type="button" 
                                x-show="currentStep < 4" 
                                @click="nextStep()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Next
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="submit" 
                                x-show="currentStep === 4" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Create Event
                            <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Add this modal markup just before closing the form tag -->
                <div x-show="showModal" 
                class="fixed inset-0 z-50 overflow-y-auto" 
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <!-- Background overlay -->
                        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showModal = false"></div>

                        <!-- Modal panel -->
                        <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <div class="sm:flex sm:items-start">
                                <!-- Warning Icon -->
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                
                                <!-- Modal Content -->
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900" x-text="modalTitle"></h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" x-text="modalMessage"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Footer -->
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="button" 
                                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="showModal = false">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Script to Handle AJAX Request for Available Venues -->
    <script>
        function fetchAvailableVenues() {
            const eventType = document.querySelector('input[name="event_type"]:checked').value;
            const eventDuration = document.querySelector('input[name="event_duration"]:checked').value;
            const eventDate = document.getElementById('event_date') ? document.getElementById('event_date').value : null;
            const eventStartDate = document.getElementById('event_start_date') ? document.getElementById('event_start_date').value : null;
            const eventEndDate = document.getElementById('event_end_date') ? document.getElementById('event_end_date').value : null;
            const eventStartTime = document.getElementById('event_start_time').value;
            const eventEndTime = document.getElementById('event_end_time').value;

            // Check if required fields are filled
            if (!eventStartTime || !eventEndTime || (!eventDate && (!eventStartDate || !eventEndDate))) {
                console.log('Required date/time fields are not filled');
                return;
            }

            const data = {
                event_type: eventType,
                event_duration: eventDuration,
                event_date: eventDate,
                event_start_date: eventStartDate,
                event_end_date: eventEndDate,
                event_start_time: eventStartTime,
                event_end_time: eventEndTime
            };

            console.log('Sending request with data:', data);

            fetch("{{ route('organizer.venues.available') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received response:', data);
                const venueSelect = document.getElementById('venue_id');
                venueSelect.innerHTML = '<option value="">Select a venue</option>';
                
                if (data.availableVenues && Array.isArray(data.availableVenues)) {
                    data.availableVenues.forEach(venue => {
                        const option = document.createElement('option');
                        option.value = venue.venue_id;
                        option.textContent = venue.venue_name;
                        venueSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching available venues:', error);
                const venueSelect = document.getElementById('venue_id');
                venueSelect.innerHTML = '<option value="">Error loading venues</option>';
            });
        }
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('imageUpload', () => ({
                isHovered: false,
                previewImage(event, previewId) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.$refs[previewId].src = e.target.result;
                            this.$refs[previewId].style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                },
                handleImageDrop(event, previewId) {
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const input = document.getElementById(previewId === 'event_img_preview' ? 'event_img' : 'cert_template');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.$refs[previewId].src = e.target.result;
                            this.$refs[previewId].style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }));
        });
    </script>

    <script>
        // Get today's date in YYYY-MM-DD format
        const today = new Date().toISOString().split('T')[0];
        
        // Set min attribute for date inputs
        document.addEventListener('DOMContentLoaded', function() {
            // For single day events
            const eventDate = document.getElementById('event_date');
            if (eventDate) {
                eventDate.min = today;
            }
            
            // For multiple day events
            const startDate = document.getElementById('event_start_date');
            const endDate = document.getElementById('event_end_date');
            
            if (startDate) {
                startDate.min = today;
            }
            
            if (endDate) {
                endDate.min = today;
            }
            
            // Update end date min value when start date changes
            if (startDate && endDate) {
                startDate.addEventListener('change', function() {
                    endDate.min = this.value;
                    if (endDate.value && endDate.value < this.value) {
                        endDate.value = this.value;
                    }
                });
            }
        });
    </script>

    <x-toast />
</x-organizer-layout>