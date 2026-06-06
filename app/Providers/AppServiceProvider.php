<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    View::composer('*', function ($view) {

        try {

            $user = \Illuminate\Support\Facades\Auth::user();

            if (!$user) {
                $view->with('sidebarAppointments', collect());
                return;
            }

            $appointments = Appointment::with('patient')
                ->where('status', 'approved')
                ->when($user->role === 'doctor', function ($q) use ($user) {
                    $q->where('doctor_id', $user->id);
                })
                ->orderBy('appointment_date')
                ->get()
                ->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->appointment_date)->format('Y-m-d');
                });

            $view->with('sidebarAppointments', $appointments);

        } catch (\Throwable $e) {
            $view->with('sidebarAppointments', collect());
        }
    });
}
}