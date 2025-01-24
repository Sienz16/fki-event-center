<x-organizer-layout>
    <x-slot:title>
        Manage Events
    </x-slot>

    <x-slot:header>
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Events</h1>
            <a href="{{ route('organizer.events.create') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Create New Event
            </a>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <livewire:organizer.event-list />
        </div>
    </div>

    <x-toast />
</x-organizer-layout>