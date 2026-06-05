<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'doctor') {

            $appointments = Appointment::with(['doctor', 'patient'])
                ->where('doctor_id', $user->id)
                ->latest()
                ->get();

        } elseif ($user->role === 'patient') {

            $appointments = Appointment::with(['doctor', 'patient'])
                ->where('patient_id', $user->id)
                ->latest()
                ->get();

        } else {

            $appointments = Appointment::with(['doctor', 'patient'])
                ->latest()
                ->get();
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();

        return view('appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'symptoms' => 'required|string',
        ]);

        $appointmentDate = Carbon::parse($request->appointment_date);

        // 🚫 Block weekends
        if ($appointmentDate->isWeekend()) {
            return back()->withErrors([
                'appointment_date' => 'Weekends are not allowed.'
            ])->withInput();
        }

        // ⏰ FIXED 2-hour slots (8AM - 5PM)
        // Slots represent START time of 2-hour windows
        $allowedTimes = [
            '08:00', // 08–10
            '10:00', // 10–12
            '13:00', // 13–15
            '15:00', // 15–17
        ];

        if (!in_array($request->appointment_time, $allowedTimes)) {
            return back()->withErrors([
                'appointment_time' => 'Invalid time slot selected.'
            ])->withInput();
        }

        // 🧑‍⚕️ Max 4 patients per doctor per day
        $dailyCount = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($dailyCount >= 4) {
            return back()->withErrors([
                'appointment_date' => 'Doctor is fully booked for this day.'
            ])->withInput();
        }

        // 🚫 Prevent duplicate slot booking
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'appointment_time' => 'This time slot is already booked.'
            ])->withInput();
        }

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => Auth::id(),
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'symptoms' => $request->symptoms,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment booked successfully!');
    }

    public function approve(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        $appointment->update(['status' => 'approved']);

        $this->notifyPatient($appointment, 'Your appointment has been APPROVED.');

        return back();
    }

    public function reject(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        $appointment->update(['status' => 'rejected']);

        $this->notifyPatient($appointment, 'Your appointment has been REJECTED.');

        return back();
    }

    public function complete(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        $appointment->update(['status' => 'completed']);

        $this->notifyPatient($appointment, 'Your appointment has been COMPLETED.');

        return back();
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index');
    }

    private function authorizeDoctor(Appointment $appointment)
    {
        if (Auth::user()->role !== 'doctor') {
            abort(403);
        }

        if ($appointment->doctor_id !== Auth::id()) {
            abort(403);
        }
    }

    private function notifyPatient(Appointment $appointment, $message)
    {
        $patient = User::find($appointment->patient_id);

        if ($patient) {
            $patient->notify(new AppointmentNotification($message));
        }
    }

    // =====================================================
    // CALENDAR VIEW (FULLCALENDAR FIXED)
    // =====================================================

    public function approvedAppointments()
    {
        $user = Auth::user();

        $events = Appointment::with('patient')
            ->where('status', 'approved')
            ->when($user->role === 'doctor', function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            })
            ->get()
            ->map(function ($appointment) {

                $start = Carbon::parse(
                    $appointment->appointment_date . ' ' . $appointment->appointment_time
                );

                $end = $start->copy()->addHours(2);

                return [
                    'title' => $appointment->patient->name ?? 'Patient',
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                ];
            });

        return view('appointments.calendar', compact('events'));
    }
}