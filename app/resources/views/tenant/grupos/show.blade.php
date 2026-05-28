@extends('layouts.app-tenant')
@section('title', $grupo->nome)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">{{ $grupo->nome }}</h5>
        <span class="badge {{ $grupo->estado == 'ativo' ? 'bg-success' : 'bg-secondary' }}">{{ $grupo->estado }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('tenant.membros.create') }}?grupo_id={{ $grupo->id }}" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus me-1"></i>Adicionar Membro
        </a>
        <a href="{{ route('tenant.grupos.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <p class="text-muted mb-1" style="font-size:12px;">MEMBROS</p>
            <h4 class="fw-bold text-primary mb-0">{{ $grupo->membros->count() }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <p class="text-muted mb-1" style="font-size:12px;">JURO MENSAL</p>
            <h4 class="fw-bold text-success mb-0">{{ $grupo->taxa_juro }}%</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <p class="text-muted mb-1" style="font-size:12px;">FUNDO SOCIAL</p>
            <h4 class="fw-bold text-warning mb-0">{{ number_format($grupo->taxa_fundo_social, 2) }} MT</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <p class="text-muted mb-1" style="font-size:12px;">TAXA ATRASO</p>
            <h4 class="fw-bold text-danger mb-0">{{ number_format($grupo->taxa_atraso, 2) }} MT</h4>
        </div>
    </div>
</div>
<div class="card stat-card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>Membros do Grupo</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Nome</th><th>Cargo</th><th>Telefone</th><th>Estado</th><th>Acções</th></tr>
            </thead>
            <tbody>
                @forelse($grupo->membros as $membro)
                <tr>
                    <td class="fw-semibold">{{ $membro->nome }}</td>
                    <td><span class="badge bg-info text-dark">{{ $membro->cargo }}</span></td>
                    <td>{{ $membro->telefone ?? '-' }}</td>
                    <td><span class="badge {{ $membro->estado == 'ativo' ? 'bg-success' : 'bg-secondary' }}">{{ $membro->estado }}</span></td>
                    <td>
                        <a href="{{ route('tenant.membros.show', $membro) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Nenhum membro adicionado</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
