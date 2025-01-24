<x-organizer-layout>
    <x-slot:title>
        Participants for {{ $event->event_name }}
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $event->event_name }}</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                <livewire:organizer.participants-list :event="$event" />
            </div>
        </div>
    </div>

    <x-toast />
</x-organizer-layout>