<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Service\RabbitMQPublisher;

class PatientController extends Controller
{

    public function __construct(private RabbitMQPublisher $rabbitMQ) {}
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
        $this->rabbitMQ->publish((array) $patient, 'patient.created');
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
    public function destroy(Patient $patient) {
        $data = $patient->toArray();
        $patient->delete();
        $this->rabbitMQ->publish($data, 'patient.deleted');
        return response()->json(['message'=>'deleted']);
    }
}
