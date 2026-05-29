<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)
            ->withCount('membros')->latest()->get();
        return view('tenant.grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('tenant.grupos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'              => 'required|string|max:255',
            'taxa_juro'         => 'required|numeric|min:0',
            'taxa_atraso'       => 'required|numeric|min:0',
            'taxa_fundo_social' => 'required|numeric|min:0',
            'data_inicio'       => 'required|date',
        ]);

        $tenant = Auth::user()->tenant;
        Grupo::create([
            'tenant_id'         => $tenant->id,
            'nome'              => $request->nome,
            'taxa_juro'         => $request->taxa_juro,
            'taxa_atraso'       => $request->taxa_atraso,
            'taxa_fundo_social' => $request->taxa_fundo_social,
            'data_inicio'       => $request->data_inicio,
            'estado'            => 'ativo',
        ]);

        return redirect()->route('tenant.grupos.index')
            ->with('success', 'Grupo criado com sucesso!');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load('membros');
        return view('tenant.grupos.show', compact('grupo'));
    }

    public function edit(Grupo $grupo)
    {
        return view('tenant.grupos.edit', compact('grupo'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nome'              => 'required|string|max:255',
            'taxa_juro'         => 'required|numeric|min:0',
            'taxa_atraso'       => 'required|numeric|min:0',
            'taxa_fundo_social' => 'required|numeric|min:0',
            'data_inicio'       => 'required|date',
        ]);

        $grupo->update($request->all());
        return redirect()->route('tenant.grupos.index')
            ->with('success', 'Grupo actualizado com sucesso!');
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return redirect()->route('tenant.grupos.index')
            ->with('success', 'Grupo eliminado!');
    }

    // ──────────────────────────────────────────────
    //  MÓDULO 7 — Encerramento e Divisão Final
    // ──────────────────────────────────────────────

    /**
     * Calcula e exibe a divisão final antes de confirmar o encerramento.
     */
    public function encerrar(Grupo $grupo)
    {
        // Garante que o grupo pertence ao tenant autenticado
        abort_if($grupo->tenant_id !== Auth::user()->tenant->id, 403);
        abort_if($grupo->estado === 'encerrado', 404, 'Este grupo já está encerrado.');

        $grupo->load(['membros', 'contribuicoes', 'emprestimos']);

        // ── Contribuições ──
        $totalPoupanca    = $grupo->contribuicoes->where('tipo', 'poupanca')->sum('valor');
        $totalFundo       = $grupo->contribuicoes->where('tipo', 'fundo_social')->sum('valor');
        $totalMultas      = $grupo->contribuicoes->where('tipo', 'atraso')->sum('valor');

        // ── Empréstimos ──
        // Juros efectivamente recebidos (só empréstimos pagos)
        $jurosRecebidos = $grupo->emprestimos
            ->where('estado', 'pago')
            ->sum(fn($e) => $e->valor_devido - $e->valor_principal);

        // Valor ainda em dívida (empréstimos pendentes + atrasados — principal)
        $emprestimosPendentes = $grupo->emprestimos
            ->whereIn('estado', ['pendente', 'atrasado'])
            ->sum('valor_principal');

        // ── Totais ──
        $totalBruto   = $totalPoupanca + $totalFundo + $totalMultas + $jurosRecebidos;
        $valorLiquido = max(0, $totalBruto - $emprestimosPendentes);

        $membrosAtivos = $grupo->membros->where('estado', 'ativo');
        $numMembros    = $membrosAtivos->count();

        $valorPorMembro = $numMembros > 0
            ? round($valorLiquido / $numMembros, 2)
            : 0;

        // Divisão individual por membro (poupança individual de cada um)
        $divisaoDetalhada = $membrosAtivos->map(function ($membro) use ($grupo, $valorPorMembro) {
            $poupancaIndividual = $grupo->contribuicoes
                ->where('membro_id', $membro->id)
                ->where('tipo', 'poupanca')
                ->sum('valor');

            $emprestimosAtivos = $grupo->emprestimos
                ->where('membro_id', $membro->id)
                ->whereIn('estado', ['pendente', 'atrasado'])
                ->sum('valor_principal');

            return [
                'membro'              => $membro,
                'poupanca_individual' => $poupancaIndividual,
                'emprestimo_activo'   => $emprestimosAtivos,
                'valor_a_receber'     => $valorPorMembro,
            ];
        });

        $divisao = [
            'totalPoupanca'        => $totalPoupanca,
            'totalFundo'           => $totalFundo,
            'totalMultas'          => $totalMultas,
            'jurosRecebidos'       => $jurosRecebidos,
            'totalBruto'           => $totalBruto,
            'emprestimosPendentes' => $emprestimosPendentes,
            'valorLiquido'         => $valorLiquido,
            'numMembros'           => $numMembros,
            'valorPorMembro'       => $valorPorMembro,
            'divisaoDetalhada'     => $divisaoDetalhada,
        ];

        return view('tenant.grupos.encerrar', compact('grupo', 'divisao'));
    }

    /**
     * Confirma o encerramento: marca o grupo como encerrado e regista a data_fim.
     */
    public function confirmarEncerramento(Request $request, Grupo $grupo)
    {
        abort_if($grupo->tenant_id !== Auth::user()->tenant->id, 403);
        abort_if($grupo->estado === 'encerrado', 404);

        $grupo->update([
            'estado'   => 'encerrado',
            'data_fim' => now()->toDateString(),
        ]);

        return redirect()->route('tenant.grupos.index')
            ->with('success', "Grupo \"{$grupo->nome}\" encerrado com sucesso! A divisão final foi calculada.");
    }
}
