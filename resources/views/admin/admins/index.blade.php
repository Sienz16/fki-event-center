<x-admin-layout>
    <x-slot:title>Manage Admins</x-slot>

    <x-slot:header>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Admins</h1>
                <p class="mt-1 text-sm text-gray-600">View and manage administrator accounts</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" 
               class="rounded-lg relative px-4 py-2 text-sm font-medium text-white overflow-hidden transition-all duration-300 ease-in-out
                      before:absolute before:inset-0 before:bg-gradient-to-r before:from-[#9d00ff] before:via-purple-500 before:to-[#9d00ff]
                      hover:before:scale-x-[1.15] hover:before:scale-y-[1.1] hover:shadow-purple-500/50
                      focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <span class="relative">Add New Admin</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Admin List -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <!-- Add your admin list table/grid here -->
            </div>
        </div>
    </div>

    <x-toast />
</x-admin-layout> 