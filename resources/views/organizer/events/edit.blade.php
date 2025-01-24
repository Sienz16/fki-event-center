<x-organizer-layout>
    <x-slot:title>
        Edit Event
    </x-slot>

    <x-slot:meta>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Event</h1>
    </x-slot>

    @push('styles')
        <style>
            [x-cloak] { display: none !important; }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-25px); }
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
        </style>
    @endpush

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <!-- Title and Description -->
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Edit Event</h2>
                    <p class="text-gray-600">Update the details of your event</p>
                </div>

                <form action="{{ route('organizer.events.update', $event->event_id) }}" 
                    method="POST" 
                    enctype="multipart/form-data" 
                    x-data="{ 
                        isMultipleDay: '{{ $event->event_start_date && $event->event_end_date ? 'multiple' : 'single' }}' === 'multiple',
                        eventType: '{{ $event->event_type }}',
                        currentVenueId: '{{ $event->venue_id }}',
                        venueType: '{{ $event->venue_type ?? 'faculty' }}',
                        
                        updateEventType() {
                            this.eventType = document.querySelector('input[name=event_type]:checked').value;
                            // Only fetch venues if it's a physical event with faculty venue type
                            if (this.eventType === 'physical' && this.venueType === 'faculty') {
                                setTimeout(() => fetchAvailableVenues(), 100);
                            }
                        },

                        async handleSubmit(e) {
                            e.preventDefault();
                            if (this.eventType !== 'physical') {
                                e.target.submit();
                                return;
                            }
                            
                            // Only check venue if it's a faculty venue
                            if (this.venueType === 'faculty') {
                                const venueSelect = document.getElementById('venue_id');
                                if (!venueSelect) return true;
                                this.currentVenueId = venueSelect.value;
                                
                                // Check if a venue is selected
                                if (!venueSelect.value) {
                                    alert('Please select a venue');
                                    return;
                                }
                            }
                            
                            const startTime = document.getElementById('event_start_time').value;
                            const endTime = document.getElementById('event_end_time').value;
                            const eventDate = document.getElementById('event_date')?.value;
                            const startDate = document.getElementById('event_start_date')?.value;
                            const endDate = document.getElementById('event_end_date')?.value;
                            
                            if (!startTime || !endTime) return true;
                            if (this.isMultipleDay && (!startDate || !endDate)) return true;
                            if (!this.isMultipleDay && !eventDate) return true;

                            try {
                                const response = await fetch('{{ route('organizer.venues.available') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        event_duration: this.isMultipleDay ? 'multiple' : 'single',
                                        event_date: eventDate,
                                        event_start_date: startDate,
                                        event_end_date: endDate,
                                        event_start_time: startTime,
                                        event_end_time: endTime,
                                        event_id: '{{ $event->event_id }}',
                                        current_venue_id: this.currentVenueId
                                    })
                                });

                                const data = await response.json();
                                
                                if (!data.currentVenueAvailable) {
                                    Alpine.store('modalStore').showVenueUnavailableModal = true;
                                    this.updateVenueOptions(data.availableVenues);
                                    venueSelect.value = '';
                                    return;
                                }
                                e.target.submit();
                            } catch (error) {
                                console.error('Error checking venue availability:', error);
                                alert('Error checking venue availability. Please try again.');
                            }
                        },

                        updateVenueOptions(venues) {
                            const select = document.getElementById('venue_id');
                            select.innerHTML = '';
                            select.appendChild(new Option('Select a venue', ''));
                            venues.forEach(venue => {
                                select.appendChild(new Option(venue.venue_name, venue.venue_id));
                            });
                        }
                    }" 
                    @submit="handleSubmit">
                    @csrf
                    @method('PUT')

                    <!-- Section: Event Details -->
                    <div class="mb-12">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-2 border-b">Event Details</h3>
                        <div class="space-y-8">
                            <!-- Event Name -->
                            <div>
                                <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
                                <input type="text" id="event_name" name="event_name" value="{{ old('event_name', $event->event_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" required>
                            </div>

                            <!-- Event Description -->
                            <div>
                                <label for="event_desc" class="block text-sm font-medium text-gray-700">Event Description</label>
                                <textarea id="event_desc" name="event_desc" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" required>{{ old('event_desc', $event->event_desc) }}</textarea>
                            </div>

                            <!-- Event Duration -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Event Duration</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="event_duration" value="single" @click="isMultipleDay = false" :checked="!isMultipleDay" class="form-radio text-purple-600">
                                        <span class="ml-2">One Day</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="event_duration" value="multiple" @click="isMultipleDay = true" :checked="isMultipleDay" class="form-radio text-purple-600">
                                        <span class="ml-2">Multiple Days</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Single Day Event Date -->
                            <div x-show="!isMultipleDay">
                                <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date</label>
                                <input type="date" 
                                       id="event_date" 
                                       name="event_date" 
                                       value="{{ old('event_date', $event->event_date) }}"
                                       x-bind:required="!isMultipleDay" 
                                       @change="if (eventType === 'physical' && venueType === 'faculty') fetchAvailableVenues()"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <!-- Multiple Day Event Dates -->
                            <div x-show="isMultipleDay" class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="event_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" 
                                           id="event_start_date" 
                                           name="event_start_date" 
                                           value="{{ old('event_start_date', $event->event_start_date) }}"
                                           x-bind:required="isMultipleDay" 
                                           @change="if (eventType === 'physical' && venueType === 'faculty') fetchAvailableVenues()"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                </div>
                                <div>
                                    <label for="event_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" 
                                           id="event_end_date" 
                                           name="event_end_date" 
                                           value="{{ old('event_end_date', $event->event_end_date) }}"
                                           x-bind:required="isMultipleDay" 
                                           @change="if (eventType === 'physical' && venueType === 'faculty') fetchAvailableVenues()"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                </div>
                            </div>

                            <!-- Event Times -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="event_start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                                    <input type="time" 
                                           id="event_start_time" 
                                           name="event_start_time" 
                                           value="{{ \Carbon\Carbon::parse($event->event_start_time)->format('H:i') }}"
                                           @change="if (eventType === 'physical' && venueType === 'faculty') fetchAvailableVenues()"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" 
                                           required>
                                </div>
                                <div>
                                    <label for="event_end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                                    <input type="time" 
                                           id="event_end_time" 
                                           name="event_end_time" 
                                           value="{{ \Carbon\Carbon::parse($event->event_end_time)->format('H:i') }}"
                                           @change="if (eventType === 'physical' && venueType === 'faculty') fetchAvailableVenues()"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" 
                                           required>
                                </div>
                            </div>

                            <!-- Event Image Upload -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Event Image</label>
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors duration-200"
                                     x-data="{ 
                                        hasImage: {{ $event->event_img ? 'true' : 'false' }}, 
                                        isHovered: false,
                                        handleDrop(event) {
                                            event.preventDefault();
                                            const file = event.dataTransfer.files[0];
                                            if (file && file.type.startsWith('image/')) {
                                                const input = document.getElementById('event_img');
                                                const dataTransfer = new DataTransfer();
                                                dataTransfer.items.add(file);
                                                input.files = dataTransfer.files;
                                                
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    this.$refs.event_img_preview.src = e.target.result;
                                                    this.hasImage = true;
                                                };
                                                reader.readAsDataURL(file);
                                            }
                                            this.isHovered = false;
                                        },
                                        handleFileSelect(event) {
                                            const file = event.target.files[0];
                                            if (file && file.type.startsWith('image/')) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    this.$refs.event_img_preview.src = e.target.result;
                                                    this.hasImage = true;
                                                };
                                                reader.readAsDataURL(file);
                                            }
                                        }
                                     }"
                                     @dragover.prevent="isHovered = true"
                                     @dragleave.prevent="isHovered = false"
                                     @drop.prevent="handleDrop($event)"
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
                                                           @change="handleFileSelect($event)">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                                        </div>

                                        <!-- Image Preview -->
                                        <div x-show="hasImage" class="relative group">
                                            <img x-ref="event_img_preview" 
                                                 src="{{ $event->event_img ? asset('storage/' . $event->event_img) : '' }}" 
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
                            </div>
                        </div>
                    </div>

                    <!-- Section: Venue Information -->
                    <div class="mb-12">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-2 border-b">Venue Information</h3>
                        <div class="space-y-8">
                            <!-- Event Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Event Type</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" 
                                               name="event_type" 
                                               value="physical" 
                                               x-model="eventType"
                                               @change="updateEventType"
                                               class="form-radio text-purple-600">
                                        <span class="ml-2">Physical</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" 
                                               name="event_type" 
                                               value="online" 
                                               x-model="eventType"
                                               @change="updateEventType"
                                               class="form-radio text-purple-600">
                                        <span class="ml-2">Online</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Venue Type Selection (for physical events) -->
                            <div x-show="eventType === 'physical'" x-transition>
                                <label class="block text-sm font-medium text-gray-700">Venue Type</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" 
                                               name="venue_type" 
                                               value="faculty" 
                                               x-model="venueType"
                                               @change="setTimeout(() => fetchAvailableVenues(true), 100)"
                                               class="form-radio text-purple-600">
                                        <span class="ml-2">Faculty Venue</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="venue_type" value="other" 
                                               x-model="venueType"
                                               class="form-radio text-purple-600">
                                        <span class="ml-2">Other Venue</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Faculty Venue Selection -->
                            <div x-show="eventType === 'physical' && venueType === 'faculty'" x-transition>
                                <label for="venue_id" class="block text-sm font-medium text-gray-700">Select Venue</label>
                                <select id="venue_id" 
                                        name="venue_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="">Select a venue</option>
                                    @foreach($availableVenues as $venue)
                                        <option value="{{ $venue->venue_id }}" {{ $event->venue_id == $venue->venue_id ? 'selected' : '' }}>
                                            {{ $venue->venue_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Other Venue Input -->
                            <div x-show="eventType === 'physical' && venueType === 'other'" x-transition>
                                <label for="other_venue_name" class="block text-sm font-medium text-gray-700">Venue Name</label>
                                <input type="text" 
                                       id="other_venue_name" 
                                       name="other_venue_name" 
                                       value="{{ old('other_venue_name', $event->other_venue_name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                       placeholder="Enter venue name">
                            </div>

                            <!-- Online Platform Input -->
                            <div x-show="eventType === 'online'" x-transition>
                                <label for="online_platform" class="block text-sm font-medium text-gray-700">Online Platform</label>
                                <input type="text" 
                                       id="online_platform" 
                                       name="online_platform" 
                                       value="{{ old('online_platform', $event->online_platform) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                       placeholder="e.g., Zoom, Microsoft Teams">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Volunteer Information -->
                    <div class="mb-12">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-2 border-b">Volunteer Information</h3>
                        <div class="space-y-8" x-data="{ 
                            volunteerCapacity: {{ $event->volunteers->isNotEmpty() ? $event->volunteers->first()->volunteer_capacity : 0 }}
                        }">
                            <!-- Volunteer Capacity -->
                            <div>
                                <label for="volunteer_capacity" class="block text-sm font-medium text-gray-700">
                                    Number of Volunteers Needed
                                </label>
                                <div class="mt-1">
                                    <input type="number" 
                                           id="volunteer_capacity" 
                                           name="volunteer_capacity" 
                                           min="0" 
                                           value="{{ $event->volunteers->isNotEmpty() ? $event->volunteers->first()->volunteer_capacity : 0 }}"
                                           x-model.number="volunteerCapacity"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Enter 0 if no volunteers are needed for this event</p>
                            </div>

                            <!-- Notes to Volunteer -->
                            <div x-show="parseInt(volunteerCapacity) > 0" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100">
                                <label for="notes" class="block text-sm font-medium text-gray-700">
                                    Notes to Volunteers
                                </label>
                                <div class="mt-1">
                                    <textarea id="notes" 
                                              name="notes" 
                                              rows="4"
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                              placeholder="Describe the roles, responsibilities, and requirements for volunteers">{{ $event->volunteers->isNotEmpty() ? $event->volunteers->first()->notes : '' }}</textarea>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Include any specific requirements, duties, or important information for volunteers
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section: E-Certificate -->
                    <div class="mb-12">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-2 border-b">E-Certificate</h3>
                        <div class="space-y-8">
                            <!-- Certificate Template -->
                            <div>
                                <label for="cert_template" class="block text-sm font-medium text-gray-700">Certificate Template</label>
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
                                     x-data="{ hasTemplate: {{ $event->cert_template ? 'true' : 'false' }}, isCertHovered: false }"
                                     x-on:dragover.prevent="isCertHovered = true"
                                     x-on:dragleave.prevent="isCertHovered = false"
                                     x-on:drop.prevent="isCertHovered = false"
                                     @drop="handleDrop($event, 'cert_template', 'cert_template_preview'); isCertHovered = false"
                                     :class="{ 'border-purple-500 bg-purple-50': isCertHovered }">
                                    <div class="space-y-2 text-center">
                                        <!-- Upload UI -->
                                        <div x-show="!hasTemplate" class="flex flex-col items-center">
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
                                                           @change="handleFileSelect($event, 'cert_template_preview'); hasTemplate = true">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                                        </div>

                                        <!-- Template Preview -->
                                        <div x-show="hasTemplate" class="relative group">
                                            <img x-ref="cert_template_preview" 
                                                 src="{{ $event->cert_template ? asset('storage/' . $event->cert_template) : '' }}" 
                                                 class="max-h-64 rounded-lg object-contain mx-auto" />
                                            <!-- Remove Template Button -->
                                            <button type="button" 
                                                    @click="hasTemplate = false; $refs.cert_template_preview.src = ''; document.getElementById('cert_template').value = '';"
                                                    class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Certificate Orientation -->
                            <div>
                                <label for="cert_orientation" class="block text-sm font-medium text-gray-700">Certificate Orientation</label>
                                <select id="cert_orientation" name="cert_orientation"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="portrait" {{ $event->cert_orientation == 'portrait' ? 'selected' : '' }}>Portrait</option>
                                    <option value="landscape" {{ $event->cert_orientation == 'landscape' ? 'selected' : '' }}>Landscape</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-12 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Update Event
                            <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Venue Unavailable Warning Modal -->
        <div x-data="{
            audio: null,
            init() {
                // Preload the audio when the component initializes
                this.audio = new Audio('/sounds/notification.mp3');
                this.audio.volume = 1.0;
                this.audio.load(); // Preload the audio file
            },
            playAlertSound() {
                if (this.audio) {
                    this.audio.currentTime = 0; // Reset to start
                    this.audio.play().catch(e => console.log('Audio play failed:', e));
                }
            }
        }"
        x-init="
            init();
            $watch('$store.modalStore.showVenueUnavailableModal', value => {
                if (value) playAlertSound();
            });
        "
        x-cloak
        x-show="$store.modalStore?.showVenueUnavailableModal" 
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
            
            <!-- Overlay with fade -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
            </div>

            <!-- Modal Content with dramatic entrance -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-8 pt-5 text-center shadow-xl sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-full scale-50"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-full scale-50">
                    
                    <!-- Close Button with hover animation -->
                    <button type="button" 
                            @click="$store.modalStore.showVenueUnavailableModal = false"
                            class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="mx-auto flex items-center justify-center mb-6">
                        <svg class="h-24 w-24 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>

                    <div class="text-center px-4">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Venue No Longer Available
                        </h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 mx-auto max-w-md">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                The previously selected venue is no longer available for the new date/time. Please select a different venue from the updated list of available venues below.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to Handle AJAX Request for Available Venues -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('modalStore', {
                showWarningModal: false,
                showVenueUnavailableModal: false
            });
        });

        function fetchAvailableVenues(isInitialLoad = false) {
            // Wait for DOM to be ready
            if (!document.getElementById('venue_id')) {
                console.log('Venue select not found, skipping fetch');
                return;
            }
            
            const eventType = document.querySelector('input[name="event_type"]:checked')?.value;
            const venueType = document.querySelector('input[name="venue_type"]:checked')?.value;
            
            if (eventType !== 'physical' || venueType !== 'faculty') {
                console.log('Not a physical event with faculty venue, skipping fetch');
                return;
            }

            const venueSelect = document.getElementById('venue_id');
            const currentVenueId = venueSelect.value || "{{ $event->venue_id }}";
            
            const eventDuration = document.querySelector('input[name="event_duration"]:checked')?.value;
            const eventDate = document.getElementById('event_date')?.value;
            const eventStartDate = document.getElementById('event_start_date')?.value;
            const eventEndDate = document.getElementById('event_end_date')?.value;
            const eventStartTime = document.getElementById('event_start_time').value;
            const eventEndTime = document.getElementById('event_end_time').value;
            const eventId = "{{ $event->event_id }}";

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
                event_end_time: eventEndTime,
                event_id: eventId,
                current_venue_id: currentVenueId,
                _token: '{{ csrf_token() }}'
            };

            fetch("{{ route('organizer.venues.available') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!venueSelect) return;

                // Save current selection
                const currentSelection = venueSelect.value;

                // Update venue options
                venueSelect.innerHTML = '<option value="">Select a venue</option>';
                data.availableVenues.forEach(venue => {
                    const option = document.createElement('option');
                    option.value = venue.venue_id;
                    option.text = venue.venue_name;
                    if (venue.venue_id == currentSelection) {
                        option.selected = true;
                    }
                    venueSelect.appendChild(option);
                });

                // Show modal if current venue is no longer available
                if (!data.currentVenueAvailable && currentSelection) {
                    Alpine.store('modalStore').showVenueUnavailableModal = true;
                    venueSelect.value = '';
                }
            })
            .catch(error => {
                console.error('Error fetching available venues:', error);
            });
        }

        // Add event listeners after the fetchAvailableVenues function
        document.addEventListener('DOMContentLoaded', () => {
            // Initial fetch for physical events with faculty venue
            const eventType = document.querySelector('input[name="event_type"]:checked')?.value;
            const venueType = document.querySelector('input[name="venue_type"]:checked')?.value;
            if (eventType === 'physical' && venueType === 'faculty') {
                fetchAvailableVenues(true);
            }

            // Add listeners for date/time changes
            const dateTimeInputs = document.querySelectorAll('input[type="date"], input[type="time"]');
            dateTimeInputs.forEach(input => {
                input.addEventListener('change', () => {
                    const eventType = document.querySelector('input[name="event_type"]:checked')?.value;
                    const venueType = document.querySelector('input[name="venue_type"]:checked')?.value;
                    if (eventType === 'physical' && venueType === 'faculty') {
                        fetchAvailableVenues(false);
                    }
                });
            });
        });

        function handleDrop(event, inputId, previewId) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const input = document.getElementById(inputId);
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                
                handleImagePreview(file, previewId);
            }
        }

        function handleFileSelect(event, previewId) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                handleImagePreview(file, previewId);
            }
        }

        function handleImagePreview(file, previewId) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.querySelector(`[x-ref="${previewId}"]`);
                preview.src = e.target.result;
                
                // Update Alpine.js state
                const container = preview.closest('[x-data]');
                if (previewId === 'cert_template_preview') {
                    container.__x.$data.hasTemplate = true;
                } else {
                    container.__x.$data.hasImage = true;
                }
            };
            reader.readAsDataURL(file);
        }
    </script>

    <x-toast />
</x-organizer-layout>