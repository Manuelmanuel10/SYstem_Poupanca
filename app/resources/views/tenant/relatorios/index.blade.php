@extends('layouts.app-tenant')
@section('title', 'Relatórios em PDF')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-file-pdf text-danger me-2"></i>Relatórios em PDF</h4>
        <p class="text-muted small mb-0">Extractos por grupo ou por membro individual</p>
    </div>
</div>

@forelse($grupos as $grupo)
<div class="card stat-card mb-4">
    {{-- Cabeçalho do grupo --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <div>
            <span class="fw-bold">{{ $grupo->nome }}</span>
            <span class="ms-2 badge {{ $grupo->estado === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($grupo->estado) }}
            </span>
            <span class="ms-2 text-muted small">{{ $grupo->membros_count }} membro(s)</span>
        </div>
        {{-- Botão: extracto do grupo inteiro --}}
        <a href="{{ route('tenant.relatorios.grupo', $grupo) }}"
           class="btn btn-sm btn-outline-danger"
           title="Descarregar extracto completo do grupo">
            <i class="fas fa-download me-1"></i> Extracto do grupo
        </a>
    </div>

    {{-- Lista de membros do grupo --}}
    <div class="card-body p-0">
        @if($grupo->membros->isEmpty())
            <p class="text-muted text-center py-3">Nenhum membro neste grupo.</p>
        @else
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Membro</th>
                    <th>Cargo</th>
                    <th>Estado</th>
                    <th class="text-end">Extracto individual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupo->membros as $membro)
                <tr>
                    <td>{{ $membro->nome }}</td>
                    <td>{{ ucfirst($membro->cargo ?? '—') }}</td>
                    <td>
                        <span class="badge {{ $membro->estado === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($membro->estado) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('tenant.relatorios.membro', $membro) }}"
                           class="btn btn-sm btn-outline-secondary"
                           title="Descarregar extracto de {{ $membro->nome }}">
                            <i class="fas fa-user-circle me-1"></i> PDF
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@empty
<div class="card stat-card">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
        <p>Nenhum grupo criado ainda.</p>
        <a href="{{ route('tenant.grupos.create') }}" class="btn btn-primary btn-sm">Criar grupo</a>
    </div>
</div>
@endforelse
@endsection
