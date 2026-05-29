@extends('layouts.app-tenant')
@section('title', 'Grupos')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Lista de Grupos</h5>
    <a href="{{ route('tenant.grupos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Grupo
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card stat-card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Taxa Juro</th>
                    <th>Fundo Social</th>
                    <th>Membros</th>
                    <th>Data Início</th>
                    <th>Estado</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grupos as $grupo)
                <tr>
                    <td class="fw-semibold">{{ $grupo->nome }}</td>
                    <td>{{ $grupo->taxa_juro }}%</td>
                    <td>{{ number_format($grupo->taxa_fundo_social, 2) }}</td>
                    <td><span class="badge bg-primary">{{ $grupo->membros_count }}</span></td>
                    <td>{{ $grupo->data_inicio }}</td>
                    <td>
                        @if($grupo->estado === 'ativo')
                            <span class="badge bg-success">Ativo</span>
                        @elseif($grupo->estado === 'encerrado')
                            <span class="badge bg-secondary">Encerrado</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ ucfirst($grupo->estado) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tenant.grupos.show', $grupo) }}"
                           class="btn btn-sm btn-outline-info" title="Ver detalhes">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if($grupo->estado === 'ativo')
                        <a href="{{ route('tenant.grupos.edit', $grupo) }}"
                           class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        {{-- Módulo 7: Encerrar grupo --}}
                        <a href="{{ route('tenant.grupos.encerrar', $grupo) }}"
                           class="btn btn-sm btn-outline-danger" title="Encerrar e calcular divisão final">
                            <i class="fas fa-flag-checkered"></i>
                        </a>
                        @endif

                        @if($grupo->estado !== 'encerrado')
                        <form method="POST" action="{{ route('tenant.grupos.destroy', $grupo) }}"
                              class="d-inline"
                              onsubmit="return confirm('Eliminar este grupo permanentemente?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Nenhum grupo criado ainda.
                        <a href="{{ route('tenant.grupos.create') }}" class="ms-2 text-primary">Criar agora</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-2 text-muted small">
    <i class="fas fa-flag-checkered me-1"></i>
    O botão <strong>vermelho com bandeira</strong> inicia o processo de encerramento e cálculo da divisão final.
</div>
@endsection
