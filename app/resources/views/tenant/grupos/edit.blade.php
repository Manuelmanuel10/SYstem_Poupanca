@extends('layouts.app-tenant')
@section('title', 'Editar Grupo')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-edit me-2 text-warning"></i>Editar Grupo</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.grupos.update', $grupo) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome do Grupo</label>
                        <input type="text" name="nome" class="form-control" value="{{ $grupo->nome }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Taxa de Juro (%)</label>
                            <input type="number" name="taxa_juro" step="0.01" class="form-control" value="{{ $grupo->taxa_juro }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Taxa de Atraso</label>
                            <input type="number" name="taxa_atraso" step="0.01" class="form-control" value="{{ $grupo->taxa_atraso }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fundo Social</label>
                            <input type="number" name="taxa_fundo_social" step="0.01" class="form-control" value="{{ $grupo->taxa_fundo_social }}" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data de Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="{{ $grupo->data_inicio }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="ativo" {{ $grupo->estado == 'ativo' ? 'selected' : '' }}>Activo</option>
                                <option value="encerrado" {{ $grupo->estado == 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i>Actualizar
                        </button>
                        <a href="{{ route('tenant.grupos.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
