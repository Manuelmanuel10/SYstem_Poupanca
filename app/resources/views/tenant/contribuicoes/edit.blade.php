@extends('layouts.app-tenant')
@section('title', 'Editar Contribuição')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-edit me-2 text-warning"></i>Editar Contribuição
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.contribuicoes.update', $contribuico) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Membro</label>
                        <select name="membro_id" class="form-select" required>
                            @foreach($membros as $membro)
                            <option value="{{ $membro->id }}" {{ $contribuico->membro_id == $membro->id ? 'selected' : '' }}>
                                {{ $membro->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            <option value="poupanca" {{ $contribuico->tipo=='poupanca' ? 'selected' : '' }}>💰 Poupança</option>
                            <option value="fundo_social" {{ $contribuico->tipo=='fundo_social' ? 'selected' : '' }}>🤝 Fundo Social</option>
                            <option value="atraso" {{ $contribuico->tipo=='atraso' ? 'selected' : '' }}>⚠️ Multa / Atraso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Valor (MT)</label>
                        <div class="input-group">
                            <span class="input-group-text">MT</span>
                            <input type="number" name="valor" step="0.01" class="form-control" value="{{ $contribuico->valor }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Data</label>
                        <input type="date" name="data" class="form-control" value="{{ $contribuico->data }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Observação</label>
                        <textarea name="observacao" class="form-control" rows="2">{{ $contribuico->observacao }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i>Actualizar
                        </button>
                        <a href="{{ route('tenant.contribuicoes.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
