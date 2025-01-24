<x-student-layout>
    <x-slot:title>
        {{ $volunteer->event->event_name }} Details
    </x-slot>

    <x-slot:header>
        {{ $volunteer->event->event_name }}
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]">
        <div class="bg-white p-6 shadow-lg rounded-lg flex flex-col md:flex-row">
            <!-- Image Section -->
            <div class="w-full md:w-1/2 mb-4 md:mb-0">
                @if($volunteer->event->event_img)
                    <img src="{{ asset('storage/' . $volunteer->event->event_img) }}" alt="{{ $volunteer->event->event_name }}" class="w-full h-auto rounded-lg">
                @else
                    <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500">No Image Available</span>
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center md:pl-8">
                <div class="w-full text-center md:text-left">
                    <h2 class="text-3xl font-bold mb-4">{{ $volunteer->event->event_name }}</h2>

                    <!-- Volunteer and Event Details in Table Format -->
                    <div class="overflow-hidden rounded-lg border border-gray-300 shadow-sm mb-4">
                        <table class="w-full text-left">
                            <tbody>
                                <!-- Event Dates -->
                                <tr class="bg-purple-100">
                                    <th class="px-4 py-2 font-semibold text-gray-700">Date</th>
                                    <td class="px-4 py-2">
                                        @if($volunteer->event->event_start_date && $volunteer->event->event_end_date)
                                            {{ \Carbon\Carbon::parse($volunteer->event->event_start_date)->format('F j, Y') }} -
                                            {{ \Carbon\Carbon::parse($volunteer->event->event_end_date)->format('F j, Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($volunteer->event->event_date)->format('F j, Y') }}
                                        @endif
                                    </td>
                                </tr>

                                <!-- Event Time -->
                                <tr>
                                    <th class="px-4 py-2 font-semibold text-gray-700">Time</th>
                                    <td class="px-4 py-2">
                                        @if($volunteer->event->event_start_time && $volunteer->event->event_end_time)
                                            From {{ \Carbon\Carbon::parse($volunteer->event->event_start_time)->format('g:i a') }}
                                            until {{ \Carbon\Carbon::parse($volunteer->event->event_end_time)->format('g:i a') }}
                                        @elseif($volunteer->event->event_start_time)
                                            {{ \Carbon\Carbon::parse($volunteer->event->event_start_time)->format('g:i a') }}
                                        @endif
                                    </td>
                                </tr>

                                <!-- Event Venue or Online Platform -->
                                <tr class="bg-purple-100">
                                    <th class="px-4 py-2 font-semibold text-gray-700">Venue</th>
                                    <td class="px-4 py-2">
                                        @if($volunteer->event->event_type === 'online')
                                            {{ $volunteer->event->online_platform ?? 'Platform not specified' }}
                                        @else
                                            {{ $volunteer->event->venue->venue_name ?? 'Venue not assigned' }}
                                        @endif
                                    </td>
                                </tr>

                                <!-- Organizer Details -->
                                <tr>
                                    <th class="px-4 py-2 font-semibold text-gray-700">Organizer</th>
                                    <td class="px-4 py-2 flex items-center">
                                        @if($volunteer->event->organizer->org_img)
                                            <img src="{{ asset('storage/' . $volunteer->event->organizer->org_img) }}" alt="Organizer Image" class="w-10 h-10 rounded-full mx-2">
                                        @else
                                            <img src="{{ asset('images/organizer_placeholder.png') }}" alt="Organizer Image" class="w-10 h-10 rounded-full mx-2">
                                        @endif
                                        <span class="ml-2">{{ $volunteer->event->organizer->org_name ?? 'Organizer Name Not Available' }}</span>
                                    </td>
                                </tr>

                                <!-- Volunteering Status -->
                                <tr class="bg-purple-100">
                                    <th class="px-4 py-2 font-semibold text-gray-700">Request Status</th>
                                    <td class="px-4 py-2">
                                        @if($volunteerStatus == 'pending')
                                            <p>Volunteer request under review, please wait.</p>
                                        @elseif($volunteerStatus == 'accepted')
                                            <p class="text-green-500">Congratulations! You have been selected to be a volunteer for this event.</p>
                                        @elseif($volunteerStatus == 'rejected')
                                            <p class="text-red-500">Sorry, your volunteer request has been rejected.</p>
                                        @else
                                            <p>You haven't submit request yet.</p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Volunteer Notes Section -->
                    <div class="mb-4 p-4 border border-purple-300 rounded-lg bg-purple-50">
                        <strong>Notes:</strong> {{ $volunteer->notes ?? 'No notes available.' }}
                    </div>
                </div>

                <!-- Volunteer Request Button Section -->
                <div class="mt-4 flex flex-col justify-center items-center space-y-4">
                    @if($volunteerStatus == 'pending')
                        <div x-data="{ actionModalOpen: false, actionUrl: '{{ route('student.volunteers.revoke', $volunteer->volunteer_id) }}' }">
                            <button @click="actionModalOpen = true" class="bg-red-500 text-white px-4 py-2 rounded-md">Revoke Request</button>
                            
                            <x-confirm-action-modal>
                                <x-slot name="icon">
                                    <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </x-slot>
                                <x-slot name="title">Revoke Volunteer Request</x-slot>
                                <x-slot name="message">Are you sure you want to revoke your volunteer request for this event? This action cannot be undone.</x-slot>
                                <x-slot name="confirmButton">
                                    <span class="flex items-center">Revoke</span>
                                </x-slot>
                                <x-slot name="confirmButtonClass">bg-red-600 hover:bg-red-700 focus:ring-red-500</x-slot>
                                <x-slot name="iconBg">bg-red-100</x-slot>
                            </x-confirm-action-modal>
                        </div>
                    @elseif($volunteerStatus == 'accepted')
                        <p></p>
                    @elseif($volunteerStatus == 'rejected')
                        <p></p>
                    @else
                        <div x-data="{ actionModalOpen: false, actionUrl: '{{ route('student.volunteers.store') }}' }">
                            <button @click="actionModalOpen = true" class="bg-purple-500 text-white px-4 py-2 rounded-md">Request Volunteer</button>
                            
                            <x-confirm-action-modal>
                                <x-slot name="icon">
                                    <svg class="h-10 w-10 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </x-slot>
                                <x-slot name="title">Confirm Volunteer Request</x-slot>
                                <x-slot name="message">Are you sure you want to volunteer for {{ $volunteer->event->event_name }}? By confirming, you agree to commit to the event schedule and responsibilities.</x-slot>
                                <x-slot name="confirmButton">
                                    <span class="flex items-center">Confirm</span>
                                </x-slot>
                                <x-slot name="method">
                                    <input type="hidden" name="event_id" value="{{ $event->event_id }}">
                                </x-slot>
                            </x-confirm-action-modal>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-student-layout>