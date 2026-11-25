<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\CareSession;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $query = CareSession::with(['parent', 'child'])
            ->where('caregiver_id', auth()->id());

        if ($request->filled('status') && in_array($request->status, ['menunggu', 'berjalan', 'selesai', 'ditolak', 'dibatalkan'])) {
            $query->where('status', $request->status);
        }

        $sessions = $query->latest()->paginate(10)->withQueryString();

        return view('caregiver.sessions.index', compact('sessions'));
    }

    public function confirm(Request $request, $id)
    {
        $session = CareSession::with('child')
            ->where('caregiver_id', auth()->id())
            ->where('status', 'menunggu')
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:terima,tolak',
        ]);

        if ($request->action === 'terima') {
            $profile = auth()->user()->caregiverProfile;
            if (!$profile || $profile->status_verifikasi !== 'terverifikasi') {
                return back()->with('error', 'Akun Anda belum terverifikasi admin sehingga belum dapat menerima sesi.');
            }

            $adaSesiBerjalan = CareSession::where('caregiver_id', auth()->id())
                ->where('status', 'berjalan')
                ->exists();

            if ($adaSesiBerjalan) {
                return back()->with('error', 'Anda masih memiliki sesi yang sedang berjalan. Selesaikan sesi aktif terlebih dahulu.');
            }

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

            return back()->with('success', 'Sesi pengasuhan diterima dan resmi berjalan.');
        }

        $session->update(['status' => 'ditolak']);

        AppNotification::kirim(
            $session->parent_id,
            '❌ Permintaan Sesi Ditolak',
            auth()->user()->name . ' tidak dapat menerima permintaan pengasuhan saat ini.',
            'layanan',
            $session->id
        );

        return back()->with('success', 'Permintaan sesi telah ditolak.');
    }

    public function end(Request $request, $id)
    {
        $session = CareSession::with('child')
            ->where('caregiver_id', auth()->id())
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
