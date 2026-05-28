@extends('layouts.app-tenant')
@section('title', 'Empréstimos')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background:#d97706;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">PENDENTES</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalPendente, 2) }} MT</h4>
                </div>
                <i class="fas fa-clock fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background:#16a34a;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">PAGOS</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalPago, 2) }} MT</h4>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background:#dc2626;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">ATRASADOS</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalAtrasado, 2) }} MT</h4>
                </div>
                <i class="fas fa-exclamation-circle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold">Registo de Empréstimos</h5>
    <a href="{{ route('tenant.emprestimos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Empréstimo
    </a>
</div>

<div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Membro</th><th>Grupo</th><th>Principal</th><th>Juro</th><th>Total Devido</th><th>Vencimento</th><th>Estado</th><th>Acções</th></tr>
            </thead>
            <tbody>
                @forelse($emprestimos as $e)
                <tr>
                    <td class="fw-semibold">{{ $e->membro->nome }}</td>
                    <td>{{ $e->grupo->nome }}</td>
                    <td>{{ number_format($e->valor_principal, 2) }} MT</td>
                    <td>{{ $e->taxa_juro }}%</td>
                    <td class="fw-bold text-danger">{{ number_format($e->valor_devido, 2) }} MT</td>
                    <td>{{ \Carbon\Carbon::parse($e->data_vencimento)->format('d/m/Y') }}</td>
                    <td>
                        @if($e->estado == 'pendente')
                            <span class="badge bg-warning text-dark">Pendente</span>
                        @elseif($e->estado == 'pago')
                            <span class="badge bg-success">Pago</span>
                        @else
                            <span class="badge bg-danger">Atrasado</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tenant.emprestimos.edit', $e) }}" class="btn btn-sm btn-outline-warning" title="Alterar estado">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('tenant.emprestimos.destroy', $e) }}" class="d-inline"
                            onsubmit="return confirm('Eliminar este empréstimo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Nenhum empréstimo registado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
