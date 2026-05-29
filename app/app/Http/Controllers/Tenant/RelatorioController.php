<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Contribuicao;
use App\Models\Emprestimo;
use App\Models\Grupo;
use App\Models\Membro;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Módulo 6 — Página de selecção de relatórios
    // ─────────────────────────────────────────────────────────────────────────

    public function index()
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)
            ->withCount('membros')
            ->with('membros')
            ->latest()
            ->get();

        return view('tenant.relatorios.index', compact('grupos'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Extracto completo do grupo em PDF
    // ─────────────────────────────────────────────────────────────────────────

    public function extratoGrupo(Grupo $grupo)
    {
        $grupo->load([
            'membros',
            'contribuicoes.membro',
            'emprestimos.membro',
        ]);

        $totais = [
            'poupanca'     => $grupo->contribuicoes->where('tipo', 'poupanca')->sum('valor'),
            'fundo_social' => $grupo->contribuicoes->where('tipo', 'fundo_social')->sum('valor'),
            'atraso'       => $grupo->contribuicoes->where('tipo', 'atraso')->sum('valor'),
        ];

        $emprestimosResumo = [
            'pendente' => $grupo->emprestimos->where('estado', 'pendente')->sum('valor_devido'),
            'pago'     => $grupo->emprestimos->where('estado', 'pago')->sum('valor_devido'),
            'atrasado' => $grupo->emprestimos->where('estado', 'atrasado')->sum('valor_devido'),
        ];

        $geradoEm   = now()->format('d/m/Y H:i');
        $nomeGestor = Auth::user()->name;

        $pdf = Pdf::loadView('tenant.relatorios.grupo_pdf', compact(
            'grupo', 'totais', 'emprestimosResumo', 'geradoEm', 'nomeGestor'
        ))->setPaper('a4', 'portrait');

        $nomeArquivo = 'extrato_grupo_' . str()->slug($grupo->nome) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nomeArquivo);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Extracto individual do membro em PDF
    // ─────────────────────────────────────────────────────────────────────────

    public function extratoMembro(Membro $membro)
    {
        $membro->load(['contribuicoes', 'emprestimos', 'grupo']);

        $totaisContrib = [
            'poupanca'     => $membro->contribuicoes->where('tipo', 'poupanca')->sum('valor'),
            'fundo_social' => $membro->contribuicoes->where('tipo', 'fundo_social')->sum('valor'),
            'atraso'       => $membro->contribuicoes->where('tipo', 'atraso')->sum('valor'),
        ];

        $totalContribuido = array_sum($totaisContrib);
        $totalEmprestado  = $membro->emprestimos->sum('valor_principal');
        $totalDevido      = $membro->emprestimos->where('estado', '!=', 'pago')->sum('valor_devido');

        $geradoEm   = now()->format('d/m/Y H:i');
        $nomeGestor = Auth::user()->name;

        $pdf = Pdf::loadView('tenant.relatorios.membro_pdf', compact(
            'membro', 'totaisContrib', 'totalContribuido',
            'totalEmprestado', 'totalDevido', 'geradoEm', 'nomeGestor'
        ))->setPaper('a4', 'portrait');

        $nomeArquivo = 'extrato_' . str()->slug($membro->nome) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nomeArquivo);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Módulo 5 — Livro-Caixa em PDF (método estava em falta)
    // Rota: GET /tenant/relatorios/caixa/{grupo}?data_inicio=...&data_fim=...
    // ─────────────────────────────────────────────────────────────────────────

    public function extratoCaixa(Request $request, Grupo $grupo)
    {
        // Garante que o grupo pertence ao tenant autenticado
        abort_if($grupo->tenant_id !== Auth::user()->tenant->id, 403);

        $dataInicio = $request->input('data_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dataFim    = $request->input('data_fim',    Carbon::now()->format('Y-m-d'));

        // ── Contribuições como entradas ───────────────────────────────────────
        $contribuicoes = Contribuicao::where('grupo_id', $grupo->id)
            ->whereBetween('data', [$dataInicio, $dataFim])
            ->with(['membro', 'grupo'])
            ->get()
            ->map(fn($c) => [
                'data'      => $c->data,
                'descricao' => ucfirst(str_replace('_', ' ', $c->tipo)) . ' — ' . $c->membro->nome,
                'entrada'   => $c->valor,
                'saida'     => 0,
                'tipo'      => 'entrada',
                'categoria' => $c->tipo,
            ]);

        // ── Empréstimos concedidos como saídas ────────────────────────────────
        $emprestimos = Emprestimo::where('grupo_id', $grupo->id)
            ->whereBetween('data_emprestimo', [$dataInicio, $dataFim])
            ->with(['membro'])
            ->get()
            ->map(fn($e) => [
                'data'      => $e->data_emprestimo,
                'descricao' => 'Empréstimo concedido — ' . $e->membro->nome,
                'entrada'   => 0,
                'saida'     => $e->valor_principal,
                'tipo'      => 'saida',
                'categoria' => 'emprestimo',
            ]);

        // ── Pagamentos recebidos de empréstimos como entradas ─────────────────
        $pagamentos = Emprestimo::where('grupo_id', $grupo->id)
            ->where('estado', 'pago')
            ->whereBetween('updated_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
            ->with(['membro'])
            ->get()
            ->map(fn($e) => [
                'data'      => Carbon::parse($e->updated_at)->format('Y-m-d'),
                'descricao' => 'Pagamento de empréstimo — ' . $e->membro->nome,
                'entrada'   => $e->valor_devido,
                'saida'     => 0,
                'tipo'      => 'entrada',
                'categoria' => 'pagamento_emprestimo',
            ]);

        // ── Juntar, ordenar e calcular saldo acumulado ────────────────────────
        $saldo      = 0;
        $movimentos = $contribuicoes
            ->concat($emprestimos)
            ->concat($pagamentos)
            ->sortBy('data')
            ->values()
            ->map(function ($m) use (&$saldo) {
                $saldo      += $m['entrada'] - $m['saida'];
                $m['saldo']  = $saldo;
                return $m;
            });

        $totalEntradas = $movimentos->sum('entrada');
        $totalSaidas   = $movimentos->sum('saida');
        $saldoFinal    = $totalEntradas - $totalSaidas;

        $nomeGestor = Auth::user()->name;

        $pdf = Pdf::loadView('tenant.relatorios.pdf.caixa', compact(
            'grupo', 'movimentos', 'totalEntradas',
            'totalSaidas', 'saldoFinal', 'nomeGestor',
            'dataInicio', 'dataFim'
        ))->setPaper('a4', 'landscape');

        $nomeArquivo = 'caixa_' . str()->slug($grupo->nome) . '_'
                     . str_replace('-', '', $dataInicio) . '.pdf';

        return $pdf->download($nomeArquivo);
    }
}
