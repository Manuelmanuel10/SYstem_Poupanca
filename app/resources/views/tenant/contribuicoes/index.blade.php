@extends('layouts.app-tenant')
@section('title', 'Contribuições')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background:#16a34a;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">TOTAL POUPANÇA</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalPoupanca, 2) }} MT</h4>
                </div>
                <i class="fas fa-piggy-bank fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background:#2563a8;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">FUNDO SOCIAL</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalFundo, 2) }} MT</h4>
                </div>
                <i class="fas fa-hand-holding-heart fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background:#dc2626;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">MULTAS / ATRASO</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalAtraso, 2) }} MT</h4>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold">Registo de Contribuições</h5>
    <a href="{{ route('tenant.contribuicoes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nova Contribuição
    </a>
</div>

<div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Data</th><th>Membro</th><th>Grupo</th><th>Tipo</th><th>Valor</th><th>Acções</th></tr>
            </thead>
            <tbody>
                @forelse($contribuicoes as $c)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}</td>
                    <td class="fw-semibold">{{ $c->membro->nome }}</td>
                    <td>{{ $c->grupo->nome }}</td>
                    <td>
                        @if($c->tipo == 'poupanca')
                            <span class="badge bg-success">Poupança</span>
                        @elseif($c->tipo == 'fundo_social')
                            <span class="badge bg-primary">Fundo Social</span>
                        @else
                            <span class="badge bg-danger">Atraso/Multa</span>
                        @endif
                    </td>
                    <td class="fw-bold">{{ number_format($c->valor, 2) }} MT</td>
                    <td>
                        <a href="{{ route('tenant.contribuicoes.edit', $c) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('tenant.contribuicoes.destroy', $c) }}" class="d-inline"
                            onsubmit="return confirm('Eliminar esta contribuição?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhuma contribuição registada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
