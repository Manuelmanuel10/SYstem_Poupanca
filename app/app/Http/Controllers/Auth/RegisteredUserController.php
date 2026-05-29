<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscricao;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostra o formulário de registo com escolha de plano (Módulo 1).
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Cria o utilizador, o Tenant e a Subscrição inicial.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'nome_negocio' => ['required', 'string', 'max:255'],
            'telefone'     => ['nullable', 'string', 'max:20'],
            'plano'        => ['required', 'in:basico,standard,premium'],
        ]);

        // 1. Criar utilizador
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. Criar Tenant ligado ao utilizador
        $tenant = Tenant::create([
            'user_id'        => $user->id,
            'nome_negocio'   => $request->nome_negocio,
            'telefone'       => $request->telefone,
            'plano'          => $request->plano,
            'estado'         => 'ativo',
            'data_expiracao' => now()->addDays(30),
        ]);

        // 3. Criar Subscrição inicial (30 dias de teste / 1.º mês)
        $precos = ['basico' => 500.00, 'standard' => 1000.00, 'premium' => 2000.00];

        Subscricao::create([
            'tenant_id'        => $tenant->id,
            'plano'            => $request->plano,
            'valor'            => $precos[$request->plano],
            'data_inicio'      => now()->toDateString(),
            'data_fim'         => now()->addDays(30)->toDateString(),
            'estado'           => 'ativo',
            'metodo_pagamento' => 'pendente',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('tenant.dashboard', absolute: false));
    }
}
