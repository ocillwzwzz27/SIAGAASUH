<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\CaregiverProfile;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function index(Request $request)
    {
        $query = CaregiverProfile::with('user')
            ->where('status_verifikasi', 'terverifikasi');

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('kompetensi', 'like', "%{$keyword}%")
                    ->orWhere('pengalaman', 'like', "%{$keyword}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$keyword}%"));
            });
        }

        if ($request->filled('layanan')) {
            match ($request->layanan) {
                'harian' => $query->whereNotNull('tarif_harian')->orderBy('tarif_harian'),
                'darurat' => $query->whereNotNull('tarif_darurat')->orderBy('tarif_darurat'),
                'malam' => $query->whereNotNull('tarif_malam')->orderBy('tarif_malam'),
                default => null,
            };
        }

        $caregivers = $query->orderByDesc('rating')
            ->paginate(12)
            ->withQueryString();

        return view('parent.caregivers.index', compact('caregivers'));
    }

    public function show($id)
    {
        $profile = CaregiverProfile::with('user')->findOrFail($id);

        if ($profile->status_verifikasi !== 'terverifikasi') {
            abort(404);
        }

        return view('parent.caregivers.show', compact('profile'));
    }
}
