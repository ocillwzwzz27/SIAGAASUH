<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('caregiver.notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notif = AppNotification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Notifikasi sudah ditandai dibaca.');
    }

    public function markAllRead()
    {
        AppNotification::where('user_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }
}
