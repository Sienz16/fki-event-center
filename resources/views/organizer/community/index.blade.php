<x-organizer-layout>
    <x-slot:title>
        Manage Community Forum
    </x-slot>

    <x-slot:header>
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Community Forum</h1>
            <a href="{{ route('organizer.community.create') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
              Add New Post
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Community Forum List Section -->
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
            {{-- <!-- Title and Border -->
            <div class="mb-6 p-2 sm:p-4">
                <h2 class="text-3xl font-bold text-gray-900">Committee Forum</h2>
                <hr class="border-t-2 border-gray-300 mt-2">
            </div>
             --}}
            <!-- Livewire Community List Component -->
            <livewire:organizer.community-list />
        </div>
    </div>
</x-organizer-layout>