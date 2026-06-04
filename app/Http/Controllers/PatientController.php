<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::where('role', 'patient')->get();

        return view('patients.index', compact('patients'));
    }

    public function show($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        return view('patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $patient->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('patients.index');
    }

    public function destroy($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $patient->delete();

        return redirect()->route('patients.index');
    }
}