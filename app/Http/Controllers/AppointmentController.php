<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => Auth::id(),
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'symptoms' => $request->symptoms,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully!');
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
}