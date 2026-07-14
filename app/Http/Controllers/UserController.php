<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Affiche la liste des utilisateurs et le formulaire
    public function index()
    {
        $users = User::with('service')->get();
        $services = Service::all();
        return view('users.index', compact('users', 'services'));
    }

    // Enregistre un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
            'service_id' => ['required', 'exists:services,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'service_id' => $request->service_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Agent ajouté avec succès.');
    }
}