<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organizer;

class NotificationController extends Controller
{
    public function index()
    {
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
        $notifications = $organizer->user->notifications()
            ->whereNotIn('data->type', [
                'event_update',
                'event_reminder',
                'committee_request',
                'event_cancelled'
            ])
            ->orderByRaw('CASE WHEN read_at IS NULL THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('organizer.notifications.all', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
        $organizer->user->notifications()->findOrFail($id)->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
        $organizer->user->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }
} 