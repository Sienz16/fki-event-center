<!-- Card and Modal Scope -->
<div x-data="{ open: false }">
    <!-- Card -->
    <div 
        class="bg-[#faf5ff] p-4 border-2 border-purple-200 shadow-lg transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl cursor-pointer"
        @click="open = true"
    >
        <img class="h-63 w-full object-cover" src="{{ asset('images/' . $post->img) }}" alt="Community Image">
        <p class="mt-3 text-gray-800">{{ $post->desc }}</p>
        <ul class="mt-3 flex flex-wrap">
            <li class="mr-auto text-gray-800 font-semibold">
                {{ $post->organizer->user->name ?? 'Unknown Organizer' }}
            </li>
            <li class="flex items-center text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 4.5C7.03 4.5 2.73 7.61 1 12c1.73 4.39 6.03 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6.03-7.5-11-7.5m0 10c-1.97 0-3.58-1.61-3.58-3.58S10.03 7.5 12 7.5s3.58 1.61 3.58 3.58S13.97 14.5 12 14.5z"/>
                </svg>
                <span class="ml-1">{{ $post->views }}</span>
            </li>
            <li class="ml-2 flex items-center text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                </svg>
                <span class="ml-1">{{ $post->likes }}</span>
            </li>
        </ul>
    </div>

    <!-- Modal -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="transition ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 z-[999] flex items-center justify-center backdrop-blur-md bg-grey/30" 
        @click.away="open = false"
        @click="open = false"
        style="display: none;"
    >
        <div 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden"
            @click.stop
        >
            <!-- Header Section -->
            <div class="px-6 py-4 bg-gray-50 border-b">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if ($post->organizer->org_img)
                        <img 
                            src="{{ asset('storage/' . $post->organizer->org_img) }}" 
                            alt="{{ $post->organizer->org_name }}"
                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200" 
                        />
                        @else
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $post->organizer->org_name }}</h3>
                            <p class="text-xs text-gray-500">{{ $post->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Close Button -->
                    <button 
                        @click="open = false"
                        class="p-1 hover:bg-gray-100 rounded-full transition-colors duration-200"
                    >
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Image Section -->
            <div class="relative bg-gray-100">
                <img 
                    src="{{ asset('images/' . $post->img) }}" 
                    alt="{{ $post->desc }}"
                    class="w-full h-[400px] object-contain"
                />
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <p class="text-gray-800 text-base mb-4">{{ $post->desc }}</p>
                
                <!-- Engagement Stats -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"/>
                            </svg>
                            <span class="ml-1.5 text-sm">{{ $post->likes }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 4.5C7.03 4.5 2.73 7.61 1 12c1.73 4.39 6.03 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6.03-7.5-11-7.5m0 10c-1.97 0-3.58-1.61-3.58-3.58S10.03 7.5 12 7.5s3.58 1.61 3.58 3.58S13.97 14.5 12 14.5z"/>
                            </svg>
                            <span class="ml-1.5 text-sm">{{ $post->views }}</span>
                        </div>
                    </div>

                    <!-- Delete Button -->
                    @if(auth()->user()->id === $post->organizer->user_id)
                    <form method="POST" action="{{ route('organizer.community.destroy', $post->com_id) }}">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="inline-flex items-center px-3 py-1.5 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors duration-200"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
