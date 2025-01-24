<x-student-layout>
    <x-slot:title>
        Community Forum
    </x-slot>

    <x-slot:header>
        Community Forum
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]">
        <!-- Community Forum List Section -->
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
            <!-- Title and Border -->
            <div class="mb-6 p-2 sm:p-4">
                <h2 class="text-3xl font-bold text-gray-900">Community Posts</h2>
                <hr class="border-t-2 border-gray-300 mt-2">
            </div>
            
            <!-- Card Grid with Staggered Columns and Container Padding -->
            <div class="px-6 grid grid-cols-2 gap-16">
                <div class="space-y-16">
                    @foreach ($communityPosts as $index => $post)
                        @if ($loop->odd)
                            @include('student.community.components.community_card', ['post' => $post])
                        @endif
                    @endforeach
                </div>

                <div class="space-y-16 mt-20">
                    @foreach ($communityPosts as $index => $post)
                        @if ($loop->even)
                            @include('student.community.components.community_card', ['post' => $post])
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-student-layout>