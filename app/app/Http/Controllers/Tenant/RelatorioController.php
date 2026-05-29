<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Membro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    /**
     * Página de selecção de relatórios (Módulo 6).
     */
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)
            ->withCount('membros')
            ->latest()
            ->get();

        return view('tenant.relatorios.index', compact('grupos'));
    }

    /**
     * Gera e descarrega o extracto PDF de um grupo completo.
     */
    public function extratoGrupo(Grupo $grupo)
    {
        $grupo->load([
            'membros',
            'contribuicoes.membro',
            'emprestimos.membro',
        ]);

        // Totais por tipo de contribuição
        $totais = [
            'poupanca'     => $grupo->contribuicoes->where('tipo', 'poupanca')->sum('valor'),
            'fundo_social' => $grupo->contribuicoes->where('tipo', 'fundo_social')->sum('valor'),
            'atraso'       => $grupo->contribuicoes->where('tipo', 'atraso')->sum('valor'),
        ];

        // Empréstimos por estado
        $emprestimosResumo = [
            'pendente'  => $grupo->emprestimos->where('estado', 'pendente')->sum('valor_devido'),
            'pago'      => $grupo->emprestimos->where('estado', 'pago')->sum('valor_devido'),
            'atrasado'  => $grupo->emprestimos->where('estado', 'atrasado')->sum('valor_devido'),
        ];

        $geradoEm = now()->format('d/m/Y H:i');
        $nomeGestor = Auth::user()->name;

        $pdf = Pdf::loadView('tenant.relatorios.grupo_pdf', compact(
            'grupo', 'totais', 'emprestimosResumo', 'geradoEm', 'nomeGestor'
        ))->setPaper('a4', 'portrait');

        $nomeArquivo = 'extrato_grupo_' . str()->slug($grupo->nome) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nomeArquivo);
    }

    /**
     * Gera e descarrega o extracto PDF de um membro individual.
     */
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

        $geradoEm = now()->format('d/m/Y H:i');
        $nomeGestor = Auth::user()->name;

        $pdf = Pdf::loadView('tenant.relatorios.membro_pdf', compact(
            'membro', 'totaisContrib', 'totalContribuido', 'totalEmprestado', 'totalDevido',
            'geradoEm', 'nomeGestor'
        ))->setPaper('a4', 'portrait');

        $nomeArquivo = 'extrato_' . str()->slug($membro->nome) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nomeArquivo);
    }
}
