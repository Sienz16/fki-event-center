<!-- Card and Modal Scope -->
<div x-data="{ open: false }">
    <!-- Card -->
    <div 
    class="bg-[#faf5ff] p-4 border-2 border-purple-200 shadow-lg transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl cursor-pointer"
    @click="open = true; registerView({{ $post->com_id }}, $event)"
    >
        <img class="h-63 w-full object-cover" src="{{ asset('images/' . $post->img) }}" alt="Community Image">
        <p class="mt-3 text-gray-800">{{ $post->desc }}</p>
        <ul class="mt-3 flex flex-wrap">
            <li class="mr-auto text-gray-800 font-semibold">
                {{ $post->organizer->user->name ?? 'Unknown Organizer' }}
            </li>
            <li class="flex items-center text-gray-500 hover:text-gray-700">
                <!-- View Count -->
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 4.5C7.03 4.5 2.73 7.61 1 12c1.73 4.39 6.03 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6.03-7.5-11-7.5m0 10c-1.97 0-3.58-1.61-3.58-3.58S10.03 7.5 12 7.5s3.58 1.61 3.58 3.58S13.97 14.5 12 14.5z"/>
                </svg>
                <span id="views-{{ $post->com_id }}">{{ $post->views }}</span> <!-- Views count -->
            </li>
            <li class="ml-2 flex items-center text-gray-500 hover:text-gray-700">
                <!-- Like Button -->
                <button 
                    id="like-btn-{{ $post->com_id }}" 
                    type="button" 
                    class="flex items-center {{ $post->liked_by_user ? 'text-red-600' : 'text-gray-500' }}" 
                    @click.stop="toggleLike({{ $post->com_id }})"
                >
                    <!-- Heart Icon -->
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                    </svg>
                    <span id="likes-count-{{ $post->com_id }}" class="ml-1">{{ $post->likes }}</span> <!-- Likes count -->
                </button>
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
                        <!-- Like Button -->
                        <button 
                            id="like-btn-{{ $post->com_id }}" 
                            type="button" 
                            class="flex items-center {{ $post->liked_by_user ? 'text-red-600' : 'text-gray-700' }}" 
                            @click.stop="toggleLike({{ $post->com_id }})"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"/>
                            </svg>
                            <span id="likes-count-{{ $post->com_id }}" class="ml-1.5 text-sm">{{ $post->likes }}</span>
                        </button>
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 4.5C7.03 4.5 2.73 7.61 1 12c1.73 4.39 6.03 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6.03-7.5-11-7.5m0 10c-1.97 0-3.58-1.61-3.58-3.58S10.03 7.5 12 7.5s3.58 1.61 3.58 3.58S13.97 14.5 12 14.5z"/>
                            </svg>
                            <span id="views-{{ $post->com_id }}" class="ml-1.5 text-sm">{{ $post->views }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js Script -->
<script>
    function toggleLike(postId) {
        fetch(`/student/community/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update all instances of like count and button color
            const likeButtons = document.querySelectorAll(`#like-btn-${postId}`);
            const likesCountElements = document.querySelectorAll(`#likes-count-${postId}`);

            // Update each instance of the like count
            likesCountElements.forEach(element => {
                element.textContent = data.likes;
            });

            // Update each instance of the like button color
            likeButtons.forEach(button => {
                if (data.status === 'liked') {
                    button.classList.remove('text-gray-500', 'text-gray-700');
                    button.classList.add('text-red-600');
                } else {
                    button.classList.remove('text-red-600');
                    button.classList.add('text-gray-500', 'text-gray-700');
                }
            });
        })
        .catch(error => console.error('Error:', error));
    }

    function registerView(postId, event) {
        // Prevent multiple rapid clicks
        if (event) {
            event.preventDefault();
        }

        fetch(`/student/community/${postId}/view`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update all instances of the view count
            const viewsElements = document.querySelectorAll(`#views-${postId}`);
            viewsElements.forEach(element => {
                element.textContent = data.views;
            });
        })
        .catch(error => console.error('Error:', error));
    }
</script>