<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscricao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function planos()
    {
        $tenant = Auth::user()->tenant;
        if ($tenant && $tenant->estado === 'ativo') {
            return redirect()->route('tenant.dashboard');
        }
        return view('tenant.onboarding.planos');
    }

    public function escolherPlano(Request $request)
    {
        $request->validate([
            'plano' => 'required|in:basico,standard,premium',
        ]);

        $precos = ['basico' => 500, 'standard' => 1000, 'premium' => 2000];
        $preco  = $precos[$request->plano];

        $tenant = Tenant::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nome_negocio'   => Auth::user()->name,
                'plano'          => $request->plano,
                'estado'         => 'ativo',
                'data_expiracao' => now()->addMonth(),
            ]
        );

        Subscricao::create([
            'tenant_id'        => $tenant->id,
            'plano'            => $request->plano,
            'valor'            => $preco,
            'data_inicio'      => now(),
            'data_fim'         => now()->addMonth(),
            'estado'           => 'ativo',
            'metodo_pagamento' => 'manual',
        ]);

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Subscrição activada! Bem-vindo ao Sistema de Poupança.');
    }

    public function subscricao()
    {
        $tenant = Auth::user()->tenant;
        $subscricoes = $tenant ? $tenant->subscricoes()->latest()->get() : collect();
        return view('tenant.onboarding.subscricao', compact('tenant', 'subscricoes'));
    }

    public function renovar(Request $request)
    {
        $request->validate(['plano' => 'required|in:basico,standard,premium']);
        $precos = ['basico' => 500, 'standard' => 1000, 'premium' => 2000];

        $tenant = Auth::user()->tenant;
        $tenant->update([
            'plano'          => $request->plano,
            'estado'         => 'ativo',
            'data_expiracao' => now()->addMonth(),
        ]);

        Subscricao::create([
            'tenant_id'        => $tenant->id,
            'plano'            => $request->plano,
            'valor'            => $precos[$request->plano],
            'data_inicio'      => now(),
            'data_fim'         => now()->addMonth(),
            'estado'           => 'ativo',
            'metodo_pagamento' => 'manual',
        ]);

        return redirect()->route('tenant.onboarding.subscricao')
            ->with('success', 'Subscrição renovada com sucesso!');
    }
}
