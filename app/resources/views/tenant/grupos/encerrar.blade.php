@extends('layouts.app-tenant')
@section('title', 'Encerrar Grupo — ' . $grupo->nome)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">
            <i class="fas fa-flag-checkered text-danger me-2"></i>
            Encerrar Grupo — <span class="text-primary">{{ $grupo->nome }}</span>
        </h4>
        <p class="text-muted small mb-0">Revisão da divisão final antes de confirmar o encerramento</p>
    </div>
    <a href="{{ route('tenant.grupos.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Voltar
    </a>
</div>

{{-- Alerta de aviso --}}
<div class="alert alert-warning d-flex align-items-start gap-3 mb-4">
    <i class="fas fa-triangle-exclamation fa-lg mt-1"></i>
    <div>
        <strong>Atenção!</strong> Esta acção é irreversível. Ao confirmar, o grupo será marcado como
        <strong>encerrado</strong> e não poderão ser adicionadas mais contribuições ou empréstimos.
        @if($divisao['emprestimosPendentes'] > 0)
            <br><span class="text-danger">
                Existem <strong>{{ number_format($divisao['emprestimosPendentes'], 2) }} MZN</strong>
                em empréstimos ainda por liquidar — este valor foi deduzido do montante a distribuir.
            </span>
        @endif
    </div>
</div>

{{-- Breakdown financeiro --}}
<div class="row g-4 mb-4">

    {{-- Entradas --}}
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white fw-bold text-success">
                <i class="fas fa-arrow-down me-1"></i> Entradas (Receitas)
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Poupança total</span>
                    <strong>{{ number_format($divisao['totalPoupanca'], 2) }} MZN</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Fundo social</span>
                    <strong>{{ number_format($divisao['totalFundo'], 2) }} MZN</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Multas por atraso</span>
                    <strong>{{ number_format($divisao['totalMultas'], 2) }} MZN</strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Juros recebidos (empréstimos pagos)</span>
                    <strong>{{ number_format($divisao['jurosRecebidos'], 2) }} MZN</strong>
                </div>
                <div class="d-flex justify-content-between border-top pt-2 mt-1">
                    <span class="fw-bold">Total Bruto</span>
                    <span class="fw-bold text-success fs-6">{{ number_format($divisao['totalBruto'], 2) }} MZN</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Deduções + líquido --}}
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white fw-bold text-danger">
                <i class="fas fa-arrow-up me-1"></i> Deduções & Resultado Final
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Total bruto</span>
                    <strong>{{ number_format($divisao['totalBruto'], 2) }} MZN</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted text-danger">(-) Empréstimos por liquidar</span>
                    <strong class="text-danger">{{ number_format($divisao['emprestimosPendentes'], 2) }} MZN</strong>
                </div>
                <div class="d-flex justify-content-between border-top pt-2 mt-1">
                    <span class="fw-bold">Valor Líquido a Dividir</span>
                    <span class="fw-bold text-primary fs-5">{{ number_format($divisao['valorLiquido'], 2) }} MZN</span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Nº de membros activos</span>
                    <strong>{{ $divisao['numMembros'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mt-2 p-3 rounded" style="background:#eff6ff;">
                    <span class="fw-bold text-primary">Valor por membro</span>
                    <span class="fw-bold text-primary fs-5">{{ number_format($divisao['valorPorMembro'], 2) }} MZN</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabela de divisão individual --}}
<div class="card stat-card mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-table me-1"></i> Divisão por Membro
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Membro</th>
                    <th class="text-end">Poupança individual</th>
                    <th class="text-end">Empréstimo activo</th>
                    <th class="text-end text-primary">A receber (quota)</th>
                </tr>
            </thead>
            <tbody>
            @forelse($divisao['divisaoDetalhada'] as $i => $linha)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $linha['membro']->nome }}</strong></td>
                <td class="text-end">{{ number_format($linha['poupanca_individual'], 2) }} MZN</td>
                <td class="text-end {{ $linha['emprestimo_activo'] > 0 ? 'text-danger' : '' }}">
                    {{ number_format($linha['emprestimo_activo'], 2) }} MZN
                </td>
                <td class="text-end fw-bold text-primary">{{ number_format($linha['valor_a_receber'], 2) }} MZN</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">Nenhum membro activo.</td></tr>
            @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Distribuído</td>
                    <td class="text-end fw-bold text-primary">
                        {{ number_format($divisao['valorPorMembro'] * $divisao['numMembros'], 2) }} MZN
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Botões de acção --}}
<div class="d-flex gap-3 justify-content-end">
    <a href="{{ route('tenant.grupos.show', $grupo) }}" class="btn btn-outline-secondary">
        <i class="fas fa-times me-1"></i> Cancelar
    </a>

    <form method="POST" action="{{ route('tenant.grupos.confirmar-encerramento', $grupo) }}"
          onsubmit="return confirm('Confirma o encerramento definitivo do grupo \'{{ $grupo->nome }}\'? Esta acção não pode ser desfeita.')">
        @csrf
        <button type="submit" class="btn btn-danger px-4">
            <i class="fas fa-flag-checkered me-1"></i> Confirmar Encerramento
        </button>
    </form>
</div>
@endsection
