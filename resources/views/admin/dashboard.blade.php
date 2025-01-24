<x-admin-layout>
    <x-slot:title>
        Admin Dashboard
    </x-slot>

    <x-slot:header>
        Dashboard
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-white py-16 sm:py-20 shadow-lg sm:rounded-lg">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Latest News !</h2>
                    <p class="mt-1 text-lg leading-8 text-gray-600">Learn what's new in the system.</p>
                </div>
                <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @foreach($news as $item)
                        <article class="flex max-w-xl flex-col items-start justify-between">
                          <div class="flex items-center gap-x-4 text-xs">
                            <time datetime="{{ $item->date->format('Y-m-d') }}" class="text-gray-500">{{ $item->date->format('M d, Y') }}</time>
                            
                            @php
                                $tagClasses = '';
                                switch ($item->news_tag) {
                                    case 'Update':
                                        $tagClasses = 'bg-green-50 text-green-600 hover:bg-green-100';
                                        break;
                                    case 'Maintenance':
                                        $tagClasses = 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100';
                                        break;
                                    case 'Bugs':
                                        $tagClasses = 'bg-red-50 text-red-600 hover:bg-red-100';
                                        break;
                                }
                            @endphp
                            
                            <a href="#" class="relative z-10 rounded-full px-3 py-1.5 font-medium {{ $tagClasses }}">
                                {{ $item->news_tag }}
                            </a>
                          </div>
                            <div class="group relative">
                                <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                                    <a href="#">
                                        <span class="absolute inset-0"></span>
                                        {{ $item->news_title }}
                                    </a>
                                </h3>
                                <p class="mt-5 text-sm leading-6 text-gray-600">{!! nl2br(e($item->news_details)) !!}</p>
                            </div>
                            <div class="relative mt-8 flex items-center gap-x-4">
                              <img src="{{ $item->admin->manage_img ? asset('storage/' . $item->admin->manage_img) : 'https://via.placeholder.com/150' }}" alt="Profile Image" class="h-10 w-10 rounded-full bg-gray-50">
                              <div class="text-sm leading-6">
                                  <p class="font-semibold text-gray-900">
                                      <a href="#">
                                          <span class="absolute inset-0"></span>
                                          {{ $item->admin->manage_name }}
                                      </a>
                                  </p>
                                  <p class="text-gray-600">{{ $item->admin->manage_position }}</p>
                                  <p class="text-gray-500">Posted {{ $item->date->diffForHumans() }}</p>
                              </div>
                          </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>  
</x-admin-layout>