<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Parent as ParentCtrl;
use App\Http\Controllers\Caregiver;

// ===================== AUTH ROUTES =====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'orang_tua' => redirect()->route('parent.dashboard'),
            'pengasuh' => redirect()->route('caregiver.dashboard'),
        };
    }
    return redirect()->route('login');
});

// ===================== ADMIN ROUTES =====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Caregiver Verification
    Route::get('/caregiver', [Admin\CaregiverVerificationController::class, 'index'])->name('caregiver.index');
    Route::get('/caregiver/{id}', [Admin\CaregiverVerificationController::class, 'show'])->name('caregiver.show');
    Route::post('/caregiver/{id}/verify', [Admin\CaregiverVerificationController::class, 'verify'])->name('caregiver.verify');

    // Session Monitor
    Route::get('/sessions', [Admin\SessionMonitorController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/{id}', [Admin\SessionMonitorController::class, 'show'])->name('sessions.show');
});

// ===================== ORANG TUA ROUTES =====================
Route::middleware(['auth', 'role:orang_tua'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentCtrl\DashboardController::class, 'index'])->name('dashboard');

    // Children
    Route::resource('children', ParentCtrl\ChildController::class)->except(['show']);
    Route::post('/children/{childId}/device', [ParentCtrl\ChildController::class, 'addDevice'])->name('children.addDevice');

    // Caregivers
    Route::get('/caregivers', [ParentCtrl\CaregiverController::class, 'index'])->name('caregivers.index');
    Route::get('/caregivers/{id}', [ParentCtrl\CaregiverController::class, 'show'])->name('caregivers.show');

    // Sessions
    Route::get('/sessions', [ParentCtrl\SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create', [ParentCtrl\SessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [ParentCtrl\SessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/{id}', [ParentCtrl\SessionController::class, 'show'])->name('sessions.show');
    Route::post('/sessions/{id}/cancel', [ParentCtrl\SessionController::class, 'cancel'])->name('sessions.cancel');
    Route::get('/sessions/{id}/monitor', [ParentCtrl\SessionController::class, 'monitor'])->name('sessions.monitor');
    Route::get('/sessions/{id}/sensor', [ParentCtrl\SessionController::class, 'latestSensor'])->name('sessions.sensor');

    // Notifications
    Route::get('/notifications', [ParentCtrl\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [ParentCtrl\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [ParentCtrl\NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

// ===================== PENGASUH ROUTES =====================
Route::middleware(['auth', 'role:pengasuh'])->prefix('caregiver')->name('caregiver.')->group(function () {
    Route::get('/dashboard', [Caregiver\DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [Caregiver\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Caregiver\ProfileController::class, 'update'])->name('profile.update');

    // Sessions
    Route::get('/sessions', [Caregiver\SessionController::class, 'index'])->name('sessions.index');
    Route::post('/sessions/{id}/confirm', [Caregiver\SessionController::class, 'confirm'])->name('sessions.confirm');
    Route::post('/sessions/{id}/end', [Caregiver\SessionController::class, 'end'])->name('sessions.end');
});
