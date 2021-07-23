<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function show(Patient $patient) {
        return response()->json($patient,200);
    }

    public function search(Request $request) {
        $request->validate(['key'=>'string|required']);

        $patients = Patient::where('name','like',"%$request->key%")
            ->orWhere('age','like',"%$request->key%")->get();

        return response()->json($patients, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'string|required',
            'age' => 'string|required',
            'gender' => 'string|required',
            'address' => 'string|required',
            'disease' => 'string|required',
            
            
        ]);

        try {
            $patient = Patient::create([

                'name' => $request->name,
                'age' => $request->age,
                'gender' => $request->gender,
                'address' => $request->address,
                'disease' => $request->disease,
                'user_id' => auth()->user()->id,
            ]);
            
            return response()->json($patient, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ],500);
        }

    }

    public function update(Request $request, Patient $patient) {
        try {
            $patient->update($request->all());
            return response()->json($patient, 202);
        }catch(Exception $ex) {
            return response()->json(['message'=>$ex->getMessage()], 500);
        }
    }

    public function destroy(Patient $patient) {
        $patient->delete();
        return response()->json(['message'=>'Patient deleted.'],202);
    }

    public function index() {
        $patients = Patient::where('user_id', auth()->user()->id)->orderBy('name')->get();
        return response()->json($patients, 200);
    }
}
