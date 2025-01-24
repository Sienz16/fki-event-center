<x-organizer-layout>
    <x-slot:title>
        Venue Booking
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Book a Venue</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">

            <!-- Tabs Section -->
            <div class="w-full mb-6">
                <ul class="grid grid-flow-col text-center text-gray-500 bg-purple-200 rounded-full p-1">
                    <li>
                        <a href="{{ route('organizer.venues.index', ['tab' => 'list']) }}" 
                           class="flex justify-center py-4 {{ request('tab') === 'list' || !request('tab') ? 'bg-white rounded-full shadow text-black' : '' }}">
                            Venue List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('organizer.venues.index', ['tab' => 'booked']) }}" 
                           class="flex justify-center py-4 {{ request('tab') === 'booked' ? 'bg-white rounded-full shadow text-black' : '' }}">
                            Booked Venues
                        </a>
                    </li>
                </ul>
            </div>

            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                
                <!-- Tab 1: Venue List -->
                @if(request('tab') === 'booked')
                    <!-- Booked Venues -->
                    <div class="grid gap-12 lg:grid-cols-2 mt-3"> <!-- Change to grid layout -->
                        @foreach($bookedVenues as $bookedVenue)
                            <a href="{{ route('organizer.venues.show', $bookedVenue->venue_id) }}" class="relative rounded-lg overflow-hidden h-[400px] transition-transform duration-300 transform hover:scale-105 hover:shadow-lg">
                                <img src="{{ asset('storage/' . $bookedVenue->venue_image) }}" alt="{{ $bookedVenue->venue_name }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent p-4 flex flex-col justify-end">
                                    <h4 class="text-xl font-semibold text-white">{{ $bookedVenue->venue_name }}</h4>
                                    <p class="text-gray-200 text-sm mb-3">{{ $bookedVenue->venue_location }}</p>
                                    <p class="text-gray-200 text-sm mb-3">Status: 
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                            Booked
                                        </span>
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if ($bookedVenues->isEmpty())
                        <div class="p-4 text-sm text-gray-500 text-center">
                            No venues booked by you.
                        </div>
                    @endif
                @else
                    <!-- Venue List -->
                    <div class="grid gap-12 lg:grid-cols-2 mt-3">
                        @foreach($venues as $venue)
                            <a href="{{ route('organizer.venues.show', $venue->venue_id) }}" class="relative rounded-lg overflow-hidden h-[400px] transition-transform duration-300 transform hover:scale-105 hover:shadow-lg">
                                <img src="{{ asset('storage/' . $venue->venue_image) }}" alt="{{ $venue->venue_name }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent p-4 flex flex-col justify-end">
                                    <h4 class="text-xl font-semibold text-white">{{ $venue->venue_name }}</h4>
                                    <p class="text-gray-200 text-sm mb-3">{{ $venue->venue_location }}</p>
                                    <p class="text-gray-200 text-sm mb-3">Status: 
                                        @if($venue->venue_status === 'Under Maintenance')
                                            <span class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1.5 text-sm font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/10">
                                                Under Maintenance
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700 ring-1 ring-inset ring-green-600/10">
                                                Available
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if ($venues->isEmpty())
                        <div class="p-4 text-sm text-gray-500 text-center">
                            No venues available for booking.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <x-toast />
</x-organizer-layout>