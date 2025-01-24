<div>
    <!-- Title and Sort Controls -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Committee Forum</h2>
        <div class="flex items-center">
            <span class="mr-3 text-gray-700 font-medium">Sort by:</span>
            <div class="inline-flex rounded-md shadow-sm">
                <button wire:click="sortBy('created_at')" 
                    class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-purple-300 bg-white text-sm font-medium transition-all duration-200 hover:bg-purple-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500 {{ $sortField === 'created_at' ? 'bg-purple-50 text-purple-700 border-purple-500' : 'text-gray-700' }}"
                >
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Date</span>
                    @if ($sortField === 'created_at')
                        <span class="ml-1 text-s">({{ $sortDirection === 'desc' ? 'Latest' : 'Oldest' }})</span>
                    @endif
                </button>
                <button wire:click="sortBy('likes')" 
                    class="relative inline-flex items-center px-4 py-2 border border-l-0 border-purple-300 bg-white text-sm font-medium transition-all duration-200 hover:bg-purple-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500 {{ $sortField === 'likes' ? 'bg-purple-50 text-purple-700 border-purple-500' : 'text-gray-700' }}"
                >
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>Likes</span>
                    @if ($sortField === 'likes')
                        <span class="ml-1 text-s">({{ $sortDirection === 'desc' ? 'Most' : 'Least' }})</span>
                    @endif
                </button>
                <button wire:click="sortBy('views')" 
                    class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-l-0 border-purple-300 bg-white text-sm font-medium transition-all duration-200 hover:bg-purple-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500 {{ $sortField === 'views' ? 'bg-purple-50 text-purple-700 border-purple-500' : 'text-gray-700' }}"
                >
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Views</span>
                    @if ($sortField === 'views')
                        <span class="ml-1 text-s">({{ $sortDirection === 'desc' ? 'Most' : 'Least' }})</span>
                    @endif
                </button>
            </div>
        </div>
    </div>
    <hr class="border-t-2 border-gray-300 mb-6">

    <!-- Card Grid -->
    <div class="px-6 grid grid-cols-2 gap-16">
        <div class="space-y-16">
            @foreach ($posts as $index => $post)
                @if ($loop->odd)
                    @include('organizer.community.components.community_card', ['post' => $post])
                @endif
            @endforeach
        </div>

        <div class="space-y-16 mt-20">
            @foreach ($posts as $index => $post)
                @if ($loop->even)
                    @include('organizer.community.components.community_card', ['post' => $post])
                @endif
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>