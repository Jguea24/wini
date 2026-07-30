<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::withCount(['ventas', 'gastos'])->orderBy('name')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', ['user' => new User()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'usuario'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'show_in_org_chart' => ['nullable', 'boolean'],
            'org_chart_position' => ['nullable', 'string', 'max:120'],
            'org_chart_level' => ['required', Rule::in(['ceo', 'director', 'support'])],
            'org_chart_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active'),
            'show_in_org_chart' => $request->boolean('show_in_org_chart'),
            'org_chart_position' => $data['org_chart_position'] ?? null,
            'org_chart_level' => $data['org_chart_level'],
            'org_chart_order' => $data['org_chart_order'] ?? 0,
            'password' => Hash::make($data['password']),
        ]);

        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Throwable $e) {
            logger()->error('Error al enviar correo de bienvenida: '.$e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in(['admin', 'usuario'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'show_in_org_chart' => ['nullable', 'boolean'],
            'org_chart_position' => ['nullable', 'string', 'max:120'],
            'org_chart_level' => ['required', Rule::in(['ceo', 'director', 'support'])],
            'org_chart_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active'),
            'show_in_org_chart' => $request->boolean('show_in_org_chart'),
            'org_chart_position' => $data['org_chart_position'] ?? null,
            'org_chart_level' => $data['org_chart_level'],
            'org_chart_order' => $data['org_chart_order'] ?? 0,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()->route('admin.users.index')->with('status', 'Usuario actualizado correctamente.');
    }
}
