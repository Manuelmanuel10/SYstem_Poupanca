@extends('layouts.app-tenant')
@section('title', 'Novo Grupo')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-users me-2 text-primary"></i>Criar Novo Grupo
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.grupos.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nome do Grupo <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome') }}" placeholder="Ex: Grupo Esperança" required>
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Data de Início <span class="text-danger">*</span></label>
                            <input type="date" name="data_inicio" class="form-control @error('data_inicio') is-invalid @enderror"
                                value="{{ old('data_inicio', date('Y-m-d')) }}" required>
                            @error('data_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Taxa de Juro Mensal (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="taxa_juro" step="0.01" min="0"
                                    class="form-control @error('taxa_juro') is-invalid @enderror"
                                    value="{{ old('taxa_juro', 10) }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('taxa_juro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Fundo Social Mensal (MT) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">MT</span>
                                <input type="number" name="taxa_fundo_social" step="0.01" min="0"
                                    class="form-control @error('taxa_fundo_social') is-invalid @enderror"
                                    value="{{ old('taxa_fundo_social', 100) }}" required>
                            </div>
                            @error('taxa_fundo_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Taxa de Atraso/Multa (MT) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">MT</span>
                                <input type="number" name="taxa_atraso" step="0.01" min="0"
                                    class="form-control @error('taxa_atraso') is-invalid @enderror"
                                    value="{{ old('taxa_atraso', 50) }}" required>
                            </div>
                            @error('taxa_atraso')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Datas de Encontro --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Datas de Encontro</label>
                        <p class="text-muted mb-2" style="font-size:12px">
                            Adicione as datas dos encontros mensais. O acumulado de poupança só será calculado nos meses com encontro registado.
                        </p>
                        <div id="encontros-container">
                            <div class="input-group mb-2 encontro-row">
                                <input type="date" name="datas_encontro[]" class="form-control">
                                <button type="button" class="btn btn-outline-danger remover-encontro">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="adicionar-encontro" class="btn btn-outline-primary btn-sm mt-1">
                            <i class="fas fa-plus me-1"></i>Adicionar Data de Encontro
                        </button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Criar Grupo
                        </button>
                        <a href="{{ route('tenant.grupos.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('adicionar-encontro').addEventListener('click', function() {
    const div = document.createElement('div');
    div.className = 'input-group mb-2 encontro-row';
    div.innerHTML = '<input type="date" name="datas_encontro[]" class="form-control"><button type="button" class="btn btn-outline-danger remover-encontro"><i class="fas fa-times"></i></button>';
    document.getElementById('encontros-container').appendChild(div);
    div.querySelector('.remover-encontro').addEventListener('click', function() { div.remove(); });
});
document.querySelectorAll('.remover-encontro').forEach(btn => {
    btn.addEventListener('click', function() { this.closest('.encontro-row').remove(); });
});
</script>
@endsection
