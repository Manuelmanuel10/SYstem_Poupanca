@extends('layouts.app-tenant')
@section('title', 'Grupos')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Lista de Grupos</h5>
    <a href="{{ route('tenant.grupos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Grupo
    </a>
</div>
<div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Nome</th><th>Taxa Juro</th><th>Fundo Social</th><th>Membros</th><th>Data Início</th><th>Estado</th><th>Acções</th></tr>
            </thead>
            <tbody>
                @forelse($grupos as $grupo)
                <tr>
                    <td class="fw-semibold">{{ $grupo->nome }}</td>
                    <td>{{ $grupo->taxa_juro }}%</td>
                    <td>{{ number_format($grupo->taxa_fundo_social, 2) }}</td>
                    <td><span class="badge bg-primary">{{ $grupo->membros_count }}</span></td>
                    <td>{{ $grupo->data_inicio }}</td>
                    <td><span class="badge bg-success">{{ $grupo->estado }}</span></td>
                    <td>
                        <a href="{{ route('tenant.grupos.show', $grupo) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('tenant.grupos.edit', $grupo) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('tenant.grupos.destroy', $grupo) }}" class="d-inline"
                            onsubmit="return confirm('Eliminar este grupo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Nenhum grupo criado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
