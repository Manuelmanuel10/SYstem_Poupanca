@extends('layouts.app-tenant')
@section('title', 'Dashboard')
@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background:#2563a8">
            <div class="card-body"><div class="d-flex justify-content-between">
                <div><p class="mb-1 opacity-75">Total Grupos</p><h3>{{ $totalGrupos }}</h3></div>
                <i class="fas fa-users fa-2x opacity-50"></i>
            </div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background:#16a34a">
            <div class="card-body"><div class="d-flex justify-content-between">
                <div><p class="mb-1 opacity-75">Total Membros</p><h3>{{ $totalMembros }}</h3></div>
                <i class="fas fa-user fa-2x opacity-50"></i>
            </div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background:#d97706">
            <div class="card-body"><div class="d-flex justify-content-between">
                <div><p class="mb-1 opacity-75">Total Poupado</p><h3>{{ number_format($totalPoupado, 2) }}</h3></div>
                <i class="fas fa-piggy-bank fa-2x opacity-50"></i>
            </div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background:#dc2626">
            <div class="card-body"><div class="d-flex justify-content-between">
                <div><p class="mb-1 opacity-75">Empréstimos Activos</p><h3>{{ $emprestimosActivos }}</h3></div>
                <i class="fas fa-money-bill fa-2x opacity-50"></i>
            </div></div>
        </div>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-header bg-white fw-bold">Grupos Recentes</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Nome</th><th>Membros</th><th>Estado</th></tr></thead>
                    <tbody>
                        @forelse($grupos as $grupo)
                        <tr><td>{{ $grupo->nome }}</td><td>{{ $grupo->membros_count }}</td><td><span class="badge bg-success">{{ $grupo->estado }}</span></td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Nenhum grupo criado</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-header bg-white fw-bold">Empréstimos Pendentes</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Membro</th><th>Valor</th><th>Vencimento</th></tr></thead>
                    <tbody>
                        @forelse($emprestimos as $emp)
                        <tr><td>{{ $emp->membro->nome }}</td><td>{{ number_format($emp->valor_devido, 2) }}</td><td>{{ $emp->data_vencimento }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Nenhum empréstimo pendente</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection