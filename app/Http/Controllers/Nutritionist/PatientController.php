<?php

namespace App\Http\Controllers\Nutritionist;

use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    public function index()
    {
        $patients = auth()->user()->patientsUsers()->with('profile')->orderBy('name')->get();

        return view('nutritionist.patients.index', compact('patients'));
    }
}
