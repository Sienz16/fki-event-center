<nav class="bg-[#9d00ff] text-[#FFFFFF] focus:ring-2 focus:ring-[#8A2BE2]" x-data="{ isOpen: false }">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <img class="h-10 w-10" src="{{ asset('images/FKI_Logo.png') }}" alt="FKI Event Center">
        </div>
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <!-- Updated logic to match all pages within each module -->
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
            
            <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Events</a>
            
            <a href="{{ route('admin.venue.index') }}" class="{{ request()->routeIs('admin.venue*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Venue</a>
            
            <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">News</a>
            
            <a href="{{ route('admin.report.index') }}" class="{{ request()->routeIs('admin.report*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} rounded-md px-3 py-2 text-sm font-medium">Analytics</a>
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
                
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span id="notification-badge" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform bg-red-600 rounded-full">
                        {{ auth()->user()->unreadNotifications->count() }}
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
                style="max-height: 90vh; overflow-y-auto;">
                
                <!-- Content wrapper -->
                <div class="relative bg-white rounded-lg">
                    <!-- Header -->
                    <div class="px-4 py-3 bg-gray-50 rounded-t-lg border-b">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                                <p class="text-xs text-gray-600">You have {{ auth()->user()->unreadNotifications->count() }} unread notifications</p>
                            </div>
                            {{-- Temporarily hiding Mark all as read functionality
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <button onclick="markAllAsRead()" 
                                        class="text-xs text-purple-600 hover:text-purple-800">
                                    Mark all as read
                                </button>
                            @endif
                            --}}
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div class="divide-y divide-gray-100">
                        @forelse(auth()->user()->notifications->sortByDesc(function($notification) {
                            return [
                                $notification->read_at === null ? 1 : 0,
                                $notification->created_at
                            ];
                        })->take(5) as $notification)
                            <div class="relative px-4 py-3 hover:bg-gray-50 transition-colors duration-200 cursor-pointer
                                {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-purple-500' }}"
                                onclick="markAsRead('{{ $notification->id }}', this, event)">
                                <!-- Notification Content -->
                                <div class="flex items-start space-x-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $notification->read_at ? 'bg-gray-200' : 'bg-purple-100' }}">
                                            <svg class="h-4 w-4 {{ $notification->read_at ? 'text-gray-500' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </span>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </p>
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400 flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No notifications found</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Footer -->
                    @if(auth()->user()->notifications->count() > 0)
                        <div class="px-4 py-3 bg-gray-50 rounded-b-lg border-t flex justify-between items-center">
                            <a href="{{ route('admin.notifications.index') }}" class="text-xs text-purple-600 hover:text-purple-800 font-medium">
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
                  
                  <!-- Display admin profile image if it exists, else show the default avatar -->
                  @if (Auth::user()->admin->manage_img)
                      <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->admin->manage_img) }}" alt="Profile Image">
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
                x-transition:enter="transition ease-out duration-100 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                <!-- Add Admin Section -->
                <a href="{{ route('admin.admins.create') }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-100" 
                   role="menuitem">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Add Admin
                    </div>
                </a>

                <div class="border-t border-gray-100"></div>

                <!-- Existing Profile Items -->
                <a href="{{ route('admin.profile.index') }}" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-100" 
                    role="menuitem">
                    Your Profile
                </a>
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
      <!-- Match active state for mobile menu items to desktop menu items -->
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Dashboard</a>
      
      <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Events</a>
      
      <a href="{{ route('admin.venue.index') }}" class="{{ request()->routeIs('admin.venue*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Venue</a>
      
      <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">News</a>
      
      <a href="{{ route('admin.report.index') }}" class="{{ request()->routeIs('admin.report*') ? 'bg-[#6e00b3] text-[#ffedfa]' : 'text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]' }} block rounded-md px-3 py-2 text-base font-medium">Analytics</a>
    </div>
    <div class="border-t border-gray-700 pb-3 pt-4">
      <div class="flex items-center px-5">
        <!-- Admin Profile Image -->
        @if (Auth::user()->admin->manage_img)
            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->admin->manage_img) }}" alt="Profile Image">
        @else
            <div class="relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full">
                <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>
        @endif
        
        <!-- Admin Name and Email -->
        <div class="ml-3">
          <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
          <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->email }}</div>
        </div>
      </div>
      <div class="mt-3 space-y-1 px-2">
        <a href="{{ route('admin.profile.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]">Your Profile</a>
        <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]">Settings</a>
        <!-- Logout link with form submission -->
          <a href="#" 
          onclick="event.preventDefault(); 
          document.getElementById('logout-form').submit();" 
          class="block rounded-md px-3 py-2 text-base font-medium text-[#faf5ff] hover:bg-[#7b2cbf] hover:text-[#F0E6FF]">
        Sign out
        </a>

        <!-- Hidden logout form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </div>
  </div>
</nav>

<script>
    function markAsRead(notificationId, element, event) {
        event.preventDefault();
        event.stopPropagation();
        
        fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
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
                icon.classList.add('bg-gray-200');
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