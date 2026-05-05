<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function read(Request $request, string $notificationId)
    {
        abort_unless(auth()->check(), 403);

        if (!Schema::hasTable('notifications')) {
            return redirect()->route('dashboard');
        }

        $notification = auth()->user()->notifications()->findOrFail($notificationId);

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url'] ?? route('dashboard'));
    }

    public function readAll(Request $request)
    {
        abort_unless(auth()->check(), 403);

        if (Schema::hasTable('notifications')) {
            auth()->user()->unreadNotifications->markAsRead();
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
