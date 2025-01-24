<x-student-layout>
    <x-slot:title>
        View Events
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Events</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <livewire:student.event-list />
        </div>
    </div>
</x-student-layout>
