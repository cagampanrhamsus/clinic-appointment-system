<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    public function index()
    {
        $histories = MedicalHistory::where(
            'patient_id',
            Auth::id()
        )->latest()->get();

        return view('medical-history.index', compact('histories'));
    }

    public function create()
    {
        $patients = User::where('role', 'patient')->get();

        return view('medical-history.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'diagnosis' => 'required',
            'treatment' => 'required',
            'notes' => 'nullable',
        ]);

        MedicalHistory::create([
            'patient_id' => $request->patient_id,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'notes' => $request->notes,
        ]);

        return redirect()->route('medical-history.index');
    }
}
