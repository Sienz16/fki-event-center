@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @forelse($notifications as $notification)
            <div class="p-4 border-b {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold">{{ $notification->data['title'] }}</h3>
                        <p class="text-gray-600">{{ $notification->data['message'] }}</p>
                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    @if(!$notification->read_at)
                        <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                Mark as read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500">
                No notifications found.
            </div>
        @endforelse

        @if($notifications->isNotEmpty())
            <div class="p-4 border-t">
                <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                        Mark all as read
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection 