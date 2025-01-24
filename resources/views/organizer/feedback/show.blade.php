<!doctype html>
<html class="h-full bg-gray-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Event Feedback</title>
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
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Event Feedback for {{ $event->event_name }}</h1>
            </div>
        </header>

        <main x-data="testimonialSlider()">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <!-- Event Feedback Section -->
                <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                    <div class="text-center mt-8 mb-10">
                        <p class="text-gray-500 text-m uppercase">Feedback</p>
                        <h1 class="text-3xl font-bold text-gray-900 mt-5">See what the participants say about your event</h1>
                    </div>
        
                    <!-- Feedback Testimonials Section with sliding functionality -->
                    <div class="relative overflow-hidden pb-12">
                        <div class="flex transition-transform duration-500" :style="`transform: translateX(-${currentIndex * 100}%)`">
                            @foreach($event->feedback as $feedback)
                                <div class="flex-none w-full sm:w-1/2 lg:w-1/4 p-4">
                                    <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-300 transform transition duration-300 hover:scale-105 hover:border-indigo-500 hover:bg-white h-full flex flex-col justify-between">
                                        <div class="relative flex flex-col items-center">
                                            <!-- Display the student's image or a placeholder if not available -->
                                            <img class="h-28 w-28 rounded-full object-cover border-4 border-gray-200"
                                                 src="{{ $feedback->student->stud_img ? asset('storage/' . $feedback->student->stud_img) : asset('images/placeholder.jpg') }}"
                                                 alt="User Image">
                                            <div class="w-40 border-b-2 border-gray-150 mt-4"></div>
                                        </div>
                                        <div class="text-center mt-6 flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $feedback->student->stud_name }}</h3>
                                            <p class="text-indigo-600 text-sm mt-1">{{ $feedback->student->user->matric_no ?? 'Student' }}</p>
                                            <div class="mt-2 flex justify-center space-x-1">
                                                @for ($i = 0; $i < $feedback->rating; $i++)
                                                    <span class="text-yellow-500">★</span>
                                                @endfor
                                                @for ($i = $feedback->rating; $i < 5; $i++)
                                                    <span class="text-gray-300">★</span>
                                                @endfor
                                            </div>
                                            <p class="mt-4 text-sm text-gray-600 break-words max-h-20 overflow-hidden">{{ $feedback->feedback ?? 'No feedback provided.' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
        
                        <!-- Left and Right Arrows -->
                        <button @click="prevSlide" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white p-2 rounded-full" :disabled="currentIndex === 0">
                            &lt;
                        </button>
                        <button @click="nextSlide" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white p-2 rounded-full" :disabled="currentIndex === maxIndex - 1">
                            &gt;
                        </button>
        
                        <!-- Navigation Dots -->
                        <div class="flex justify-center absolute bottom-0 left-1/2 transform -translate-x-1/2 mb-4"> <!-- Added mb-4 -->
                            <template x-for="(dot, index) in maxIndex" :key="index">
                                <span 
                                    @click="currentIndex = index"
                                    :class="{
                                        'bg-black': currentIndex === index,
                                        'bg-gray-300': currentIndex !== index
                                    }"
                                    class="w-3 h-3 mx-1 rounded-full cursor-pointer transition-colors duration-300"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </main>                     
        
        <script>
            function testimonialSlider() {
                return {
                    currentIndex: 0,
                    itemsPerSlide: 4, // Adjust the number of items per slide
                    maxIndex: Math.ceil({{ $event->feedback->count() }} / 4), // Total number of slides needed
                    prevSlide() {
                        if (this.currentIndex > 0) {
                            this.currentIndex--;
                        }
                    },
                    nextSlide() {
                        if (this.currentIndex < this.maxIndex - 1) {
                            this.currentIndex++;
                        }
                    }
                }
            }
        </script>               
        
        <!-- Include Modals and Toasts -->
        @include('organizer.events.toast.success')
        @include('organizer.events.toast.error')
    </div>
</body>
@include('organizer.layouts.footer')  <!-- Including the footer -->
</html>