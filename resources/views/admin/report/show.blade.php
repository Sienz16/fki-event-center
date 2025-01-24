<x-admin-layout>
    <x-slot:title>
        Event Analysis
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Event Analysis for {{ $event->event_name }}</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div x-data="testimonialSlider()">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                
                <!-- Feedback Section -->
                <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg mb-10">
                    <div class="text-center mt-8 mb-10">
                        <p class="text-gray-500 text-m uppercase">Feedback</p>
                        <h1 class="text-3xl font-bold text-gray-900 mt-5">See what the participants say about this event</h1>
                    </div>

                    <!-- Check if feedback exists -->
                    @if($event->feedback->isEmpty())
                        <p class="text-center text-gray-500 text-xl my-10">No Feedback Yet</p>
                    @else
                        <!-- Feedback Testimonials Section with sliding functionality -->
                        <div class="relative overflow-hidden pb-12">
                            <div class="flex transition-transform duration-500" :style="`transform: translateX(-${currentIndex * 100}%)`">
                                @foreach($event->feedback as $feedback)
                                    <div class="flex-none w-full sm:w-1/2 lg:w-1/4 p-4">
                                        <div class="bg-[#faf5ff] p-6 rounded-lg shadow-md border border-purple-200 transform transition duration-300 hover:scale-105 hover:border-purple-500 hover:bg-white h-full flex flex-col justify-between">
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
                    @endif
                </div>

                <!-- Participants Section -->
                <div class="border border-gray-300 p-6 bg-white shadow-lg rounded-lg">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Participants List</h2>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-purple-400">
                                <tr>
                                    <th scope="col" class="px-3 py-2">No</th>
                                    <th scope="col" class="px-3 py-2">Student Name</th>
                                    <th scope="col" class="px-3 py-2">Matric No</th>
                                    <th scope="col" class="px-3 py-2">Email</th>
                                    <th scope="col" class="px-3 py-2">Phone No</th>
                                    <th scope="col" class="px-3 py-2">Course</th>
                                    <th scope="col" class="px-3 py-2">Register Date</th>
                                    <th scope="col" class="px-3 py-2">Status</th>
                                    <th scope="col" class="px-3 py-2">Attendance Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($participants as $index => $participant)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-purple-50' : 'bg-white' }} hover:bg-purple-100">
                                        <td class="px-3 py-2 text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="px-3 py-2 align-middle">{{ $participant->user_name }}</td>
                                        <td class="px-3 py-2 align-middle">{{ $participant->matric_no }}</td>
                                        <td class="px-3 py-2 align-middle truncate">{{ $participant->email }}</td>
                                        <td class="px-3 py-2 align-middle">{{ $participant->stud_phoneNo }}</td>
                                        <td class="px-3 py-2 align-middle truncate">{{ $participant->stud_course }}</td>
                                        <td class="px-3 py-2 align-middle">{{ \Carbon\Carbon::parse($participant->register_datetime)->format('F j, Y, g:i a') }}</td>
                                        <td class="px-3 py-2 align-middle">{{ $participant->status }}</td>
                                        <td class="px-3 py-2 align-middle">
                                            @if ($participant->attendance_datetime)
                                                {{ \Carbon\Carbon::parse($participant->attendance_datetime)->format('F j, Y, g:i a') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-3 py-2 text-center text-gray-500">No participants found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</x-admin-layout>