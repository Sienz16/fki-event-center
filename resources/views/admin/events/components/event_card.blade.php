<!-- resources/views/admin/events/components/event_card.blade.php -->
<div class="bg-[#faf5ff] hover:bg-white p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-purple-200 relative overflow-hidden">
    <!-- Status Badge -->
    <div class="absolute top-4 right-4 z-10">
        <span class="px-3 py-1.5 rounded-full text-sm font-medium shadow-sm
            @if($event->status === 'active') bg-emerald-100 text-emerald-800 border border-emerald-200
            @elseif($event->status === 'suspended') bg-red-100 text-red-800 border border-red-200
            @elseif($event->status === 'requested') bg-yellow-100 text-yellow-800 border border-yellow-200
            @endif">
            {{ ucfirst($event->status) }}
        </span>
    </div>

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

    <!-- Content -->
    <div class="flex-grow space-y-3">
        <h2 class="text-xl font-bold text-gray-900 group-hover:text-purple-800 transition-colors duration-300">
            {{ $event->event_name }}
        </h2>

        <!-- Event Details -->
        <div class="space-y-2">
            <div class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm">
                    @if($event->event_start_date && $event->event_end_date)
                        {{ \Carbon\Carbon::parse($event->event_start_date)->format('M j, Y') }} - 
                        {{ \Carbon\Carbon::parse($event->event_end_date)->format('M j, Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($event->event_date)->format('M j, Y') }}
                    @endif
                </span>
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

    <!-- Footer -->
    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <span class="text-xs text-gray-500">Updated {{ $event->updated_at->diffForHumans() }}</span>
        </div>
        
        <a href="{{ route('admin.events.show', $event->event_id) }}" 
           class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white rounded-lg px-4 py-2.5 
                  transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
            View Details
        </a>
    </div>
</div>