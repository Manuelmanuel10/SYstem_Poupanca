@extends('layouts.app-tenant')
@section('title', 'Membros')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Lista de Membros</h5>
    <a href="{{ route('tenant.membros.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Membro
    </a>
</div>
<div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Nome</th><th>Grupo</th><th>Cargo</th><th>Telefone</th><th>Estado</th><th>Acções</th></tr>
            </thead>
            <tbody>
                @forelse($membros as $membro)
                <tr>
                    <td class="fw-semibold">{{ $membro->nome }}</td>
                    <td>{{ $membro->grupo->nome }}</td>
                    <td>
                        @php
                        $cores = ['presidente'=>'danger','secretario'=>'warning','tesoureiro'=>'success','guardiao'=>'info','vice'=>'secondary','membro'=>'primary'];
                        $cor = $cores[$membro->cargo] ?? 'primary';
                        @endphp
                        <span class="badge bg-{{ $cor }}">{{ ucfirst($membro->cargo) }}</span>
                    </td>
                    <td>{{ $membro->telefone ?? '-' }}</td>
                    <td><span class="badge bg-{{ $membro->estado == 'ativo' ? 'success' : 'secondary' }}">{{ $membro->estado }}</span></td>
                    <td>
                        <a href="{{ route('tenant.membros.edit', $membro) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('tenant.membros.destroy', $membro) }}" class="d-inline"
                            onsubmit="return confirm('Eliminar este membro?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhum membro adicionado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
