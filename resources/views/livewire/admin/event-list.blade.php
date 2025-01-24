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
                         'requested' => '66.3333334%',
                         default => '0%'
                     } }};">
                </div>

                @foreach (['active', 'suspended', 'requested'] as $tabName)
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
                            @include('admin.events.components.event_card', ['event' => $event])
                        @endforeach
                    </div>
                @endif
            </div>

            <hr class="border-t-2 border-gray-300 mt-10 mb-6">

            <!-- Pagination Section -->
            @if ($events->hasPages())
                <div class="mt-6">
                    <div class="flex justify-center">
                        <nav class="inline-flex rounded-md shadow-sm isolate" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            @if ($events->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-md">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @else
                                <button wire:click="previousPage" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-purple-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif

                            {{-- Page Numbers --}}
                            @foreach ($events->getUrlRange(max($events->currentPage() - 2, 1), min($events->currentPage() + 2, $events->lastPage())) as $page => $url)
                                @if ($page == $events->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-purple-600 cursor-default focus:z-10">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-purple-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($events->hasMorePages())
                                <button wire:click="nextPage" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-purple-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-md">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                    
                    <!-- Optional: Page Information -->
                    <div class="mt-3 text-sm text-gray-600 text-center">
                        Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} results
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
