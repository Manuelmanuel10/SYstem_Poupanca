<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscricao;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Listagem de todos os tenants (painel admin)
    // ─────────────────────────────────────────────────────────────────────────

    public function index()
    {
        $tenants = Tenant::with(['user', 'subscricoes'])
            ->withCount(['grupos', 'grupos as membros_count' => function ($q) {
                $q->join('membros', 'grupos.id', '=', 'membros.grupo_id');
            }])
            ->latest()
            ->paginate(20);

        $totais = [
            'tenants'  => Tenant::count(),
            'ativos'   => Tenant::where('estado', 'ativo')->count(),
            'suspensos'=> Tenant::where('estado', 'suspenso')->count(),
            'receita'  => Subscricao::where('estado', 'ativo')->sum('valor'),
        ];

        return view('admin.tenants.index', compact('tenants', 'totais'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Detalhe de um tenant
    // ─────────────────────────────────────────────────────────────────────────

    public function show(Tenant $tenant)
    {
        $tenant->load(['user', 'subscricoes', 'grupos.membros']);
        return view('admin.tenants.show', compact('tenant'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Formulário de edição (alterar plano / estado / data de expiração)
    // ─────────────────────────────────────────────────────────────────────────

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Guardar alterações do admin
    // ─────────────────────────────────────────────────────────────────────────

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'plano'          => 'required|in:basico,standard,premium',
            'estado'         => 'required|in:ativo,suspenso,cancelado',
            'data_expiracao' => 'nullable|date',
        ]);

        $tenant->update([
            'plano'          => $request->plano,
            'estado'         => $request->estado,
            'data_expiracao' => $request->data_expiracao,
        ]);

        return redirect()->route('admin.tenants.index')
            ->with('success', "Tenant \"{$tenant->nome_negocio}\" actualizado com sucesso.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Suspender / reactivar rapidamente via POST
    // ─────────────────────────────────────────────────────────────────────────

    public function suspender(Tenant $tenant)
    {
        $tenant->update(['estado' => 'suspenso']);
        return back()->with('success', "Tenant \"{$tenant->nome_negocio}\" suspenso.");
    }

    public function reativar(Tenant $tenant)
    {
        $tenant->update([
            'estado'         => 'ativo',
            'data_expiracao' => now()->addMonth(),
        ]);
        return back()->with('success', "Tenant \"{$tenant->nome_negocio}\" reactivado por mais 30 dias.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Eliminar tenant e todos os seus dados
    // ─────────────────────────────────────────────────────────────────────────

    public function destroy(Tenant $tenant)
    {
        $nome = $tenant->nome_negocio;
        // Elimina o utilizador — o cascade nas FK elimina tenant, grupos, etc.
        $tenant->user->delete();
        return redirect()->route('admin.tenants.index')
            ->with('success', "Tenant \"{$nome}\" e todos os seus dados foram eliminados.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Métodos não utilizados no fluxo actual (mantidos para compatibilidade)
    // ─────────────────────────────────────────────────────────────────────────

    public function create()
    {
        // Criação de tenants é feita via registo público
        return redirect()->route('admin.tenants.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.tenants.index');
    }
}
