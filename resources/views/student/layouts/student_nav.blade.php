<!-- resources/views/admin/layouts/student_nav.blade.php -->

<nav class="bg-[#9d00ff] text-[#FFFFFF] focus:ring-2 focus:ring-[#8A2BE2]" x-data="{ isOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10" src="{{ asset('images/FKI_Logo.png') }}" alt="FKI Event Center">
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
                        <a href="{{ route('student.events.index') }}" class="{{ request()->routeIs('student.events.*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Events</a>
                        <a href="{{ route('student.volunteers.index') }}" class="{{ request()->routeIs('student.volunteers.*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Volunteering</a>
                        <a href="{{ route('student.community.index') }}" class="{{ request()->routeIs('student.community.*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Committee</a>
                        <a href="{{ route('student.report.index') }}" class="{{ request()->routeIs('student.report.index') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Analytics</a>                          
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Notification dropdown -->
                    <div class="relative ml-3" x-data="{ isNotificationOpen: false }">
                        <button type="button" @click="isNotificationOpen = !isNotificationOpen"
                            class="relative rounded-full bg-[#9d00ff] p-1 text-gray-400 hover:bg-purple-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                        <span class="sr-only">View notifications</span>
                            
                            @php
                                $unreadCount = auth()->user()->notifications()
                                    ->whereIn('data->type', [
                                        'event_update', 
                                        'event_reminder', 
                                        'committee_request', 
                                        'event_cancelled',
                                        'event_suspended'
                                    ])
                                    ->whereNull('read_at')
                                    ->when(session('selected_role') === 'student', function($query) {
                                        return $query;
                                    }, function($query) {
                                        return $query->where('id', 0);
                                    })
                                    ->count();
                            @endphp
                            
                            @if($unreadCount > 0)
                                <span id="notification-badge" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform bg-red-600 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                            
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#faf5ff" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        </button>

                        <!-- Notification dropdown panel -->
                        <div x-show="isNotificationOpen"
                            @click.away="isNotificationOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 mt-2 w-96 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            style="max-height: 90vh; overflow-y: auto;">
                            
                            <!-- Content wrapper -->
                            <div class="relative bg-white rounded-lg">
                                <!-- Header -->
                                <div class="px-4 py-3 bg-gray-50 rounded-t-lg border-b">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                                            <p class="text-xs text-gray-600">You have {{ $unreadCount }} unread notifications</p>
                                        </div>
                                        {{-- @if($unreadCount > 0)
                                            <form action="{{ route('student.notifications.mark-all-as-read') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs text-purple-600 hover:text-purple-800">
                                                    Mark all as read
                                                </button>
                                            </form>
                                        @endif --}}
                                    </div>
                                </div>

                                <!-- Notifications List -->
                                <div class="divide-y divide-gray-100">
                                    @forelse(auth()->user()->notifications()
                                        ->whereIn('data->type', [
                                            'event_update',
                                            'event_reminder',
                                            'committee_request',
                                            'event_cancelled',
                                            'event_suspended'
                                        ])
                                        ->when(session('selected_role') === 'student', function($query) {
                                            return $query;
                                        }, function($query) {
                                            return $query->where('id', 0); // Return no results for non-student roles
                                        })
                                        ->orderByRaw('CASE WHEN read_at IS NULL THEN 0 ELSE 1 END')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get() as $notification)
                                        <div class="relative px-4 py-3 hover:bg-gray-50 transition-colors duration-200 cursor-pointer
                                            {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-purple-500' }}"
                                            onclick="markAsRead('{{ $notification->id }}', this, event)">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full 
                                                        {{ $notification->read_at ? 'bg-gray-100' : 'bg-purple-100' }}">
                                                        @switch($notification->data['type'] ?? '')
                                                            @case('event_update')
                                                                <svg class="h-5 w-5 {{ $notification->read_at ? 'text-gray-500' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                                @break
                                                            @case('event_reminder')
                                                                <svg class="h-5 w-5 {{ $notification->read_at ? 'text-gray-500' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                @break
                                                            @case('committee_request')
                                                                <svg class="h-5 w-5 {{ $notification->read_at ? 'text-gray-500' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                </svg>
                                                                @break
                                                            @case('event_cancelled')
                                                                <svg class="h-5 w-5 {{ $notification->read_at ? 'text-gray-500' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                @break
                                                            @case('event_suspended')
                                                                <svg class="h-5 w-5 {{ $notification->read_at ? 'text-gray-500' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                                @break
                                                            @default
                                                                <svg class="h-5 w-5 {{ $notification->read_at ? 'text-gray-500' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                                </svg>
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $notification->data['title'] ?? 'Notification' }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $notification->data['message'] ?? '' }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500">
                                            No notifications yet
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Footer -->
                                @if(auth()->user()->notifications()
                                    ->whereIn('data->type', ['event_update', 'event_reminder', 'committee_request', 'event_cancelled'])
                                    ->count() > 0)
                                    <div class="px-4 py-3 bg-gray-50 text-right border-t">
                                        <a href="{{ route('student.notifications.index') }}" class="text-sm text-purple-600 hover:text-purple-800">
                                            View all notifications
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
  
                    <!-- Profile dropdown -->
                    <div class="relative ml-3" @click.away="isOpen = false">
                      <div>
                          <button type="button" @click="isOpen = !isOpen"
                              class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                              <span class="absolute -inset-1.5"></span>
                              <span class="sr-only">Open user menu</span>
                              
                              <!-- Display stud_img if it exists, else show the default avatar -->
                              @if (Auth::user()->student->stud_img)
                                  <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->student->stud_img) }}" alt="Profile Image">
                              @else
                                  <div class="relative w-8 h-8 overflow-hidden bg-gray-100 rounded-full">
                                      <svg class="absolute w-10 h-10 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                      </svg>
                                  </div>
                              @endif
                          </button>
                      </div>
  
                      <div x-show="isOpen"
                          x-cloak
                          x-transition:enter="transition ease-out duration-100 transform"
                          x-transition:enter-start="opacity-0 scale-95"
                          x-transition:enter-end="opacity-100 scale-100"
                          x-transition:leave="transition ease-in duration-75 transform"
                          x-transition:leave-start="opacity-100 scale-100"
                          x-transition:leave-end="opacity-0 scale-95"
                          class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                          <a href="{{ route('student.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
                          {{-- <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a> --}}
                          <a href="#" 
                              onclick="event.preventDefault(); 
                              document.getElementById('logout-form').submit();" 
                              class="block px-4 py-2 text-sm text-gray-700" 
                              role="menuitem" 
                              tabindex="-1" 
                              id="user-menu-item-2">
                              Sign out
                          </a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                          </form>
                      </div>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex md:hidden">
                <button type="button" @click="isOpen = !isOpen"
                class="relative inline-flex items-center justify-center rounded-md bg-[#9d00ff] p-2 text-gray-400 hover:bg-[#7b2cbf] hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-purple-800" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <svg :class="{'hidden': isOpen, 'block': !isOpen }" 
                    class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg :class="{'block': isOpen, 'hidden': !isOpen }" 
                    class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
  
    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="isOpen" class="md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Dashboard</a>
            <a href="{{ route('student.events.index') }}" class="{{ request()->routeIs('student.events.index') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Events</a>
            <a href="{{ route('student.volunteers.index') }}" class="{{ request()->routeIs('student.volunteers.*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Volunteering</a>
            <a href="{{ route('student.community.index') }}" class="{{ request()->routeIs('student.community.*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Committee</a>
            <a href="{{ route('student.report.index') }}" class="{{ request()->routeIs('student.report.index') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Analytics</a>                          
        </div>
        <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . Auth::user()->student->stud_img ?? 'https://via.placeholder.com/150') }}" alt="Student Avatar">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-white">{{ Auth::user()->student->stud_name }}</div>
                    <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                <button type="button" class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                    <span class="absolute -inset-1.5"></span>
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </button>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('student.profile.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]">Your Profile</a>
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]">Settings</a>
                <a href="#" 
                   onclick="event.preventDefault(); 
                   document.getElementById('logout-form').submit();" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]">
                   Sign out
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    function markAsRead(notificationId, element, event) {
        event.preventDefault();
        event.stopPropagation();
        
        fetch(`/student/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ _token: '{{ csrf_token() }}' })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update notification appearance
            element.classList.add('opacity-75');
            element.classList.remove('border-l-4', 'border-purple-500');
            
            // Update icon color
            const icon = element.querySelector('span');
            const svg = element.querySelector('svg');
            if (icon) {
                icon.classList.remove('bg-purple-100');
                icon.classList.add('bg-gray-100');
            }
            if (svg) {
                svg.classList.remove('text-purple-600');
                svg.classList.add('text-gray-500');
            }

            // Update unread count
            let headerCount = document.querySelector('.text-xs.text-gray-600');
            let bellBadge = document.getElementById('notification-badge');
            let unreadCount = parseInt(bellBadge ? bellBadge.textContent : 0) - 1;
            
            if (unreadCount <= 0) {
                if (bellBadge) bellBadge.remove();
            } else {
                if (bellBadge) bellBadge.textContent = unreadCount;
            }
            
            // Update header notification count text
            if (headerCount) {
                headerCount.textContent = `You have ${unreadCount} unread notifications`;
            }

            // Move read notification to bottom
            const notificationsList = element.closest('.divide-y');
            const notifications = Array.from(notificationsList.children);
            notifications.sort((a, b) => {
                const aIsUnread = !a.classList.contains('opacity-75');
                const bIsUnread = !b.classList.contains('opacity-75');
                return bIsUnread - aIsUnread;
            });
            notifications.forEach(notification => notificationsList.appendChild(notification));
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
