<div>
    <div class="min-h-[calc(100vh-65px)] pb-8">
        <!-- Tabs Section -->
        <div class="w-full mb-6">
            <ul class="grid grid-flow-col text-center text-gray-500 bg-purple-200 rounded-full p-1 relative">
                <!-- Ongoing Event Tab Indicator -->
                <div class="absolute inset-y-1 transition-all duration-300 ease-out bg-white rounded-full shadow"
                     style="width: calc(100% / 3); left: {{ match($tab) {
                         'all' => '0.5%',
                         'registered' => '33.333333%',
                         'past' => '66.333334%', 
                         default => '0%'
                     } }};">
                </div>

                @foreach (['all', 'registered', 'past'] as $tabName)
                    <li class="relative z-10">
                        <button wire:click="switchTab('{{ $tabName }}')"
                                wire:loading.class="opacity-50"
                                class="flex justify-center w-full py-4 transition-all duration-300 {{ $tab === $tabName ? 'text-black' : 'hover:text-gray-700' }}">
                            {{ ucfirst($tabName) }} Events
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Add a loading indicator -->
        <div wire:loading class="fixed top-0 left-0 right-0 z-50">
            <div class="h-1 bg-purple-500 overflow-hidden">
                <div class="w-full h-full origin-left bg-purple-300 animate-loading-bar"></div>
            </div>
        </div>

        <style>
            /* Add these styles within your component */
            .transition-all {
                transition-property: all;
            }
            .duration-300 {
                transition-duration: 300ms;
            }
            .ease-out {
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>

        <!-- Search and Filter Section -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-6">
            <div class="flex flex-wrap gap-4">
                <input type="text" 
                       wire:model.live.debounce.500ms="search"
                       placeholder="Search events" 
                       class="flex-grow border border-gray-300 rounded-lg px-3 py-2 text-gray-900 placeholder-gray-500">
                
                <select wire:model.live="date_filter" 
                        class="w-full sm:w-auto sm:flex-grow border border-gray-300 rounded-lg px-3 py-2 text-gray-900 bg-white">
                    <option value="">All Dates</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="past">Past</option>
                </select>
                
                <select wire:model.live="venue_type_filter" 
                        class="w-full sm:w-auto sm:flex-grow border border-gray-300 rounded-lg px-3 py-2 text-gray-900 bg-white">
                    <option value="">All Venue Types</option>
                    <option value="physical">Physical</option>
                    <option value="online">Online</option>
                </select>
            </div>
        </div>

        <!-- Events List Section -->
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
            <div class="mb-6 p-2 sm:p-4">
                <!-- Conditional Title Based on Tab -->
                @if(request('tab') == 'registered')
                <h2 class="text-3xl font-bold text-gray-900">Your Registered Events</h2>
                @elseif(request('tab') == 'past')
                <h2 class="text-3xl font-bold text-gray-900">Past Events</h2>
                @else
                <h2 class="text-3xl font-bold text-gray-900">All Events</h2>
                @endif
                <hr class="border-t-2 border-gray-300 mt-2">
            </div>
            
            @if($events->isEmpty())
                <!-- No Events Available Message -->
                <div class="flex justify-center items-center h-64">
                    <p class="text-gray-500 text-xl">No Events Available.</p>
                </div>
            @else
                <!-- Events List -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                    <div class="bg-[#faf5ff] hover:bg-white p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-purple-200 relative overflow-hidden">
                        <!-- Status Badge (if registered/attended) -->
                        @if($event->participants->contains(Auth::user()->student))
                            @php
                                $attendance = $event->attendances()->where('stud_id', Auth::user()->student->stud_id)->first();
                            @endphp
                            <div class="absolute top-4 right-4 z-10">
                                @if($attendance && $attendance->status == 'attended')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium shadow-sm border border-emerald-200">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Attended
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium shadow-sm border border-purple-200">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Registered
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Event Image -->
                        <div class="mb-4">
                            @if($event->event_img)
                                <img src="{{ asset('storage/' . $event->event_img) }}" 
                                     alt="{{ $event->event_name }}" 
                                     class="w-full h-80 object-cover rounded-lg">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" 
                                     alt="Placeholder Image" 
                                     class="w-full h-80 object-cover rounded-lg">
                            @endif
                        </div>

                        <!-- Event Information -->
                        <div class="flex-grow space-y-3">
                            <h2 class="text-xl font-bold text-gray-900 group-hover:text-purple-800 transition-colors duration-300">
                                {{ $event->event_name }}
                            </h2>

                            <!-- Date and Time Section -->
                            <div class="space-y-2">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @if($event->event_start_date && $event->event_end_date)
                                        <span class="text-sm">
                                            {{ \Carbon\Carbon::parse($event->event_start_date)->format('M j, Y') }} - 
                                            {{ \Carbon\Carbon::parse($event->event_end_date)->format('M j, Y') }}
                                        </span>
                                    @else
                                        <span class="text-sm">{{ \Carbon\Carbon::parse($event->event_date)->format('M j, Y') }}</span>
                                    @endif
                                </div>

                                @if($event->event_start_time)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm">
                                        @if($event->event_end_time)
                                            {{ \Carbon\Carbon::parse($event->event_start_time)->format('g:i a') }} - 
                                            {{ \Carbon\Carbon::parse($event->event_end_time)->format('g:i a') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($event->event_start_time)->format('g:i a') }}
                                        @endif
                                    </span>
                                </div>
                                @endif

                                <!-- Venue/Platform -->
                                <div class="flex items-center text-gray-600">
                                    @if($event->event_type === 'physical')
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                    <span class="text-sm">
                                        @if($event->event_type === 'physical')
                                            @if($event->venue_type === 'faculty')
                                                {{ $event->venue ? $event->venue->venue_name : 'Venue not assigned' }}
                                            @elseif($event->venue_type === 'other')
                                                {{ $event->other_venue_name ?: 'Other venue name missing' }}
                                            @else
                                                Venue not specified
                                            @endif
                                        @else
                                            Online via {{ $event->online_platform ?? 'platform not specified' }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xs text-gray-500">
                                    Updated {{ $event->updated_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <!-- View Details Button -->
                            <a href="{{ route('student.events.show', $event->event_id) }}" 
                               class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white rounded-lg px-4 py-2.5 
                                      transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif                

            <hr class="border-t-2 border-gray-300 mt-10 mb-6">

            <!-- Pagination Links -->
            <div class="mt-6 flex justify-center">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
