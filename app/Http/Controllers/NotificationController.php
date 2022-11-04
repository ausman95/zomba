<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        activity('Notifications')
            ->log("Accessed notifications")->causer(request()->user());
        $notifications = \request()->user()->notifications()->get();

        return view('notifications.index')->with([
            'notifications' => $notifications,
            'cpage' => "notifications"
        ]);
    }


    public function unreadNotifications()
    {
        $notifications = \request()->user()->unreadNotifications()->get();

        return view('notifications.unread')->with([
            'notifications' => $notifications,
            'cpage' => "notifications"
        ]);
    }


    public function readNotification($notification_id)
    {

        $notification = \request()->user()->notifications()->where('id', $notification_id)->firstOrFail();
        // mark notification as read
        $notification->markAsRead();

        return view('notifications.read')->with([
            'notification' => $notification,
            'cpage' => "notifications"
        ]);
    }


    public function markAllAsRead()
    {
        \request()->user()->unreadNotifications->markAsRead();


        return back()->with(['success-notification' => "All notifications marked as read."]);
    }
}
