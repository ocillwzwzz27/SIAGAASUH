<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareSession;

class SessionMonitorController extends Controller
{
    public function index()
    {
        $sessions = CareSession::with(['parent', 'caregiver', 'child', 'latestReading'])
            ->latest()
            ->paginate(15);

        return view('admin.sessions.index', compact('sessions'));
    }

    public function show($id)
    {
        $session = CareSession::with([
            'parent', 'caregiver', 'child',
            'sensorReadings' => fn($q) => $q->latest()->take(50),
        ])->findOrFail($id);

        return view('admin.sessions.show', compact('session'));
    }
}
