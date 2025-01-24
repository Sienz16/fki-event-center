<div>
    <div class="mb-6 p-2 sm:p-4">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900">Participants Lists</h2>
                <p class="text-gray-600 mt-2">Total Participants: {{ count($participants) }}</p>
            </div>
            <button wire:click="exportToExcel" 
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export to Excel
            </button>
        </div>
        <hr class="border-t-2 border-gray-300 mt-2">
    </div>

    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <input type="text" 
                   wire:model.live="search"
                   placeholder="Search by name, matric no, or email..." 
                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
            @if($search)
                <button wire:click="$set('search', '')" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
        <select wire:model.live="statusFilter" 
                class="w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
            <option value="">All Status</option>
            <option value="Attended">Attended</option>
            <option value="Registered">Registered</option>
        </select>
    </div>

    <!-- Table section remains the same but uses $participants from the component -->
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
                    <th scope="col" class="px-6 py-3">Register Date</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Attendance Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $index => $participant)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-purple-50 transition-colors">
                        <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $participant->stud_name }}</td>
                        <td class="px-6 py-4">{{ $participant->matric_no }}</td>
                        <td class="px-6 py-4">
                            <a href="mailto:{{ $participant->email }}" class="text-purple-600 hover:text-purple-900">
                                {{ $participant->email }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $participant->stud_phoneNo }}</td>
                        <td class="px-6 py-4">{{ $participant->stud_course }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($participant->register_datetime)->format('M d, Y, g:i a') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if(strtolower($participant->status) === 'attended')
                                    bg-emerald-100 text-emerald-800
                                @elseif(strtolower($participant->status) === 'registered')
                                    bg-purple-100 text-purple-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif">
                                {{ $participant->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($participant->attendance_datetime)
                                {{ \Carbon\Carbon::parse($participant->attendance_datetime)->format('M d, Y, g:i a') }}
                            @else
                                <span class="text-gray-400 italic">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No participants</h3>
                                <p class="mt-1 text-sm text-gray-500">No participants have registered for this event yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Empty state remains the same -->
</div> 