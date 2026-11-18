<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $location = 'GondangManis, Kudus';

        // Menu cepat (baris 4 ikon di bawah banner)
        $services = [
            ['icon' => 'alert',  'label' => ['Pengasuh', 'Darurat'],   'url' => '#', 'active' => false],
            ['icon' => 'sun',    'label' => ['Pengasuh Harian'],       'url' => '#', 'active' => false],
            ['icon' => 'users',  'label' => ['Pengasuh', 'Langganan'], 'url' => '#', 'active' => false],
            ['icon' => 'grid',   'label' => ['Semua Layanan'],         'url' => '#', 'active' => true],
        ];

        // Pengasuh terdekat
        $caregivers = [
            ['name' => 'Triana Putri',   'rating' => '4.9', 'reviews' => 87],
            ['name' => 'Putri Dayarsi',  'rating' => '4.7', 'reviews' => 64],
            ['name' => 'Siska Putri',    'rating' => '4.2', 'reviews' => 31],
        ];

        // Navigasi bawah
        $nav = [
            ['icon' => 'clock',  'label' => ['Riwayat'],            'url' => '#'],
            ['icon' => 'news',   'label' => ['Berita'],             'url' => '#'],
            ['icon' => 'baby',   'label' => ['Anak'],               'url' => '#'],
            ['icon' => 'heart',  'label' => ['Anak', 'Kebutuhan'],  'url' => '#'],
            ['icon' => 'grid',   'label' => ['Lainnya'],            'url' => '#'],
        ];

        return view('home', compact('location', 'services', 'caregivers', 'nav'));
    }
}
