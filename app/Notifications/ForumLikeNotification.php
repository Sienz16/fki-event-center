<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ForumLikeNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $postId;
    protected $studentId;

    public function __construct($title, $message, $postId, $studentId)
    {
        $this->title = $title;
        $this->message = $message;
        $this->postId = $postId;
        $this->studentId = $studentId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'post_id' => $this->postId,
            'student_id' => $this->studentId,
            'type' => 'forum_like',
            'time' => now()
        ];
    }
} 