<x-student-layout>
    <x-slot:title>
        Available Volunteer Requests
    </x-slot>

    <x-slot:header>
        Volunteer Opportunities
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]">
        <livewire:student.volunteer-requests 
            :volunteers="$volunteers" 
            :submitted-requests="$submittedRequests" />
    </div>
</x-student-layout>
