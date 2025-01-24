<x-admin-layout>
    <x-slot:title>
        Venue Details
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $venue->venue_name }}</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8"> <!-- Added this wrapper with min-height -->
        <div x-data="{
            deleteModalOpen: false,
            deleteUrl: '',
            calendarOpen: false,
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            events: {{ Js::from($venue->events) }},
            get daysInMonth() {
                return new Date(this.year, this.month + 1, 0).getDate();
            },
            get firstDayOfMonth() {
                return new Date(this.year, this.month, 1).getDay();
            },
            get currentMonthYear() {
                return new Date(this.year, this.month).toLocaleString('default', { month: 'long', year: 'numeric' });
            },
            hasEvent(day) {
                return this.getEventsForDay(day).length > 0;
            },
            getEventsForDay(day) {
                const currentDate = new Date(this.year, this.month, day);
                currentDate.setHours(0, 0, 0, 0);

                return this.events.filter(event => {
                    let eventDateMatch = false;

                    if (event.event_date) {
                        const eventDate = new Date(event.event_date);
                        eventDate.setHours(0, 0, 0, 0);
                        eventDateMatch = eventDate.getTime() === currentDate.getTime();
                    } else if (event.event_start_date && event.event_end_date) {
                        const startDate = new Date(event.event_start_date);
                        const endDate = new Date(event.event_end_date);
                        startDate.setHours(0, 0, 0, 0);
                        endDate.setHours(0, 0, 0, 0);

                        eventDateMatch = currentDate >= startDate && currentDate <= endDate;
                    }

                    return eventDateMatch;
                }).map(event => ({
                    ...event,
                    startTime: event.event_start_time ? event.event_start_time : 'Not specified',
                    endTime: event.event_end_time ? event.event_end_time : 'Not specified'
                }));
            },
            nextMonth() {
                this.month++;
                if (this.month > 11) {
                    this.month = 0;
                    this.year++;
                }
            },
            previousMonth() {
                this.month--;
                if (this.month < 0) {
                    this.month = 11;
                    this.year--;
                }
            }
        }">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-lg rounded-lg flex flex-col md:flex-row">
                    <div class="w-full md:w-1/2 mb-4 md:mb-0">
                        <img src="{{ $venue->venue_image ? asset('storage/' . $venue->venue_image) : asset('images/placeholder.jpg') }}" alt="{{ $venue->venue_name }}" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col justify-center md:pl-8">
                        <h2 class="text-3xl font-bold mb-2">{{ $venue->venue_name }}</h2>
                        <div class="mt-4 rounded-lg overflow-hidden shadow-lg">
                            <table class="w-full divide-y divide-gray-200 border border-gray-300">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="bg-purple-100">
                                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $venue->venue_location }}</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $venue->capacity ?? 'Not specified' }} people</td>
                                    </tr>
                                    <tr class="bg-purple-100">
                                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">{{ $venue->equipment ?? 'No equipment listed' }}</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $venue->venue_details ?? 'No details available.' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6 rounded-lg bg-purple-200 bg-opacity-50 p-2 flex items-center justify-center space-x-2">
                            <span class="text-gray-700">See Venue Schedule -></span>
                            <button @click="calendarOpen = true" class="bg-purple-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-purple-700 transition duration-200 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008H16.5V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-center mt-8 space-x-2">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.venue.edit', $venue->venue_id) }}" 
                            class="inline-block w-24 text-center rounded-md bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700">
                                Edit
                            </a>
                            
                            <!-- Delete Button -->
                            <button type="button"
                                    @click="deleteModalOpen = true; deleteUrl = '{{ route('admin.venue.destroy', $venue->venue_id) }}'"
                                    class="inline-block w-24 text-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Modal -->
            <div x-show="calendarOpen" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-4 relative" @click.away="calendarOpen = false">
                    <button @click="calendarOpen = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold transition duration-200 ease-in-out" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">&times;</button>
                    <h2 class="text-xl font-bold mb-4">Calendar for {{ $venue->venue_name }}</h2>

                    <!-- Calendar Navigation -->
                    <div class="flex justify-between items-center mb-4">
                        <button @click="previousMonth" class="text-gray-700 hover:text-gray-900 transition duration-200 ease-in-out">&laquo; Previous</button>
                        <h3 class="text-lg font-semibold text-gray-900" x-text="currentMonthYear"></h3>
                        <button @click="nextMonth" class="text-gray-700 hover:text-gray-900 transition duration-200 ease-in-out">Next &raquo;</button>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-2 text-center text-gray-900 font-medium mb-4">
                        <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                            <div x-text="day"></div>
                        </template>
                        <template x-for="i in firstDayOfMonth">
                            <div></div>
                        </template>
                        <template x-for="day in daysInMonth" :key="day">
                            <div class="p-2 rounded relative transition duration-200 ease-in-out" x-data="{ showTooltip: false }"
                                :class="hasEvent(day) ? 'bg-blue-200 rounded-lg' : ''"
                                @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">
                                <div x-text="day"></div>

                                <!-- Tooltip with event details on hover -->
                                <div x-show="showTooltip && hasEvent(day)" class="absolute top-full left-0 mt-1 w-40 bg-gray-800 text-white text-xs rounded p-2 shadow-lg" style="z-index: 50;">
                                    <template x-for="event in getEventsForDay(day)" :key="event.event_id">
                                        <div>
                                            <strong x-text="event.event_name"></strong><br>
                                            <span x-text="event.startTime + ' - ' + event.endTime"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Add the delete confirmation modal component -->
            <x-confirm-delete-modal title="Venue" />
        </div>
    </div>

    <script>
        function calendarModal() {
            return {
                calendarOpen: false,
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
                events: @json($venue->events),
                get daysInMonth() {
                    return new Date(this.year, this.month + 1, 0).getDate();
                },
                get firstDayOfMonth() {
                    return new Date(this.year, this.month, 1).getDay();
                },
                get currentMonthYear() {
                    return new Date(this.year, this.month).toLocaleString('default', { month: 'long', year: 'numeric' });
                },
                hasEvent(day) {
                    return this.getEventsForDay(day).length > 0;
                },
                getEventsForDay(day) {
                    const currentDate = new Date(this.year, this.month, day);
                    currentDate.setHours(0, 0, 0, 0);

                    return this.events.filter(event => {
                        let eventDateMatch = false;

                        if (event.event_date) {
                            const eventDate = new Date(event.event_date);
                            eventDate.setHours(0, 0, 0, 0);
                            eventDateMatch = eventDate.getTime() === currentDate.getTime();
                        } else if (event.event_start_date && event.event_end_date) {
                            const startDate = new Date(event.event_start_date);
                            const endDate = new Date(event.event_end_date);
                            startDate.setHours(0, 0, 0, 0);
                            endDate.setHours(0, 0, 0, 0);

                            eventDateMatch = currentDate >= startDate && currentDate <= endDate;
                        }

                        return eventDateMatch;
                    }).map(event => ({
                        ...event,
                        startTime: event.event_start_time ? event.event_start_time : 'Not specified',
                        endTime: event.event_end_time ? event.event_end_time : 'Not specified'
                    }));
                },
                nextMonth() {
                    this.month++;
                    if (this.month > 11) {
                        this.month = 0;
                        this.year++;
                    }
                },
                previousMonth() {
                    this.month--;
                    if (this.month < 0) {
                        this.month = 11;
                        this.year--;
                    }
                }
            }
        }
    </script>
</x-admin-layout>
