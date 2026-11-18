<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    private const TABS = ['semua', 'layanan', 'jadwal', 'sistem'];

    public function index(Request $request)
    {
        $user = $request->user();
        $tab = in_array($request->query('tab'), self::TABS, true) ? $request->query('tab') : 'semua';

        $feed = $this->buildFeed($request)
            ->when($tab !== 'semua', fn ($c) => $c->where('type', $tab))
            ->sortByDesc('at')
            ->values();

        $now = Carbon::now('Asia/Jakarta');

        $groups = $feed
            ->map(function (array $n) use ($now) {
                $at = $n['at']->copy()->timezone('Asia/Jakarta');
                [$group, $time] = match (true) {
                    $at->isSameDay($now) => ['Hari Ini', $at->format('H.i')],
                    $at->isSameDay($now->copy()->subDay()) => ['Kemarin', 'Kemarin ' . $at->format('H.i')],
                    default => ['Sebelumnya', $at->format('d/m/Y')],
                };

                return $n + ['group' => $group, 'time' => $time];
            })
            ->groupBy('group');

        return view('notifications', ['tab' => $tab, 'groups' => $groups]);
    }

    /**
     * Notifikasi dibentuk dari data yang sudah ada (pesanan, pembayaran, ulasan)
     * karena belum ada tabel notifikasi khusus.
     */
    private function buildFeed(Request $request)
    {
        $user = $request->user();
        $isCaregiver = $user->isCaregiver();

        $bookings = Booking::with(['parent', 'caregiver.user', 'serviceType', 'payment', 'review'])
            ->when(
                $isCaregiver,
                fn ($q) => $q->where('caregiver_profile_id', $user->caregiverProfile?->id),
                fn ($q) => $q->where('parent_id', $user->id)
            )
            ->latest('updated_at')
            ->limit(30)
            ->get();

        $items = collect();

        foreach ($bookings as $b) {
            $name = e($isCaregiver ? $b->parent?->name : $b->caregiver?->user?->name);
            $when = $b->service_date->format('d/m/Y') . ', pukul ' . str_replace(':', '.', substr($b->start_time, 0, 5)) . ' WIB';

            $items->push(match ($b->status) {
                'pending' => $isCaregiver
                    ? $this->item('layanan', 'clipboard-list', 'Permintaan Layanan', "Ada permintaan layanan baru dari {$name}.", $b->updated_at, true)
                    : $this->item('layanan', 'clipboard-list', 'Menunggu Konfirmasi', "Permintaan layanan ke {$name} sedang menunggu konfirmasi.", $b->updated_at, true),
                'accepted', 'ongoing' => $this->item('jadwal', 'calendar', 'Jadwal Pengasuhan', "Pengasuhan bersama {$name} pada {$when}.", $b->updated_at, true),
                'completed' => $this->item('layanan', 'circle-check', 'Layanan Selesai', "Sesi pengasuhan dengan {$name} telah selesai.", $b->updated_at, false),
                'rejected' => $this->item('layanan', 'info', 'Permintaan Ditolak', "Permintaan layanan dengan {$name} tidak dapat diproses.", $b->updated_at, false),
                default => $this->item('layanan', 'info', 'Layanan Dibatalkan', "Layanan dengan {$name} telah dibatalkan.", $b->updated_at, false),
            });

            if ($b->payment?->status === 'paid') {
                $service = e($b->serviceType?->name ?? 'pengasuhan');
                $items->push($this->item('layanan', 'calendar-check', 'Pembayaran Berhasil', "Pembayaran layanan {$service} berhasil diterima.", $b->payment->paid_at ?? $b->payment->updated_at, false));
            }

            if ($isCaregiver && $b->review) {
                $items->push($this->item(
                    'layanan', 'star', 'Ulasan Diterima',
                    e($b->parent?->name) . ' memberikan ulasan.<br><span class="text-amber-500">★</span> ' . number_format($b->review->rating, 1) . ' untuk layanan Anda.',
                    $b->review->created_at, false
                ));
            }
        }

        $items->push($this->item('sistem', 'info', 'Selamat Datang', 'Akun kamu sudah aktif. Mulai cari pengasuh terpercaya di sekitarmu.', $user->created_at ?? now(), false));

        return $items;
    }

    private function item(string $type, string $icon, string $title, string $body, $at, bool $unread): array
    {
        return compact('type', 'icon', 'title', 'body', 'at', 'unread');
    }
}
