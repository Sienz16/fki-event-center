<x-student-layout>
    <x-slot:title>
        Event Details
    </x-slot>

    <x-slot:header>
        Event Details
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]" x-data="{ 
        registerModalOpen: false, 
        attendanceModalOpen: false, 
        feedbackModalOpen: false, 
        rating: {{ $existingFeedback ? $existingFeedback->rating : 0 }}, 
        eventCode: '', 
        actionModalOpen: false, 
        loadingModalOpen: false 
    }">
        <div class="bg-white p-6 shadow-lg rounded-lg flex flex-col md:flex-row">
            <!-- Image Section -->
            <div class="w-full md:w-1/2 mb-4 md:mb-0">
                @if($event->event_img)
                    <img src="{{ asset('storage/' . $event->event_img) }}" alt="{{ $event->event_name }}" class="w-full h-auto rounded-lg">
                @else
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder Image" class="w-full h-auto rounded-lg">
                @endif
            </div>

            <!-- Details Section -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center md:pl-8">
                <div class="w-full text-center md:text-left">
                    <h2 class="text-3xl font-bold mb-4">{{ $event->event_name }}</h2>

                    <!-- Event Details in Table Format -->
                    <div class="overflow-hidden rounded-lg border border-gray-300 shadow-sm mb-4">
                        <table class="w-full text-left">
                            <tbody>
                                <tr class="bg-purple-100">
                                <th class="px-4 py-2 font-semibold text-gray-700">Date</th>
                                <td class="px-4 py-2">
                                    @if($event->event_start_date && $event->event_end_date)
                                    {{ \Carbon\Carbon::parse($event->event_start_date)->format('F j, Y') }} -
                                    {{ \Carbon\Carbon::parse($event->event_end_date)->format('F j, Y') }}
                                    @else
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}
                                    @endif
                                </td>
                                </tr>
                                <tr>
                                <th class="px-4 py-2 font-semibold text-gray-700">Time</th>
                                <td class="px-4 py-2">
                                    @if($event->event_start_time && $event->event_end_time)
                                    From {{ \Carbon\Carbon::parse($event->event_start_time)->format('g:i a') }}
                                    until {{ \Carbon\Carbon::parse($event->event_end_time)->format('g:i a') }}
                                    @elseif($event->event_start_time)
                                    {{ \Carbon\Carbon::parse($event->event_start_time)->format('g:i a') }}
                                    @endif
                                </td>
                                </tr>
                                <tr class="bg-purple-100">
                                <th class="px-4 py-2 font-semibold text-gray-700">Venue</th>
                                <td class="px-4 py-2">
                                    @if($event->event_type === 'physical')
                                        @if($event->venue_type === 'faculty')
                                            {{ $event->venue->venue_name ?? 'No faculty venue assigned' }}
                                        @elseif($event->other_venue_name)
                                            {{ $event->other_venue_name }}
                                            <span class="text-gray-800 text-sm italic">(Other Venue)</span>
                                        @else
                                            Venue not specified
                                        @endif
                                    @else
                                        {{ $event->online_platform }}
                                    @endif
                                </td>
                                </tr>
                                <tr>
                                <th class="px-4 py-2 font-semibold text-gray-700">Organizer</th>
                                <td class="px-4 py-2 flex items-center">
                                    @if($event->organizer->org_img)
                                    <img src="{{ asset('storage/' . $event->organizer->org_img) }}" alt="Organizer Image" class="w-10 h-10 rounded-full mx-2">
                                    @else
                                    <img src="{{ asset('images/organizer_placeholder.png') }}" alt="Organizer Image" class="w-10 h-10 rounded-full mx-2">
                                    @endif
                                    <span class="ml-2">{{ $event->organizer->org_name ?? 'Organizer Name Not Available' }}</span>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-4 p-4 border border-purple-300 rounded-lg bg-purple-50">
                        <p>{{ $event->event_desc }}</p>
                    </div>
                </div>

                <!-- Register and Confirm Attendance Section -->
                <div class="mt-4 flex flex-col justify-center items-center space-y-4">
                    @if($event->participants->contains(Auth::user()->student))
                        @php
                            $attended = $event->attendances()->where('stud_id', Auth::user()->student->stud_id)->where('status', 'attended')->exists();
                        @endphp

                        @if($attended)
                            <p class="text-green-600 font-semibold text-center">You had attended this event! 😊</p>

                            @php
                                // Fetch the student's e-cert for this event, if it exists
                                $ecert = $event->ecertificates()->where('stud_id', Auth::user()->student->stud_id)->first();
                            @endphp

                            <div class="flex space-x-4 mt-4">
                                @if(!$ecert)
                                    <!-- Generate E-Cert Button -->
                                    <button type="button" @click="actionModalOpen = true" 
                                            class="bg-green-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-48">
                                        Generate E-Certificate
                                    </button>

                                    <!-- E-Certificate Generate Modal -->
                                    <x-student-event-action-modal>
                                        <x-slot name="icon">
                                            <svg class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </x-slot>
                                        <x-slot name="iconBg">bg-green-100</x-slot>
                                        <x-slot name="title">Generate E-Certificate</x-slot>
                                        <x-slot name="message">Are you ready to generate your e-certificate?</x-slot>
                                        <x-slot name="color">green</x-slot>
                                        <x-slot name="action">
                                            <form action="{{ route('student.ecert.generate', ['event' => $event->event_id]) }}" 
                                                  method="POST" 
                                                  @submit="actionModalOpen = false; loadingModalOpen = true">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 w-32">
                                                    Generate
                                                </button>
                                            </form>
                                        </x-slot>
                                    </x-student-event-action-modal>

                                    <!-- Loading Modal -->
                                    <x-loading-modal>
                                        <x-slot name="color">green</x-slot>
                                        <x-slot name="title">Generating E-Certificate</x-slot>
                                        <x-slot name="message">Please wait while we generate your certificate...</x-slot>
                                    </x-loading-modal>
                                @else
                                    <!-- Button to download E-Cert -->
                                    <a href="{{ route('student.ecert.download', ['ecert' => $ecert->ecert_id]) }}" class="bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-48">
                                        Download E-Certificate
                                    </a>
                                @endif

                                <!-- Check if feedback exists and show the appropriate button -->
                                @if($existingFeedback)
                                    <!-- "Edit Feedback" Button -->
                                    <button @click="feedbackModalOpen = true" class="bg-yellow-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 w-48">
                                        Edit Feedback
                                    </button>
                                @else
                                    <!-- "Give Feedback" Button -->
                                    <button @click="feedbackModalOpen = true" class="bg-yellow-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 w-48">
                                        Give Feedback
                                    </button>
                                @endif
                            </div>
                        @else
                            <p class="text-green-600 font-semibold text-center">You are registered for this event!</p>

                            <!-- Button Container -->
                            <div class="flex space-x-4">
                                <!-- Cancel Registration Button -->
                                <button type="button" 
                                        @click="actionModalOpen = true"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Cancel Registration
                                </button>

                                <!-- Cancel Registration Modal -->
                                <x-student-event-action-modal>
                                    <x-slot name="icon">
                                        <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </x-slot>
                                    <x-slot name="iconBg">bg-red-100</x-slot>
                                    <x-slot name="title">Cancel Registration</x-slot>
                                    <x-slot name="message">Are you sure you want to cancel your registration for this event?</x-slot>
                                    <x-slot name="color">red</x-slot>
                                    <x-slot name="action">
                                        <form action="{{ route('student.events.unregister', $event->event_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 w-32">
                                                Confirm
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-student-event-action-modal>

                                <!-- Confirm Attendance Button -->
                                @php
                                    $currentDate = \Carbon\Carbon::now();
                                    $eventEndDate = \Carbon\Carbon::parse($event->event_end_date ?? $event->event_date);
                                @endphp

                                @if($currentDate->lessThanOrEqualTo($eventEndDate))
                                    <button
                                        type="button"
                                        @click="attendanceModalOpen = true"
                                        class="bg-green-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Confirm Attendance
                                    </button>
                                @endif
                            </div>
                        @endif
                    @else
                        <!-- Register Button -->
                        <button type="button" 
                                @click="actionModalOpen = true"
                                class="bg-purple-500 text-white w-50 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Register Event
                        </button>

                        <!-- Registration Modal -->
                        <x-student-event-action-modal>
                            <x-slot name="icon">
                                <svg class="h-10 w-10 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </x-slot>
                            <x-slot name="iconBg">bg-purple-100</x-slot>
                            <x-slot name="title">Register for Event</x-slot>
                            <x-slot name="message">Are you sure you want to register for this event?</x-slot>
                            <x-slot name="color">purple</x-slot>
                            <x-slot name="action">
                                <form action="{{ route('student.events.register', $event->event_id) }}" 
                                      method="POST" 
                                      @submit="actionModalOpen = false; loadingModalOpen = true">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200 w-32">
                                        Register
                                    </button>
                                </form>
                            </x-slot>
                        </x-student-event-action-modal>

                        <!-- Loading Modal -->
                        <x-loading-modal>
                            <x-slot name="color">purple</x-slot>
                            <x-slot name="title">Processing Registration</x-slot>
                            <x-slot name="message">Please wait while we process your registration...</x-slot>
                        </x-loading-modal>
                    @endif
                </div>                                                     
            </div>
        </div>

        <!-- Attendance Confirmation Modal -->
        <div x-show="attendanceModalOpen" 
        x-cloak 
        @click.away="attendanceModalOpen = false" 
        class="fixed inset-0 z-50 flex items-center justify-center overflow-auto">
    
            <!-- Backdrop -->
            <div x-show="attendanceModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm">
            </div>

            <!-- Modal Content -->
            <div class="relative w-full max-w-lg mx-auto bg-white shadow-2xl rounded-3xl"
                    x-show="attendanceModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-8">
                <!-- Close Button -->
                <button @click="attendanceModalOpen = false" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="px-6 pt-8 pb-6">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                        <svg class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Confirm Your Attendance</h3>
                        <p class="text-gray-600">Please enter the event code provided by the organizer to confirm your attendance.</p>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('student.events.attend', $event->event_id) }}" 
                            method="POST" 
                            class="space-y-6">
                        @csrf
                        <!-- Event Code Input -->
                        <div class="relative">
                            <input type="text" 
                                    x-model="eventCode"
                                    name="event_code"
                                    class="block w-full px-4 py-3 text-lg text-center tracking-widest rounded-xl border-2 border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Enter Event Code"
                                    maxlength="10"
                                    required>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-center space-x-4">
                            <button type="submit"
                                    class="px-6 py-3 text-base font-medium text-white bg-green-600 hover:bg-green-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 w-32">
                                Confirm
                            </button>
                            <button type="button"
                                    @click="attendanceModalOpen = false"
                                    class="px-6 py-3 text-base font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 w-32">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </div>

            <!-- Feedback Modal with Updated Design -->
            <div x-show="feedbackModalOpen" 
                x-cloak 
                @click.away="feedbackModalOpen = false" 
                class="fixed inset-0 z-50 flex items-center justify-center overflow-auto">
            
            <!-- Backdrop -->
            <div x-show="feedbackModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm">
            </div>

            <!-- Modal Content -->
            <div class="relative w-full max-w-lg mx-auto bg-white shadow-2xl rounded-3xl"
                    x-show="feedbackModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-8">
                
                <!-- Close Button -->
                <button @click="feedbackModalOpen = false" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="px-6 pt-8 pb-6">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-6">
                        <svg class="h-10 w-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $existingFeedback ? 'Edit Your Feedback' : 'Share Your Experience' }}</h3>
                        <p class="text-gray-600">Your feedback helps us improve our events!</p>
                    </div>

                    <form action="{{ route('student.events.feedback', $event->event_id) }}" method="POST" class="space-y-6">
                        @csrf
                        <!-- Star Rating -->
                        <div class="flex flex-col items-center space-y-3">
                            <label class="text-lg font-medium text-gray-700">Rate your experience</label>
                            <div class="flex space-x-1">
                                <template x-for="i in 5" :key="i">
                                    <button type="button"
                                            @click="rating = i"
                                            class="focus:outline-none transition-transform duration-200 hover:scale-110"
                                            :class="{ 'transform scale-110': rating === i }">
                                        <svg :class="rating >= i ? 'text-yellow-400' : 'text-gray-300'"
                                                class="h-10 w-10 cursor-pointer fill-current transition-colors duration-200"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="rating" :value="rating">
                        </div>

                        <!-- Feedback Text -->
                        <div class="space-y-2">
                            <label for="feedback" class="block text-lg font-medium text-gray-700">Additional comments</label>
                            <textarea id="feedback"
                                        name="feedback"
                                        rows="4"
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 transition-colors duration-200 resize-none"
                                        placeholder="Share your thoughts about the event..."
                            >{{ $existingFeedback->feedback ?? '' }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center space-x-3">
                            <button type="submit"
                                    class="px-6 py-3 text-base font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200 w-32">
                                {{ $existingFeedback ? 'Update' : 'Submit' }}
                            </button>
                            <button type="button"
                                    @click="feedbackModalOpen = false"
                                    class="px-6 py-3 text-base font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 w-32">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>