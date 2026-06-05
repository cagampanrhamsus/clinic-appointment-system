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

        if ($user->role !== 'doctor') abort(403);

        $appointments = Appointment::with('patient')
            ->where('doctor_id', $user->id)
            ->where('status', 'approved')
            ->get();

        return view('prescriptions.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') abort(403);

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

        if (
            ($user->role === 'doctor' && $prescription->appointment->doctor_id !== $user->id) ||
            ($user->role === 'patient' && $prescription->appointment->patient_id !== $user->id)
        ) {
            abort(403);
        }

        $prescription->load(['appointment.doctor', 'appointment.patient']);

        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'));

        return $pdf->download('prescription-' . $prescription->id . '.pdf');
    }

    public function json(Prescription $prescription)
    {
        return response()->json($prescription)
            ->header(
                'Content-Disposition',
                'attachment; filename=prescription-' . $prescription->id . '.json'
            );
    }

    public function xml(Prescription $prescription)
    {
        $xml = new \SimpleXMLElement('<prescription/>');

        $xml->addChild('id', $prescription->id);
        $xml->addChild('appointment_id', $prescription->appointment_id);
        $xml->addChild('illness', $prescription->illness);
        $xml->addChild('medicine', $prescription->medicine);
        $xml->addChild('instructions', $prescription->instructions);

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header(
                'Content-Disposition',
                'attachment; filename=prescription-' . $prescription->id . '.xml'
            );
    }

    public function xsd(Prescription $prescription)
    {
        $xsd = '<?xml version="1.0" encoding="UTF-8"?>

<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="prescription">
[xs:complexType](xs:complexType)
[xs:sequence](xs:sequence)
<xs:element name="id" type="xs:integer"/>
<xs:element name="appointment_id" type="xs:integer"/>
<xs:element name="illness" type="xs:string"/>
<xs:element name="medicine" type="xs:string"/>
<xs:element name="instructions" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:schema>';


    return response($xsd, 200)
        ->header('Content-Type', 'application/xml')
        ->header(
            'Content-Disposition',
            'attachment; filename=prescription-schema.xsd'
        );
}

public function destroy(Prescription $prescription)
{
    $user = Auth::user();

    if ($user->role !== 'doctor') abort(403);

    $prescription->delete();

    return back();
}


}