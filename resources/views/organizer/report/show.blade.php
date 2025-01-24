<x-organizer-layout>
    <x-slot:title>
        Manage Event Feedback
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Event Feedback for {{ $event->event_name }}</h1>
    </x-slot>

    <main x-data="testimonialSlider()">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Event Stats Section -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Feedback Summary Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Feedback Summary</h2>
                            <p class="text-sm text-gray-600 mt-1">Total feedback received</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-purple-600">{{ $event->feedback->count() }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex text-yellow-400">
                                @php
                                    $avgRating = $event->feedback->avg('rating');
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $avgRating)
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <p class="ml-2 text-sm text-gray-600">{{ number_format($avgRating, 1) }} average rating</p>
                        </div>
                    </div>
                </div>

                <!-- Certificates Issued Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Certificates Issued</h2>
                            <p class="text-sm text-gray-600 mt-1">Total e-certificates generated</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        @php
                            $totalCertificates = $event->ecertificates->count();
                            $totalAttended = $event->attendances()->where('status', 'Attended')->count();
                        @endphp
                        <p class="text-3xl font-bold text-green-600">{{ $totalCertificates }}</p>
                        <p class="text-sm text-gray-600 mt-2">
                            Certificates generated from {{ $totalAttended }} attended participants
                        </p>
                    </div>
                </div>
            </div>

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
                                <div class="bg-[#faf5ff] p-6 rounded-lg shadow-md border border-purple-200 transform transition duration-300 hover:scale-105 hover:border-purple-500 hover:bg-white h-full flex flex-col justify-between">
                                    <div class="relative flex flex-col items-center">
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
                    <button @click="prevSlide" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-purple-700 text-white p-2 rounded-full" :disabled="currentIndex === 0">
                        &lt;
                    </button>
                    <button @click="nextSlide" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-purple-700 text-white p-2 rounded-full" :disabled="currentIndex === maxIndex - 1">
                        &gt;
                    </button>
    
                    <!-- Navigation Dots -->
                    <div class="flex justify-center absolute bottom-0 left-1/2 transform -translate-x-1/2 mb-4">
                        <template x-for="(dot, index) in maxIndex" :key="index">
                            <span 
                                @click="currentIndex = index"
                                :class="{
                                    'bg-purple-700': currentIndex === index,
                                    'bg-purple-300': currentIndex !== index
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
                itemsPerSlide: 4,
                maxIndex: Math.ceil({{ $event->feedback->count() }} / 4),
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

    <x-toast />
</x-organizer-layout>