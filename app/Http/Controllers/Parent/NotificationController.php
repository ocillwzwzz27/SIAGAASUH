<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markRead($id)
    {
        $notif = AppNotification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Notifikasi sudah ditandai dibaca.');
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        AppNotification::where('user_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }

    /**
     * Halaman daftar semua notifikasi user.
     */
    public function index()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('parent.notifications.index', compact('notifications'));
    }
}
