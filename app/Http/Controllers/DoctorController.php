<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'doctor')->get();

        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'doctor',
        ]);

        return redirect()->route('doctors.index');
    }

    public function show($id)
    {
        $doctor = User::findOrFail($id);

        return view('doctors.show', compact('doctor'));
    }

    public function edit($id)
    {
        $doctor = User::findOrFail($id);

        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('doctors.index');
    }

    public function destroy($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index');
    }
}