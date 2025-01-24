<div x-data
    @pageChanged.window="$el.closest('.min-h-[calc(100vh-65px)]').scrollIntoView({ behavior: 'smooth' })"
>
    <!-- Loading Bar -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-purple-500 overflow-hidden">
            <div class="w-full h-full origin-left bg-purple-300 animate-loading-bar"></div>
        </div>
    </div>

    @if($events->isEmpty())
        <div class="flex justify-center items-center h-64">
            <p class="text-gray-500 text-xl">No events available.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-[#faf5ff] hover:bg-white p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-purple-200 relative overflow-hidden">
                    <!-- Event Image -->
                    <div class="mb-4">
                        <img src="{{ $event->event_img ? asset('storage/' . $event->event_img) : asset('images/placeholder.jpg') }}" 
                             alt="{{ $event->event_name }}" 
                             class="w-full h-80 object-cover rounded-lg">
                    </div>

                    <!-- Content -->
                    <div class="flex-grow space-y-3">
                        <h2 class="text-xl font-bold text-gray-900 group-hover:text-purple-800 transition-colors duration-300">
                            {{ $event->event_name }}
                        </h2>

                        <!-- Rating Section -->
                        <div class="flex items-center space-x-2">
                            @if ($event->average_rating)
                                <div class="flex items-center text-yellow-500">
                                    @php
                                        $averageRating = number_format($event->average_rating, 1);
                                        $fullStars = floor($averageRating);
                                        $halfStar = ($averageRating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - ($fullStars + $halfStar);
                                    @endphp
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    @if ($halfStar)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm font-medium text-gray-600">{{ $averageRating }}</span>
                            @else
                                <span class="text-sm text-gray-500 italic">No Rating Yet</span>
                            @endif
                        </div>

                        <!-- Organizer Info -->
                        <div class="flex items-center space-x-3">
                            <img src="{{ $event->org_img ? asset('storage/' . $event->org_img) : asset('images/avatar_placeholder.png') }}" 
                                 alt="{{ $event->org_name }}" 
                                 class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium text-gray-700">{{ $event->org_name }}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs text-gray-500">
                                Updated {{ \Carbon\Carbon::parse($event->updated_at)->diffForHumans() }}
                            </span>
                        </div>
                        
                        <a href="{{ route('admin.report.show', $event->event_id) }}" 
                           class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white rounded-lg px-4 py-2.5 
                                  transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                            View Report
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @endif
</div> 