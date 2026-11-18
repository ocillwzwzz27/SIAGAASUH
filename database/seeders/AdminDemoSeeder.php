<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\CaregiverProfile;
use App\Models\Payment;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh agar dashboard admin terlihat "hidup".
 * Jalankan SETELAH MasterDataSeeder dan DemoSeeder.
 */
class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Dua pengasuh yang menunggu verifikasi
        foreach ([['Rina Maharani', 'rina@siagaasuh.test', 2], ['Dewi Lestari', 'dewi@siagaasuh.test', 4]] as [$name, $email, $years]) {
            $user = User::updateOrCreate(['email' => $email], [
                'name' => $name,
                'password' => 'password',
                'role' => 'caregiver',
                'city' => 'Kudus',
                'latitude' => -6.81,
                'longitude' => 110.84,
            ]);

            CaregiverProfile::updateOrCreate(['user_id' => $user->id], [
                'bio' => 'Mengajukan diri sebagai pengasuh.',
                'experience_years' => $years,
                'verification_status' => 'pending',
            ]);
        }

        if (Booking::count() > 0) {
            return; // jangan gandakan pesanan kalau seeder dijalankan ulang
        }

        $parent = User::where('email', 'ortu@siagaasuh.test')->first();
        $caregivers = CaregiverProfile::verified()->get();
        $services = ServiceType::all();

        if (! $parent || $caregivers->isEmpty() || $services->isEmpty()) {
            return;
        }

        $statuses = ['completed', 'completed', 'ongoing', 'accepted', 'pending', 'completed',
                     'cancelled', 'completed', 'rejected', 'completed', 'pending', 'accepted'];

        foreach ($statuses as $i => $status) {
            $caregiver = $caregivers[$i % $caregivers->count()];
            $service = $services[$i % $services->count()];
            $price = (int) ($caregiver->services()->first()?->pivot->price_per_hour ?? 35000);
            $date = now()->subDays($i * 12);

            $booking = Booking::create([
                'parent_id' => $parent->id,
                'caregiver_profile_id' => $caregiver->id,
                'service_type_id' => $service->id,
                'service_date' => $date->toDateString(),
                'start_time' => '08:00',
                'end_time' => '12:00',
                'address' => 'Gondangmanis, Kudus',
                'total_price' => $price * 4,
                'status' => $status,
            ]);

            $paymentStatus = match ($status) {
                'completed', 'ongoing' => 'paid',
                'accepted' => 'pending',
                'cancelled' => 'refunded',
                default => null,
            };

            if ($paymentStatus) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'method' => ['QRIS', 'Transfer Bank', 'E-Wallet'][$i % 3],
                    'amount' => $booking->total_price,
                    'status' => $paymentStatus,
                    'transaction_id' => 'TRX-' . strtoupper(substr(md5((string) $booking->id), 0, 8)),
                    'paid_at' => $paymentStatus === 'paid' ? $date : null,
                ]);
            }
        }
    }
}
