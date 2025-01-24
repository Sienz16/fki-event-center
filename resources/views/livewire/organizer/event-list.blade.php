<div>
    <div class="min-h-[calc(100vh-65px)] pb-8">
        <!-- Tabs Section -->
        <div class="w-full mb-6">
            <ul class="grid grid-flow-col text-center text-gray-500 bg-purple-200 rounded-full p-1 relative">
                <!-- Active Tab Indicator -->
                <div class="absolute inset-y-1 transition-all duration-300 ease-out bg-white rounded-full shadow"
                     style="width: calc(100% / 3); left: {{ match($tab) {
                         'active' => '0.5%',
                         'suspended' => '33.333333%',
                         'pending' => '66.333333%',
                         default => '0%'
                     } }};">
                </div>

                @foreach (['active', 'suspended', 'pending'] as $tabName)
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
                       wire:model.live="search" 
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
                <h2 class="text-3xl font-bold text-gray-900">{{ ucfirst($tab) }} Event Lists</h2>
                <hr class="border-t-2 border-gray-300 mt-2">
            </div>

            <!-- Events Grid -->
            <div>
                @if ($events->isEmpty())
                    <div class="flex justify-center items-center h-64">
                        <p class="text-gray-500 text-xl">No {{ ucfirst($tab) }} Events Available.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($events as $event)
                            @include('organizer.events.components.event_card', ['event' => $event])
                        @endforeach
                    </div>
                @endif
            </div>

            <hr class="border-t-2 border-gray-300 mt-10 mb-6">

            <!-- Pagination -->
            @if ($events->hasPages())
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
