<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CaregiverProfile;

class CaregiverController extends Controller
{
    public function index()
    {
        $caregivers = CaregiverProfile::with('user')
            ->where('status_verifikasi', 'terverifikasi')
            ->orderByDesc('rating')
            ->paginate(12);

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
