<?php

namespace Database\Seeders;

use App\Models\CaregiverProfile;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'ortu@siagaasuh.test'], [
            'name' => 'Orang Tua Demo',
            'password' => 'password',
            'role' => 'parent',
            'village' => 'Gondangmanis',
            'city' => 'Kudus',
            'latitude' => -6.8048,
            'longitude' => 110.8405,
        ]);

        $caregivers = [
            ['Triana Putri',  'triana@siagaasuh.test', 4.9, 120, 3, -6.8100, 110.8450, 40000],
            ['Putri Dayarsi', 'putri@siagaasuh.test',  4.7, 80,  2, -6.8000, 110.8300, 35000],
            ['Siska Putri',   'siska@siagaasuh.test',  4.2, 45,  1, -6.8250, 110.8600, 30000],
        ];

        foreach ($caregivers as [$name, $email, $rating, $reviews, $years, $lat, $lng, $price]) {
            $user = User::updateOrCreate(['email' => $email], [
                'name' => $name,
                'password' => 'password',
                'role' => 'caregiver',
                'city' => 'Kudus',
                'latitude' => $lat,
                'longitude' => $lng,
            ]);

            $profile = CaregiverProfile::updateOrCreate(['user_id' => $user->id], [
                'bio' => 'Pengasuh berpengalaman, sabar, dan terpercaya.',
                'experience_years' => $years,
                'verification_status' => 'verified',
                'verified_at' => now(),
                'rating_avg' => $rating,
                'review_count' => $reviews,
                'is_available' => true,
            ]);

            $sync = [];
            foreach (ServiceType::all() as $service) {
                $sync[$service->id] = ['price_per_hour' => $price];
            }
            $profile->services()->sync($sync);
        }
    }
}
