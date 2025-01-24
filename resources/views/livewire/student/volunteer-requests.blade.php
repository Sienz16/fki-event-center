<div>
    <!-- Tabs Section -->
    <div class="w-full mb-6">
        <ul class="grid grid-flow-col text-center text-gray-500 bg-purple-200 rounded-full p-1 relative">
            <!-- White Capsule Background for Active Tab -->
            <div class="absolute inset-y-1 transition-all duration-300 ease-out bg-white rounded-full shadow"
                 style="width: calc(100% / 2); left: {{ $tab === 'list' ? '0.5%' : '49.5%' }};">
            </div>
    
            <!-- Tabs -->
            @foreach (['list' => 'Volunteer List', 'request' => 'Volunteer Request'] as $key => $label)
                <li class="relative z-10">
                    <button wire:click="$set('tab', '{{ $key }}')"
                            wire:loading.class="opacity-50"
                            class="flex justify-center w-full py-4 transition-all duration-300 {{ $tab === $key ? 'text-black font-semibold' : 'hover:text-gray-700' }}">
                        {{ $label }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>    

    <!-- Loading Indicator -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-purple-500 overflow-hidden">
            <div class="w-full h-full origin-left bg-purple-300 animate-loading-bar"></div>
        </div>
    </div>


    <!-- Content Container -->
    <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
        <!-- Volunteer Request Tab Content -->
        @if ($tab === 'request')
            @if ($submittedRequests->isEmpty())
                <div class="flex justify-center items-center h-64">
                    <p class="text-gray-500 text-xl">You have not submitted any volunteer requests.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($submittedRequests as $request)
                        <div class="bg-[#faf5ff] p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-purple-200 relative overflow-hidden">
                            <!-- Status Badge -->
                            <div class="absolute top-8 right-8">
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium shadow-sm
                                    @if ($request->status === 'accepted') bg-emerald-100 text-emerald-800 border border-emerald-200
                                    @elseif ($request->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @elseif ($request->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                                    @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>

                            <!-- Event Image -->
                            <div class="mb-4">
                                @if($request->volunteer->event->event_img)
                                    <img src="{{ asset('storage/' . $request->volunteer->event->event_img) }}" 
                                         alt="{{ $request->volunteer->event->event_name }}" 
                                         class="w-full h-80 object-cover rounded-lg">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" 
                                         alt="Placeholder Image" 
                                         class="w-full h-80 object-cover rounded-lg">
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-grow space-y-4">
                                <h2 class="text-xl font-bold text-gray-900">{{ $request->volunteer->event->event_name }}</h2>

                                <!-- Event Details -->
                                <div class="space-y-2">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm">
                                            @if($request->volunteer->event->event_start_date && $request->volunteer->event->event_end_date)
                                                {{ \Carbon\Carbon::parse($request->volunteer->event->event_start_date)->format('M j, Y') }} - 
                                                {{ \Carbon\Carbon::parse($request->volunteer->event->event_end_date)->format('M j, Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($request->volunteer->event->event_date)->format('M j, Y') }}
                                            @endif
                                        </span>
                                    </div>

                                    @if($request->volunteer->event->event_start_time)
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm">
                                            @if($request->volunteer->event->event_end_time)
                                                {{ \Carbon\Carbon::parse($request->volunteer->event->event_start_time)->format('g:i a') }} - 
                                                {{ \Carbon\Carbon::parse($request->volunteer->event->event_end_time)->format('g:i a') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($request->volunteer->event->event_start_time)->format('g:i a') }}
                                            @endif
                                        </span>
                                    </div>
                                    @endif

                                    <div class="flex items-center text-gray-600">
                                        @if($request->volunteer->event->event_type === 'physical')
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
                                            @if($request->volunteer->event->event_type === 'physical')
                                                {{ $request->volunteer->event->venue ? $request->volunteer->event->venue->venue_name : 'Venue not assigned' }}
                                            @else
                                                Online via {{ $request->volunteer->event->online_platform ?? 'platform not specified' }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-4 pt-4 border-t border-purple-100">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-xs text-gray-500">Updated {{ $request->volunteer->updated_at->diffForHumans() }}</span>
                                </div>
                                
                                <a href="{{ route('student.volunteers.show', $request->volunteer->volunteer_id) }}" 
                                   class="block w-full text-center bg-purple-600 text-white rounded-lg px-4 py-2.5 
                                          transition-all duration-300 hover:bg-purple-700">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <!-- Volunteer List Tab Content -->
            @if ($volunteers->isEmpty())
                <div class="flex justify-center items-center h-64">
                    <p class="text-gray-500 text-xl">No Volunteer Opportunities Available.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($volunteers as $volunteer)
                        @php
                            $eventEndDate = $volunteer->event->event_end_date ?? $volunteer->event->event_date;
                            $hasNotPassed = \Carbon\Carbon::parse($eventEndDate)->endOfDay()->isFuture();
                        @endphp
                        
                        @if(!$submittedRequests->pluck('volunteer_id')->contains($volunteer->volunteer_id) && $hasNotPassed)
                            <div class="bg-[#faf5ff] p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-purple-200 relative overflow-hidden">
                                <!-- Volunteer Need Badge -->
                                <div class="absolute top-8 right-8">
                                    <span class="bg-purple-600 text-white px-4 py-1.5 rounded-full text-sm font-medium shadow-sm">
                                        {{ $volunteer->volunteer_capacity > 0 ? $volunteer->remaining_needed : 0 }} Volunteers Needed
                                    </span>
                                </div>

                                <!-- Event Image -->
                                <div class="mb-4">
                                    @if($volunteer->event->event_img)
                                        <img src="{{ asset('storage/' . $volunteer->event->event_img) }}" 
                                             alt="{{ $volunteer->event->event_name }}" 
                                             class="w-full h-80 object-cover rounded-lg">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" 
                                             alt="Placeholder Image" 
                                             class="w-full h-80 object-cover rounded-lg">
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-grow space-y-4">
                                    <h2 class="text-xl font-bold text-gray-900">{{ $volunteer->event->event_name }}</h2>

                                    <!-- Event Details -->
                                    <div class="space-y-2">
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-sm">
                                                @if($volunteer->event->event_start_date && $volunteer->event->event_end_date)
                                                    {{ \Carbon\Carbon::parse($volunteer->event->event_start_date)->format('M j, Y') }} - 
                                                    {{ \Carbon\Carbon::parse($volunteer->event->event_end_date)->format('M j, Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($volunteer->event->event_date)->format('M j, Y') }}
                                                @endif
                                            </span>
                                        </div>

                                        @if($volunteer->event->event_start_time)
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm">
                                                @if($volunteer->event->event_end_time)
                                                    {{ \Carbon\Carbon::parse($volunteer->event->event_start_time)->format('g:i a') }} - 
                                                    {{ \Carbon\Carbon::parse($volunteer->event->event_end_time)->format('g:i a') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($volunteer->event->event_start_time)->format('g:i a') }}
                                                @endif
                                            </span>
                                        </div>
                                        @endif

                                        <div class="flex items-center text-gray-600">
                                            @if($volunteer->event->event_type === 'physical')
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
                                                @if($volunteer->event->event_type === 'physical')
                                                    {{ $volunteer->event->venue ? $volunteer->event->venue->venue_name : 'Venue not assigned' }}
                                                @else
                                                    Online via {{ $volunteer->event->online_platform ?? 'platform not specified' }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="mt-4 pt-4 border-t border-purple-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-xs text-gray-500">Updated {{ $volunteer->updated_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <a href="{{ route('student.volunteers.show', $volunteer->volunteer_id) }}" 
                                       class="block w-full text-center bg-purple-600 text-white rounded-lg px-4 py-2.5 
                                              transition-all duration-300 hover:bg-purple-700">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
