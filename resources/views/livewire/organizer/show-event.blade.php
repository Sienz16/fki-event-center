<div class="min-h-[calc(100vh-65px)] pb-8" 
     x-data="{ 
        deleteModalOpen: @entangle('deleteModalOpen'),
        requestActivationOpen: @entangle('requestActivationOpen'),
        deleteUrl: '{{ route('organizer.events.destroy', $event->event_id) }}'
     }">

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

            <!-- Event Details Section -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center md:pl-8">
                <div class="w-full text-center md:text-left">
                    <div class="overflow-hidden rounded-lg border border-gray-300 shadow-sm mb-4">
                        <table class="w-full text-left">
                            <tbody>
                                <tr class="bg-purple-100">
                                    <th class="px-4 py-2 font-semibold text-gray-700">Date</th>
                                    <td class="px-4 py-2">
                                        @if($event->event_date)
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($event->event_start_date)->format('F j, Y') }} - 
                                            {{ \Carbon\Carbon::parse($event->event_end_date)->format('F j, Y') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 font-semibold text-gray-700">Time</th>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($event->event_start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($event->event_end_time)->format('g:i A') }}
                                    </td>
                                </tr>
                                <tr class="bg-purple-100">
                                    <th class="px-4 py-2 font-semibold text-gray-700">Type</th>
                                    <td class="px-4 py-2 capitalize">{{ $event->event_type }}</td>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 font-semibold text-gray-700">Location</th>
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
                                <tr class="bg-purple-100">
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
                                <tr>
                                    <th class="px-4 py-2 font-semibold text-gray-700">E-Cert Template</th>
                                    <td class="px-4 py-2">
                                        @if($event->cert_template)
                                            <span class="text-green-600 font-medium">Uploaded</span>
                                        @else
                                            <span class="text-red-600 font-medium">Not Uploaded</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-purple-100">
                                    <th class="px-4 py-2 font-semibold text-gray-700">Status</th>
                                    <td class="px-4 py-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($event->event_status === 'active') bg-green-100 text-green-800
                                            @elseif($event->event_status === 'suspended') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($event->event_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 font-semibold text-gray-700">Event Code</th>
                                    <td class="px-4 py-2">
                                        @if($event->event_status === 'active')
                                            <button type="button"
                                                    wire:click="$set('showEventCodeModal', true)"
                                                    class="w-44 text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                                </svg>
                                                Show Code
                                            </button>
                                        @else
                                            <span class="text-gray-500 italic">Not Available</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-purple-100">
                                    <th class="px-4 py-2 font-semibold text-gray-700">Participants</th>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('organizer.events.participants', ['event' => $event->event_id]) }}" 
                                           class="w-46 text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                            </svg>
                                            Participants List
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4 p-4 border border-purple-300 rounded-lg bg-purple-50">
                        <p>{{ $event->event_desc }}</p>
                    </div>

                    @if($event->event_status === 'suspended')
                        <div class="text-center text-red-600 font-semibold mb-4">
                            This event is suspended by the admin!
                        </div>
                    @endif
                </div>

                <!-- Buttons Section -->
                <div class="mt-4 flex justify-center md:justify-start space-x-4">
                    @if($event->event_status === 'active')
                        <a href="{{ route('organizer.events.edit', $event->event_id) }}"
                            class="bg-purple-600 text-white w-40 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 text-center">
                            Edit Event
                        </a>
                        <button type="button" 
                                wire:click="$set('deleteModalOpen', true)"
                                class="bg-red-500 text-white w-40 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete Event
                        </button>
                    @elseif($event->event_status === 'suspended')
                        <button type="button" 
                            wire:click="$set('requestActivationOpen', true)"
                            class="bg-green-500 text-white w-40 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Request Activation
                        </button>
                        <a href="{{ route('organizer.events.edit', $event->event_id) }}"
                        class="bg-yellow-500 text-white w-40 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 flex items-center justify-center">
                        Edit Event
                        </a>
                        <button type="button" 
                                wire:click="$set('deleteModalOpen', true)"
                                class="bg-red-500 text-white w-40 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete Event
                        </button>
                    @elseif($event->event_status === 'pending')
                        <div class="text-center text-yellow-600 font-semibold mb-4">
                            This event is under review by the admin.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <x-confirm-delete-modal 
        title="Event"
        message="Are you sure you want to delete this event? This action cannot be undone.">
    </x-confirm-delete-modal>

    <!-- Request Activation Modal -->
    @include('organizer.events.modal.request_active', ['event' => $event])

    <x-toast />

    <!-- Event Code Modal -->
    <div x-show="showEventCodeModal"
         class="relative z-10"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true"
         x-data="{ 
            showEventCodeModal: @entangle('showEventCodeModal'),
            timeRemaining: @entangle('timeRemaining'),
            timer: null,
            startTimer() {
                this.timer = setInterval(() => {
                    if (this.timeRemaining > 0) {
                        @this.timeRemaining--;
                    } else {
                        @this.refreshEventCode();
                    }
                }, 1000);
            },
            stopTimer() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }
         }"
         x-init="
            $watch('showEventCodeModal', value => {
                if (value) {
                    startTimer();
                } else {
                    stopTimer();
                    @this.resetTimer();
                }
            })
         "
         >
        <!-- Backdrop -->
        <div x-show="showEventCodeModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="showEventCodeModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:p-8">
                    <div>
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-purple-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div class="mt-4 text-center">
                            <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Event Code</h3>
                            <div class="mt-6">
                                <div class="bg-purple-50 rounded-lg p-6 border-2 border-purple-200">
                                    <p class="text-4xl font-bold text-purple-600 tracking-wider select-all">{{ $event->event_code }}</p>
                                </div>
                                <div class="mt-4 flex items-center justify-center gap-2 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Refreshing in <span class="font-bold text-purple-600" x-text="timeRemaining"></span> seconds</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="button"
                                wire:click="$set('showEventCodeModal', false)"
                                class="inline-flex w-full justify-center items-center gap-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-purple-700 hover:to-purple-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600 transition-all duration-200">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 