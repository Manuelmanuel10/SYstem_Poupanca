@extends('layouts.app-tenant')
@section('title', 'Divisão Final — ' . $grupo->nome)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Divisão Final — {{ $grupo->nome }}</h5>
        <p class="text-muted mb-0" style="font-size:13px">Cálculo automático do valor a receber por cada membro</p>
    </div>
    <a href="{{ route('tenant.grupos.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Voltar
    </a>
</div>

{{-- Resumo do Caixa --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white" style="background:#16a34a;border-radius:12px;border:none">
            <div class="card-body text-center py-3">
                <p class="mb-1 opacity-75" style="font-size:11px">TOTAL POUPANÇA</p>
                <h5 class="mb-0 fw-bold">{{ number_format($totalPoupanca, 2) }} MT</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background:#2563a8;border-radius:12px;border:none">
            <div class="card-body text-center py-3">
                <p class="mb-1 opacity-75" style="font-size:11px">FUNDO SOCIAL</p>
                <h5 class="mb-0 fw-bold">{{ number_format($totalFundo, 2) }} MT</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background:#d97706;border-radius:12px;border:none">
            <div class="card-body text-center py-3">
                <p class="mb-1 opacity-75" style="font-size:11px">JUROS RECEBIDOS</p>
                <h5 class="mb-0 fw-bold">{{ number_format($totalJurosRecebidos, 2) }} MT</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background:#1a3c5e;border-radius:12px;border:none">
            <div class="card-body text-center py-3">
                <p class="mb-1 opacity-75" style="font-size:11px">TOTAL CAIXA</p>
                <h5 class="mb-0 fw-bold">{{ number_format($totalCaixa, 2) }} MT</h5>
            </div>
        </div>
    </div>
</div>

{{-- Tabela de Divisão --}}
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
    <div class="card-header bg-white py-3 d-flex justify-content-between">
        <span class="fw-bold"><i class="fas fa-calculator me-2 text-primary"></i>Divisão por Membro</span>
        <span class="text-muted" style="font-size:13px">{{ $totalMembros }} membros activos</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Membro</th>
                    <th>Cargo</th>
                    <th class="text-end">Poupança</th>
                    <th class="text-end">Fundo Social</th>
                    <th class="text-end">Juros Recebidos</th>
                    <th class="text-end text-danger">Dívida</th>
                    <th class="text-end text-success">Valor a Receber</th>
                </tr>
            </thead>
            <tbody>
                @foreach($divisao as $d)
                <tr>
                    <td class="fw-semibold">{{ $d['membro']->nome }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($d['membro']->cargo) }}</span></td>
                    <td class="text-end">{{ number_format($d['poupanca'], 2) }} MT</td>
                    <td class="text-end">{{ number_format($d['fundo'], 2) }} MT</td>
                    <td class="text-end text-success">+{{ number_format($d['juros'], 2) }} MT</td>
                    <td class="text-end text-danger">
                        {{ $d['divida'] > 0 ? '-'.number_format($d['divida'], 2).' MT' : '—' }}
                    </td>
                    <td class="text-end fw-bold" style="color:{{ $d['valorAReceber'] >= 0 ? '#16a34a' : '#dc2626' }};font-size:15px">
                        {{ number_format($d['valorAReceber'], 2) }} MT
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="2" class="fw-bold">TOTAL</td>
                    <td class="text-end fw-bold">{{ number_format(array_sum(array_column($divisao, 'poupanca')), 2) }} MT</td>
                    <td class="text-end fw-bold">{{ number_format(array_sum(array_column($divisao, 'fundo')), 2) }} MT</td>
                    <td class="text-end fw-bold text-success">+{{ number_format(array_sum(array_column($divisao, 'juros')), 2) }} MT</td>
                    <td class="text-end fw-bold text-danger">-{{ number_format(array_sum(array_column($divisao, 'divida')), 2) }} MT</td>
                    <td class="text-end fw-bold text-primary" style="font-size:16px">{{ number_format(array_sum(array_column($divisao, 'valorAReceber')), 2) }} MT</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Confirmação de encerramento --}}
<div class="card border-danger" style="border-radius:12px">
    <div class="card-body p-4">
        <h6 class="text-danger fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Encerramento</h6>
        <p class="text-muted mb-3" style="font-size:13px">
            Ao confirmar, o grupo será marcado como <strong>encerrado</strong> e não poderá receber novas contribuições ou empréstimos.
            Esta acção é irreversível.
        </p>
        <form method="POST" action="{{ route('tenant.grupos.confirmar', $grupo) }}"
            onsubmit="return confirm('Tem a certeza que quer encerrar este grupo? Esta acção não pode ser desfeita.')">
            @csrf
            <button type="submit" class="btn btn-danger px-4">
                <i class="fas fa-flag-checkered me-2"></i>Confirmar Encerramento do Grupo
            </button>
            <a href="{{ route('tenant.grupos.index') }}" class="btn btn-outline-secondary ms-2 px-4">Cancelar</a>
        </form>
    </div>
</div>
@endsection
