@php
    $map = [
        'pending'   => ['Menunggu',      'bg-yellow-100 text-yellow-800'],
        'accepted'  => ['Diterima',      'bg-blue-100 text-blue-800'],
        'rejected'  => ['Ditolak',       'bg-red-100 text-red-800'],
        'ongoing'   => ['Berjalan',      'bg-indigo-100 text-indigo-800'],
        'completed' => ['Selesai',       'bg-green-100 text-green-800'],
        'cancelled' => ['Dibatalkan',    'bg-gray-200 text-gray-700'],
        'verified'  => ['Terverifikasi', 'bg-green-100 text-green-800'],
        'paid'      => ['Lunas',         'bg-green-100 text-green-800'],
        'failed'    => ['Gagal',         'bg-red-100 text-red-800'],
        'refunded'  => ['Dikembalikan',  'bg-gray-200 text-gray-700'],
        'parent'    => ['Orang Tua',     'bg-purple-100 text-purple-800'],
        'caregiver' => ['Pengasuh',      'bg-pink-100 text-pink-800'],
        'admin'     => ['Admin',         'bg-gray-800 text-white'],
    ];
    [$label, $classes] = $map[$status] ?? [$status, 'bg-gray-100 text-gray-700'];
@endphp
<span class="inline-block whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">{{ $label }}</span>
