<x-organizer-layout>
    <x-slot:title>
        Event Details
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $event->event_name }}</h1>
    </x-slot>

    <livewire:organizer.show-event :event="$event" />
    
    <x-toast />
</x-organizer-layout>