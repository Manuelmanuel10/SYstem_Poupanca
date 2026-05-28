@extends('layouts.app-tenant')
@section('title', 'Novo Grupo')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>Criar Novo Grupo</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.grupos.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome do Grupo</label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                            placeholder="Ex: Grupo Esperança 2026" value="{{ old('nome') }}" required>
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Taxa de Juro (%/mês)</label>
                            <input type="number" name="taxa_juro" step="0.01" class="form-control"
                                value="{{ old('taxa_juro', 20) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Taxa de Atraso (MT)</label>
                            <input type="number" name="taxa_atraso" step="0.01" class="form-control"
                                value="{{ old('taxa_atraso', 50) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fundo Social (MT)</label>
                            <input type="number" name="taxa_fundo_social" step="0.01" class="form-control"
                                value="{{ old('taxa_fundo_social', 100) }}" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Data de Início</label>
                        <input type="date" name="data_inicio" class="form-control"
                            value="{{ old('data_inicio', date('Y-m-d')) }}" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Criar Grupo
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
