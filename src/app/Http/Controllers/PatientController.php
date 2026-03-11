<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Patient::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname'=>'required|string',
            'lastname'=>'required|string',
            'date_naissance'=>'required|date',
            'email'=>'required|string|email',
            'city'=>'required|string'
        ]);
        
        $patient = Patient::create($data);
        return $patient;
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return $patient;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());
        return $patient;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
         $patient->delete();
        return response()->json(['message'=>'deleted']);
    }
}
