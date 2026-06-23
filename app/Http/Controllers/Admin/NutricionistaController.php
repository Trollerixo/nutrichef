<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NutricionistaController extends Controller
{
    public function index()
    {
        $nutricionistas = User::with('role')
            ->whereHas('role', fn ($q) => $q->where('slug', Role::NUTRITIONIST))
            ->withCount(['patients as patient_count' => fn ($q) => $q->where('active', true)])
            ->orderBy('name')
            ->get();

        return view('admin.nutricionistas.index', compact('nutricionistas'));
    }

    public function create()
    {
        return view('admin.nutricionistas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'specialty' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $role = Role::where('slug', Role::NUTRITIONIST)->firstOrFail();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'specialty' => $request->specialty,
            'active' => $request->boolean('active'),
            'role_id' => $role->id,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.nutricionistas.index')->with('success', 'Nutricionista creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('admin.nutricionistas.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'specialty' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'specialty' => $request->specialty,
            'active' => $request->boolean('active'),
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('admin.nutricionistas.index')->with('success', 'Nutricionista actualizado correctamente.');
    }

    public function toggle(User $user)
    {
        $user->update(['active' => ! $user->active]);

        return back()->with('success', 'Estado del nutricionista actualizado.');
    }
}
