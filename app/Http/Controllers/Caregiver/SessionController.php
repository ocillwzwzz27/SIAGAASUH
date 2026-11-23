<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\CareSession;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = CareSession::with(['parent', 'child'])
            ->where('caregiver_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('caregiver.sessions.index', compact('sessions'));
    }

    public function confirm(Request $request, $id)
    {
        $session = CareSession::where('caregiver_id', auth()->id())
            ->where('status', 'menunggu')
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:terima,tolak',
        ]);

        if ($request->action === 'terima') {
            $session->update([
                'status' => 'berjalan',
                'started_at' => now(),
            ]);

            AppNotification::kirim(
                $session->parent_id,
                '✅ Sesi Pengasuhan Dikonfirmasi!',
                auth()->user()->name . ' telah menerima permintaan Anda. Sesi dimulai sekarang.',
                'layanan',
                $session->id
            );
        } else {
            $session->update(['status' => 'ditolak']);

            AppNotification::kirim(
                $session->parent_id,
                '❌ Permintaan Sesi Ditolak',
                auth()->user()->name . ' tidak dapat menerima permintaan pengasuhan saat ini.',
                'layanan',
                $session->id
            );
        }

        return back()->with('success', 'Status sesi berhasil diperbarui.');
    }

    public function end(Request $request, $id)
    {
        $session = CareSession::where('caregiver_id', auth()->id())
            ->where('status', 'berjalan')
            ->findOrFail($id);

        $request->validate([
            'ringkasan' => 'required|string|min:20',
        ]);

        $session->update([
            'status' => 'selesai',
            'ended_at' => now(),
            'ringkasan' => $request->ringkasan,
        ]);

        AppNotification::kirim(
            $session->parent_id,
            '🏁 Sesi Pengasuhan Selesai',
            'Sesi pengasuhan untuk ' . $session->child->nama . ' telah selesai. Ringkasan: ' . substr($request->ringkasan, 0, 100) . '...',
            'layanan',
            $session->id
        );

        return redirect()->route('caregiver.sessions.index')
            ->with('success', 'Sesi berhasil diakhiri. Terima kasih!');
    }
}
