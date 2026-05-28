@extends('layouts.app-tenant')
@section('title', 'Nova Contribuição')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-hand-holding-usd me-2 text-success"></i>Registar Nova Contribuição
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.contribuicoes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Grupo <span class="text-danger">*</span></label>
                        <select name="grupo_id" id="grupo_id" class="form-select" required>
                            <option value="">Seleccione o grupo...</option>
                            @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Membro <span class="text-danger">*</span></label>
                        <select name="membro_id" id="membro_id" class="form-select @error('membro_id') is-invalid @enderror" required>
                            <option value="">Seleccione o membro...</option>
                            @foreach($membros as $membro)
                            <option value="{{ $membro->id }}" data-grupo="{{ $membro->grupo_id }}">
                                {{ $membro->nome }} — {{ ucfirst($membro->cargo) }}
                            </option>
                            @endforeach
                        </select>
                        @error('membro_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo de Contribuição <span class="text-danger">*</span></label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="">Seleccione o tipo...</option>
                            <option value="poupanca" {{ old('tipo')=='poupanca' ? 'selected' : '' }}>💰 Poupança</option>
                            <option value="fundo_social" {{ old('tipo')=='fundo_social' ? 'selected' : '' }}>🤝 Fundo Social</option>
                            <option value="atraso" {{ old('tipo')=='atraso' ? 'selected' : '' }}>⚠️ Multa / Atraso</option>
                        </select>
                        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Valor (MT) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">MT</span>
                            <input type="number" name="valor" step="0.01" min="0.01"
                                class="form-control @error('valor') is-invalid @enderror"
                                value="{{ old('valor') }}" placeholder="0.00" required>
                        </div>
                        @error('valor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Data <span class="text-danger">*</span></label>
                        <input type="date" name="data" class="form-control @error('data') is-invalid @enderror"
                            value="{{ old('data', date('Y-m-d')) }}" required>
                        @error('data')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observação</label>
                        <textarea name="observacao" class="form-control" rows="2"
                            placeholder="Observação opcional...">{{ old('observacao') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-2"></i>Guardar
                        </button>
                        <a href="{{ route('tenant.contribuicoes.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Filtrar membros pelo grupo seleccionado
document.getElementById('grupo_id').addEventListener('change', function() {
    const grupoId = this.value;
    const membroSelect = document.getElementById('membro_id');
    const options = membroSelect.querySelectorAll('option[data-grupo]');
    options.forEach(opt => {
        opt.style.display = (!grupoId || opt.dataset.grupo === grupoId) ? '' : 'none';
    });
    membroSelect.value = '';
});
</script>
@endsection
