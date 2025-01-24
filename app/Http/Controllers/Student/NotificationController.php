<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class NotificationController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        // Only get notifications with types related to student
        $notifications = $student->user->notifications()
            ->whereIn('data->type', [
                'event_update',
                'event_reminder',
                'committee_request',
                'event_cancelled',
                'event_suspended'
            ])
            ->paginate(10);
            
        return view('student.notifications.all', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        // Only mark as read if it's a student-related notification
        $notification = $student->user->notifications()
            ->whereIn('data->type', [
                'event_update',
                'event_reminder',
                'committee_request',
                'event_cancelled',
                'event_suspended'
            ])
            ->findOrFail($id);
            
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        // Only mark all read for student-related notifications
        $student->user->notifications()
            ->whereIn('data->type', [
                'event_update',
                'event_reminder',
                'committee_request',
                'event_cancelled'
            ])
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
} 