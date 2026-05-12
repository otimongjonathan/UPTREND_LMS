<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::guard('customer')->user()
            ->notifications()
            ->paginate(20);

        return view('customer.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::guard('customer')->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        Auth::guard('customer')->user()
            ->unreadNotifications
            ->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}
