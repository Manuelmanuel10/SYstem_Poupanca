@extends('layouts.app-tenant')
@section('title', 'Editar Membro')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-user-edit me-2 text-warning"></i>Editar Membro
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.membros.update', $membro) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Grupo</label>
                        <select name="grupo_id" class="form-select" required>
                            @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ $membro->grupo_id == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" value="{{ $membro->nome }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Telefone</label>
                        <input type="text" name="telefone" class="form-control" value="{{ $membro->telefone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cargo</label>
                        <select name="cargo" class="form-select" required>
                            @foreach(['presidente','vice','secretario','tesoureiro','guardiao','membro'] as $c)
                            <option value="{{ $c }}" {{ $membro->cargo == $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="ativo" {{ $membro->estado == 'ativo' ? 'selected' : '' }}>Activo</option>
                            <option value="inativo" {{ $membro->estado == 'inativo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i>Actualizar
                        </button>
                        <a href="{{ route('tenant.membros.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
