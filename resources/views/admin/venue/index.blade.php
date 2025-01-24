<x-admin-layout>
    <x-slot:title>
        Venues
    </x-slot>

    <x-slot:header>
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Venues</h1>
            <a href="{{ route('admin.venue.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Add New Venue
            </a>
        </div>
    </x-slot>

    <div x-data="{ deleteModalOpen: false, deleteUrl: '' }">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                <div class="mb-6 p-2 sm:p-4">
                    <h2 class="text-3xl font-semibold text-gray-900">Manage Venues</h2>
                    <hr class="border-t-2 border-gray-300 mt-2">
                </div>

                <div class="grid gap-8 mt-8">
                    @foreach($venues as $venue)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-purple-100 hover:border-purple-200 transition-all duration-300">
                        <div class="flex flex-col lg:flex-row">
                            <!-- Venue Image Section -->
                            <div class="lg:w-1/3 relative">
                                <img src="{{ $venue->venue_image ? asset('storage/' . $venue->venue_image) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $venue->venue_name }}" 
                                     class="h-full w-full object-cover min-h-[400px]">
                                <!-- Add a subtle purple overlay on hover -->
                                <div class="absolute inset-0 bg-purple-900/0 hover:bg-purple-900/10 transition-colors duration-300"></div>
                            </div>

                            <!-- Venue Details Section -->
                            <div class="lg:w-2/3 p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $venue->venue_name }}</h3>
                                        <p class="text-gray-600 mt-1">{{ $venue->venue_location }}</p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            {{ $venue->venue_status === 'Available' ? 'bg-green-100 text-green-800' : 
                                               ($venue->venue_status === 'Booked' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $venue->venue_status }}
                                        </span>
                                        <!-- Updated Action Buttons with Labels -->
                                        <a href="{{ route('admin.venue.edit', $venue->venue_id) }}" 
                                           class="flex items-center px-3 py-1 text-sm text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button @click="deleteModalOpen = true; deleteUrl = '{{ route('admin.venue.destroy', $venue->venue_id) }}'"
                                                class="flex items-center px-3 py-1 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-gray-600 text-sm">Capacity:</span>
                                        <p class="font-medium">{{ $venue->capacity ?? 'Not specified' }} people</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 text-sm">Equipment:</span>
                                        <p class="font-medium line-clamp-1">{{ $venue->equipment ?? 'None listed' }}</p>
                                    </div>
                                </div>

                                <!-- Updated Current/Upcoming Bookings section -->
                                <div class="mt-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Current & Upcoming Bookings</h4>
                                    <div class="space-y-2 max-h-[160px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-purple-500 scrollbar-track-purple-100">
                                        @php
                                            $sortedEvents = $venue->venueBooks->map->event
                                                ->filter()
                                                ->sortBy(function($event) {
                                                    return $event->event_date ?? $event->event_start_date;
                                                });
                                        @endphp
                                        @forelse($sortedEvents as $event)
                                            <div class="flex items-center justify-between bg-purple-50 p-3 rounded-lg border border-purple-100 hover:bg-purple-100 transition-colors">
                                                <div>
                                                    <p class="font-medium text-purple-900">{{ $event->event_name }}</p>
                                                    <p class="text-sm text-purple-600">
                                                        @if($event->event_date)
                                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d, Y') }} - 
                                                            {{ \Carbon\Carbon::parse($event->event_end_date)->format('M d, Y') }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <span class="text-sm text-purple-600 bg-purple-100 px-3 py-1 rounded-full">
                                                    {{ $event->event_start_time }} - {{ $event->event_end_time }}
                                                </span>
                                            </div>
                                        @empty
                                            <p class="text-gray-500 text-sm italic">No upcoming bookings</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if ($venues->isEmpty())
                        <div class="p-4 text-sm text-gray-500 text-center">
                            No venues found.
                        </div>
                    @endif
                </div>

                <div class="mt-8">
                    {{ $venues->links() }}
                </div>
            </div>
        </div>

        <x-confirm-delete-modal title="Venue" />
    </div>

    <x-toast />
</x-admin-layout>