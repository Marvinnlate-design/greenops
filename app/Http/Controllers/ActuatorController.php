<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use App\Models\ActuatorLog;
use Illuminate\Http\Request;

class ActuatorController extends Controller
{
    // Afficher la liste des actionneurs
    public function index()
    {
        $actuators = Actuator::all();
        return view('actuators.index', compact('actuators'));
    }

    // Toggle manuel (AJAX)
    public function toggle($id)
    {
        $actuator = Actuator::findOrFail($id);
        $actuator->status = !$actuator->status;
        $actuator->save();

        ActuatorLog::create([
            'actuator_id' => $actuator->id,
            'command' => $actuator->status,
            'triggered_by' => 'manual',
        ]);

        return response()->json(['success' => true, 'status' => $actuator->status]);
    }
}