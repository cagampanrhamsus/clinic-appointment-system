<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon;

class AppLayout extends Component
{
    public $sidebarAppointments;

    /**
     * Create the component instance.
     */
    public function __construct()
    {
        $this->sidebarAppointments = $this->loadAppointments();
    }

    /**
     * Load approved appointments for sidebar
     */
    private function loadAppointments()
    {
        if (!Auth::check()) {
            return collect();
        }

        $user = Auth::user();

        return Appointment::with('patient')
            ->where('status', 'approved')
            ->when($user->role === 'doctor', function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            })
            ->orderBy('appointment_date')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->appointment_date)->format('Y-m-d');
            });
    }

    /**
     * Get the view / contents
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}