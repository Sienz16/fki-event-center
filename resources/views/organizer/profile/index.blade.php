<x-organizer-layout>
    <x-slot:title>
        Organizer Profile
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Organizer Profile</h1>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-65px)] pb-8">
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
            <div class="p-3 sm:p-0 mt-6">
                <!-- Profile Section -->
                <div class="flex">
                    <!-- Left Column: Profile Image and Edit Button -->
                    <div class="flex flex-col justify-center items-center w-1/3 h-full mt-12">
                        <img class="w-48 h-48 rounded-full object-cover" 
                             src="{{ $organizer->org_img ? asset('storage/' . $organizer->org_img) : 'https://via.placeholder.com/150' }}" 
                             alt="Profile Image">
                        <a href="{{ route('organizer.profile.edit') }}" 
                           class="mt-8 bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Edit Profile
                        </a>                        
                    </div>

                    <!-- Vertical Divider -->
                    <div class="w-px bg-gray-200 mx-8"></div>

                    <!-- Right Column: Organizer Details -->
                    <div class="flex-1">
                        <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg border border-gray-300">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Organizer Details
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    Details and information about the organizer.
                                </p>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $organizer->org_name }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Age</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $organizer->org_age }}
                                        </dd>
                                    </div>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Course</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $organizer->org_course }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Position</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $organizer->org_position }}
                                        </dd>
                                    </div>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $organizer->org_phoneNo }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">About</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $organizer->org_detail ?? 'No details available.' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>

    <x-toast />
</x-organizer-layout>