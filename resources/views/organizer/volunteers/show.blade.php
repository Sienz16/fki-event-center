<x-organizer-layout>
    <x-slot:title>
        Volunteers for {{ $volunteer->event->event_name }}
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $volunteer->event->event_name }}</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <!-- Title Section -->
                <div class="mb-6 p-2 sm:p-4">
                    <h2 class="text-2xl font-semibold text-gray-900">Volunteers List</h2>
                    <p class="text-gray-600 mt-1">Manage volunteer requests for this event</p>
                    <hr class="border-t-2 border-gray-200 mt-4">
                </div>

                @if($volunteerRequests->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Volunteers Available</h3>
                        <p class="mt-1 text-sm text-gray-500">There are currently no volunteer requests for this event.</p>
                    </div>
                @else
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-white uppercase bg-purple-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Student Name</th>
                                    <th scope="col" class="px-6 py-3">Matric No</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Phone No</th>
                                    <th scope="col" class="px-6 py-3">Course</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($volunteerRequests as $index => $request)
                                    <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }} hover:bg-purple-50 transition-colors duration-200">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $request->student->stud_name }}</td>
                                        <td class="px-6 py-4">{{ $request->student->user->matric_no }}</td>
                                        <td class="px-6 py-4">{{ $request->student->user->email }}</td>
                                        <td class="px-6 py-4">{{ $request->student->stud_phoneNo }}</td>
                                        <td class="px-6 py-4">{{ $request->student->stud_course }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $request->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                                   ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($request->status === 'pending')
                                                <form action="{{ route('organizer.volunteers.updateStatus', $request->request_id) }}" 
                                                      method="POST" 
                                                      class="space-y-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" 
                                                            name="status" 
                                                            value="accepted" 
                                                            class="w-full bg-green-500 text-white rounded-lg px-3 py-1 border border-green-600 hover:bg-green-600 transition-colors duration-200">
                                                        Accept
                                                    </button>
                                                    <button type="submit" 
                                                            name="status" 
                                                            value="rejected" 
                                                            class="w-full bg-red-500 text-white rounded-lg px-3 py-1 border border-red-600 hover:bg-red-600 transition-colors duration-200">
                                                        Decline
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-500 italic">Responded</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-toast />
</x-organizer-layout>