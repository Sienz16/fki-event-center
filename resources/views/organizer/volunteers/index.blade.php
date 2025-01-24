<x-organizer-layout>
    <x-slot:title>
        Manage Volunteers
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Volunteers</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
            <!-- Volunteers List Section -->
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <!-- Title Section -->
                <div class="mb-6 p-2 sm:p-4">
                    <h2 class="text-2xl font-semibold text-gray-900">Volunteer Requests</h2>
                    <p class="text-gray-600 mt-1">Manage volunteer requests for your events</p>
                    <hr class="border-t-2 border-gray-200 mt-4">
                </div>

                @livewire('organizer.volunteer-list')
            </div>
        </div>
    </div>

    <x-toast />
</x-organizer-layout>