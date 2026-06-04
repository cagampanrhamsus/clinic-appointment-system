<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $prescriptions = Prescription::with([
                'appointment.doctor',
                'appointment.patient'
            ])
            ->when($user->role === 'doctor', function ($q) use ($user) {
                $q->whereHas('appointment', function ($sub) use ($user) {
                    $sub->where('doctor_id', $user->id);
                });
            })
            ->when($user->role === 'patient', function ($q) use ($user) {
                $q->whereHas('appointment', function ($sub) use ($user) {
                    $sub->where('patient_id', $user->id);
                });
            })
            ->latest()
            ->get();

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            abort(403);
        }

        $appointments = Appointment::with('patient')
            ->where('doctor_id', $user->id)
            ->where('status', 'approved')
            ->get();

        return view('prescriptions.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            abort(403);
        }

        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'medicine' => 'required|string',
            'instructions' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        Prescription::create([
            'appointment_id' => $appointment->id,
            'medicine' => $request->medicine,
            'instructions' => $request->instructions,
            'illness' => $appointment->symptoms,
        ]);

        MedicalHistory::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $user->id,
            'diagnosis' => $appointment->symptoms,
            'treatment' => $request->medicine,
            'notes' => $request->instructions,
        ]);

        return redirect()->route('prescriptions.index')
            ->with('success', 'Saved successfully!');
    }

    public function downloadPdf(Prescription $prescription)
    {
        $user = Auth::user();

        // 🔒 SECURITY: prevent accessing others' prescriptions
        if (
            ($user->role === 'doctor' && $prescription->appointment->doctor_id !== $user->id) ||
            ($user->role === 'patient' && $prescription->appointment->patient_id !== $user->id)
        ) {
            abort(403);
        }

        $prescription->load([
            'appointment.doctor',
            'appointment.patient'
        ]);

        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'));

        return $pdf->download('prescription-' . $prescription->id . '.pdf');
    }

    public function destroy(Prescription $prescription)
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            abort(403);
        }

        $prescription->delete();

        return back();
    }
}