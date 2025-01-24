<!doctype html>
<html class="h-full bg-gray-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Event Feedbacks</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    [x-cloak] {
        display: none !important;
    }
  </style>
</head>
<body class="h-full" x-data="{ open: false }">
    <div class="min-h-full">
        @include('organizer.layouts.organizer_nav')  <!-- Including the navigation bar -->

        <!-- Header Section -->
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Event Feedback</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- Events with Feedback List Section -->
                <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                    <div class="mb-6 p-2 sm:p-4">
                        <h2 class="text-3xl font-bold text-gray-900">Events with Feedback</h2>
                        <hr class="border-t-2 border-gray-300 mt-2">
                    </div>

                    <!-- Events with Feedback -->
                    <div>
                        @if($eventsWithFeedback->isEmpty())
                            <div class="flex justify-center items-center h-64">
                                <p class="text-gray-500 text-xl">No events with feedback available.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($eventsWithFeedback as $event)
                                    <div class="bg-[#F8F7F4] p-4 rounded-lg shadow-lg flex flex-col justify-between">
                                        @if($event->event_img)
                                            <img src="{{ asset('storage/' . $event->event_img) }}" alt="{{ $event->event_name }}" class="w-full h-80 object-cover rounded-lg mb-4">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder Image" class="w-full h-80 object-cover rounded-lg mb-4">
                                        @endif
                                        <div class="flex-grow">
                                            <h2 class="text-2xl font-bold text-black mb-2">{{ $event->event_name }}</h2>
                                            <p class="text-gray-600">
                                                Feedback Count: {{ $event->feedback->count() }}
                                            </p>
                                            
                                            <!-- Display Average Star Rating and the number beside it -->
                                            @php
                                                $averageRating = number_format($event->average_rating, 1); // Access the average rating and format it to 1 decimal
                                                $fullStars = floor($averageRating); // Get the number of full stars
                                                $halfStar = ($averageRating - $fullStars) >= 0.5 ? 1 : 0; // Check if there's a half star
                                                $emptyStars = 5 - ($fullStars + $halfStar); // Calculate the number of empty stars
                                            @endphp
                                            
                                            <div class="flex items-center mt-2">
                                                <!-- Display stars -->
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <span class="text-yellow-500">★</span>
                                                @endfor
                                                
                                                @if ($halfStar)
                                                    <span class="text-yellow-500">★</span> <!-- Use a half-star icon if you have one -->
                                                @endif
                                                
                                                @for ($i = 0; $i < $emptyStars; $i++)
                                                    <span class="text-gray-300">★</span>
                                                @endfor

                                                <!-- Display the average rating number -->
                                                <span class="ml-2 text-gray-700">{{ $averageRating }}</span>
                                            </div>
                                        </div>
                                        <div class="text-gray-500 p-1 sm:p-1 text-sm mt-4">
                                            <p>Last updated: {{ $event->updated_at->diffForHumans() }}</p>
                                        </div>
                                        <a href="{{ route('organizer.feedback.show', $event->event_id) }}" 
                                            class="mt-auto bg-blue-600 text-white rounded-lg px-4 py-2 border border-blue-700 hover:bg-blue-700 text-center">
                                            {{ __('View Feedback') }}
                                        </a>                                         
                                    </div>    
                                @endforeach
                            </div>
                        
                            <!-- Pagination links -->
                            <div class="mt-4">
                                {{ $eventsWithFeedback->links() }}
                            </div>
                        @endif
                    </div>

                    {{-- <hr class="border-t-2 border-gray-300 mt-10 mb-6">

                    <!-- Pagination Links -->
                    <div class="mt-6 flex justify-center">
                        <nav aria-label="Page navigation example">
                            <ul class="inline-flex space-x-1 text-sm">
                                <!-- Previous Page Link -->
                                <li>
                                    @if ($eventsWithFeedback->onFirstPage())
                                        <span class="px-3 py-2 text-gray-500 bg-white border border-gray-300 rounded-l-lg">Previous</span>
                                    @else
                                        <a href="{{ $eventsWithFeedback->previousPageUrl() }}" class="px-3 py-2 text-gray-500 bg-white border border-gray-300 rounded-l-lg">Previous</a>
                                    @endif
                                </li>

                                <!-- Pagination Links -->
                                @foreach (range(1, $eventsWithFeedback->lastPage()) as $page)
                                    <li>
                                        <a href="{{ $eventsWithFeedback->url($page) }}" class="px-3 py-2 text-gray-500 bg-white border border-gray-300 {{ $page == $eventsWithFeedback->currentPage() ? 'text-blue-600 bg-blue-50' : '' }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                <!-- Next Page Link -->
                                <li>
                                    @if ($eventsWithFeedback->hasMorePages())
                                        <a href="{{ $eventsWithFeedback->nextPageUrl() }}" class="px-3 py-2 text-gray-500 bg-white border border-gray-300 rounded-r-lg">Next</a>
                                    @else
                                        <span class="px-3 py-2 text-gray-500 bg-white border border-gray-300 rounded-r-lg">Next</span>
                                    @endif
                                </li>
                            </ul>
                        </nav>
                    </div> --}}
                </div>
            </div>
        </main>

        <!-- Include Modals and Toasts -->
        @include('organizer.events.toast.success')
        @include('organizer.events.toast.error')
    </div>
</body>
@include('organizer.layouts.footer')  <!-- Including the footer -->
</html>