<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\CareSession;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request, $id)
    {
        $session = CareSession::with(['parent', 'caregiver.caregiverProfile', 'child'])
            ->where('parent_id', auth()->id())
            ->findOrFail($id);

        if (in_array($session->status, ['ditolak', 'dibatalkan'])) {
            return back()->with('error', 'Sesi yang dibatalkan atau ditolak tidak dapat dibayar.');
        }

        if ($session->status_pembayaran === 'lunas') {
            return back()->with('success', 'Pembayaran sesi ini sudah berstatus LUNAS.');
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:qris,transfer_bca,transfer_bri,ewallet',
        ]);

        if (!$session->total_biaya || !$session->order_id) {
            $profile = $session->caregiver?->caregiverProfile;
            $amount = match ($session->jenis_layanan) {
                'darurat' => (float) ($profile?->tarif_darurat ?? 200000),
                'malam' => (float) ($profile?->tarif_malam ?? 180000),
                default => (float) ($profile?->tarif_harian ?? 150000),
            };

            $session->update([
                'order_id' => $session->order_id ?? ('SGA-' . $session->id . '-' . time()),
                'total_biaya' => $session->total_biaya ?? $amount,
            ]);
        }

        $session->markAsPaid($request->metode_pembayaran);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS! Notifikasi telah dikirim ke pengasuh dan admin.');
    }
}
