<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class NotificationController extends Controller
{
    public function index()
    {
        $admin = Admin::where('user_id', Auth::id())->firstOrFail();
        $notifications = $admin->user->notifications()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.notifications.all', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $admin = Admin::where('user_id', Auth::id())->firstOrFail();
        $admin->user->notifications()->findOrFail($id)->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $admin = Admin::where('user_id', Auth::id())->firstOrFail();
        $admin->user->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }
} 