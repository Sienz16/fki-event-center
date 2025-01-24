<x-admin-layout>
    <div x-data="{ open: false }">
        <x-slot:title>
            Event Details
        </x-slot>

        <x-slot:header>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $event->event_name }}</h1>
        </x-slot>

        <div class="min-h-[calc(100vh-65px)] pb-8">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
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
                                        <tr class="bg-purple-100">
                                            <th class="px-4 py-2 font-semibold text-gray-700">E-Certificate</th>
                                            <td class="px-4 py-2">
                                                @if($event->cert_template)
                                                    <div class="flex items-center space-x-2">
                                                        <button @click="open = true"
                                                                class="inline-flex items-center text-purple-600 hover:text-purple-800 text-sm">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Review Template
                                                        </button>
                                                        <span class="text-sm">
                                                            @if($event->template_status === 'pending')
                                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Pending Review</span>
                                                            @elseif($event->template_status === 'approved')
                                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Approved</span>
                                                            @elseif($event->template_status === 'rejected')
                                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Rejected</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-600 text-sm italic">Using default template</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-4 p-4 border border-gray-300 rounded-lg bg-purple-50">
                                <p>{{ $event->event_desc }}</p>
                            </div>
                        </div>

                        <!-- Replace the old actions section with the Livewire component -->
                        <livewire:admin.event-actions :event="$event" />
                    </div>
                </div>
            </div>
        </div>

        <div id="certModal" 
             x-cloak
             @keydown.escape.window="open = false"
             class="fixed inset-0 h-full w-full z-50 flex items-center justify-center"
             x-show="open"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             >
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50"
                 @click="open = false">
            </div>

            <div class="relative p-6 w-11/12 max-w-3xl bg-white rounded-lg shadow-xl z-10"
                 x-show="open"
                 x-transition:enter="transform transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
                 x-transition:leave="transform transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">E-Certificate Template Review</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <img src="{{ asset('storage/' . $event->cert_template) }}" 
                         alt="Certificate Template" 
                         class="w-full max-h-[70vh] object-contain rounded-lg shadow-md">
                </div>

                @if($event->template_status === 'pending')
                    <div class="text-center mt-4 pt-4 border-t border-purple-200 text-sm text-gray-600">
                        Use the action buttons below to approve or reject this template
                    </div>
                @endif
            </div>
        </div>

        <style>
            [x-cloak] { 
                display: none !important; 
            }
        </style>

        <x-toast />
    </div>
</x-admin-layout>