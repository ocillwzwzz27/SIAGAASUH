<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CaregiverProfile;
use App\Models\Payment;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Jumlah pesanan 6 bulan terakhir (untuk grafik batang)
        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $monthly[] = [
                'label' => $monthNames[$date->month - 1],
                'count' => Booking::whereYear('service_date', $date->year)
                    ->whereMonth('service_date', $date->month)
                    ->count(),
            ];
        }

        return view('admin.dashboard', [
            'stats' => [
                'parents' => User::where('role', 'parent')->count(),
                'caregivers' => CaregiverProfile::verified()->count(),
                'bookings_month' => Booking::whereYear('service_date', now()->year)
                    ->whereMonth('service_date', now()->month)->count(),
                'revenue' => Payment::where('status', 'paid')->sum('amount'),
            ],
            'pendingCount' => CaregiverProfile::where('verification_status', 'pending')->count(),
            'pending' => CaregiverProfile::where('verification_status', 'pending')
                ->with('user')->latest()->limit(5)->get(),
            'recent' => Booking::with(['parent', 'caregiver.user', 'serviceType'])
                ->latest()->limit(6)->get(),
            'statusCounts' => Booking::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->pluck('total', 'status'),
            'monthly' => $monthly,
        ]);
    }

    public function caregivers(Request $request)
    {
        $status = $request->query('status');

        $profiles = CaregiverProfile::with('user')
            ->when($status, fn ($q) => $q->where('verification_status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.caregivers', [
            'profiles' => $profiles,
            'status' => $status,
            'counts' => CaregiverProfile::select('verification_status', DB::raw('count(*) as total'))
                ->groupBy('verification_status')->pluck('total', 'verification_status'),
        ]);
    }

    public function verify(Request $request, CaregiverProfile $profile)
    {
        $data = $request->validate([
            'status' => ['required', 'in:verified,rejected'],
        ]);

        $profile->update([
            'verification_status' => $data['status'],
            'verified_at' => $data['status'] === 'verified' ? now() : null,
        ]);

        $label = $data['status'] === 'verified' ? 'diverifikasi' : 'ditolak';

        return back()->with('success', "Pengasuh {$profile->user->name} berhasil {$label}.");
    }

    public function users(Request $request)
    {
        $role = $request->query('role');
        $q = $request->query('q');

        $users = User::query()
            ->when($role, fn ($query) => $query->where('role', $role))
            ->when($q, fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', compact('users', 'role', 'q'));
    }

    public function bookings(Request $request)
    {
        $status = $request->query('status');

        $bookings = Booking::with(['parent', 'caregiver.user', 'serviceType'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings', compact('bookings', 'status'));
    }

    public function payments()
    {
        return view('admin.payments', [
            'payments' => Payment::with('booking')->latest()->paginate(10),
            'totalPaid' => Payment::where('status', 'paid')->sum('amount'),
            'totalPending' => Payment::where('status', 'pending')->sum('amount'),
        ]);
    }

    public function services()
    {
        $stats = DB::table('caregiver_services')
            ->selectRaw('service_type_id, count(*) as caregivers, avg(price_per_hour) as avg_price')
            ->groupBy('service_type_id')
            ->get()
            ->keyBy('service_type_id');

        return view('admin.services', [
            'services' => ServiceType::all(),
            'stats' => $stats,
        ]);
    }
}
