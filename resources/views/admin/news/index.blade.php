<x-admin-layout>
    <x-slot:title>
        News Index
    </x-slot>

    <x-slot:header>
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">News Index</h1>
            <a href="{{ route('admin.news.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Create New News
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <livewire:admin.news-list />
    </div>
</x-admin-layout>