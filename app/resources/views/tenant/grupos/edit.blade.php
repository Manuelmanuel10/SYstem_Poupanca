@extends('layouts.app-tenant')
@section('title', 'Editar Grupo')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-edit me-2 text-warning"></i>Editar Grupo
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.grupos.update', $grupo) }}">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nome do Grupo</label>
                            <input type="text" name="nome" class="form-control" value="{{ $grupo->nome }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Data de Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="{{ $grupo->data_inicio }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Taxa de Juro (%)</label>
                            <div class="input-group">
                                <input type="number" name="taxa_juro" step="0.01" class="form-control" value="{{ $grupo->taxa_juro }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Fundo Social (MT/mês)</label>
                            <div class="input-group">
                                <span class="input-group-text">MT</span>
                                <input type="number" name="taxa_fundo_social" step="0.01" class="form-control" value="{{ $grupo->taxa_fundo_social }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Taxa de Atraso (MT)</label>
                            <div class="input-group">
                                <span class="input-group-text">MT</span>
                                <input type="number" name="taxa_atraso" step="0.01" class="form-control" value="{{ $grupo->taxa_atraso }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Datas de Encontro --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Datas de Encontro</label>
                        <p class="text-muted mb-2" style="font-size:12px">O acumulado só é calculado nos meses com encontro registado.</p>
                        <div id="encontros-container">
                            @forelse($grupo->datas_encontro ?? [] as $data)
                            <div class="input-group mb-2 encontro-row">
                                <input type="date" name="datas_encontro[]" class="form-control" value="{{ $data }}">
                                <button type="button" class="btn btn-outline-danger remover-encontro">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @empty
                            <div class="input-group mb-2 encontro-row">
                                <input type="date" name="datas_encontro[]" class="form-control">
                                <button type="button" class="btn btn-outline-danger remover-encontro">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforelse
                        </div>
                        <button type="button" id="adicionar-encontro" class="btn btn-outline-primary btn-sm mt-1">
                            <i class="fas fa-plus me-1"></i>Adicionar Data
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="ativo"     {{ $grupo->estado == 'ativo'     ? 'selected' : '' }}>Activo</option>
                            <option value="inativo"   {{ $grupo->estado == 'inativo'   ? 'selected' : '' }}>Inactivo</option>
                            <option value="encerrado" {{ $grupo->estado == 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i>Actualizar
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
