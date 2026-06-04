<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\MedicalHistoryController;

use App\Models\User;
use App\Models\Appointment;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {

    $user = Auth::user();

    $doctors = User::where('role', 'doctor')->count();
    $patients = User::where('role', 'patient')->count();

    $appointments = Appointment::count();
    $pending = Appointment::where('status', 'pending')->count();
    $approved = Appointment::where('status', 'approved')->count();
    $rejected = Appointment::where('status', 'rejected')->count();

    return view('dashboard', compact(
        'user',
        'doctors',
        'patients',
        'appointments',
        'pending',
        'approved',
        'rejected'
    ));

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', function () {

        $user = Auth::user();

        $notifications = $user
            ? $user->notifications->sortByDesc('created_at')
            : collect();

        return view('notifications.index', compact('notifications'));

    })->middleware('auth')->name('notifications');

    Route::middleware('role:doctor')->group(function () {

        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');

        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

        Route::post('/appointments/{appointment}/approve', [AppointmentController::class, 'approve'])->name('appointments.approve');
        Route::post('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('appointments.reject');
        Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');

        Route::get('/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::delete('/prescriptions/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');

        Route::get('/medical-history/create', [MedicalHistoryController::class, 'create'])->name('medical-history.create');
        Route::post('/medical-history', [MedicalHistoryController::class, 'store'])->name('medical-history.store');
    });

    Route::middleware('role:patient')->group(function () {

        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    Route::get('/medical-history', [MedicalHistoryController::class, 'index'])->name('medical-history.index');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');

    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');

    Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'downloadPdf'])
        ->name('prescriptions.pdf');
});

require __DIR__.'/auth.php';
