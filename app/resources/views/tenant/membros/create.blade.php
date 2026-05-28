@extends('layouts.app-tenant')
@section('title', 'Novo Membro')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-user-plus me-2 text-primary"></i>Adicionar Novo Membro
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.membros.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Grupo <span class="text-danger">*</span></label>
                        <select name="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror" required>
                            <option value="">Seleccione o grupo...</option>
                            @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nome }}
                            </option>
                            @endforeach
                        </select>
                        @error('grupo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome Completo <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                            value="{{ old('nome') }}" placeholder="Ex: João Manuel Silva" required>
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Telefone</label>
                        <input type="text" name="telefone" class="form-control"
                            value="{{ old('telefone') }}" placeholder="Ex: +258 84 000 0000">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Cargo <span class="text-danger">*</span></label>
                        <select name="cargo" class="form-select @error('cargo') is-invalid @enderror" required>
                            <option value="">Seleccione o cargo...</option>
                            <option value="presidente" {{ old('cargo')=='presidente' ? 'selected' : '' }}>Presidente</option>
                            <option value="vice" {{ old('cargo')=='vice' ? 'selected' : '' }}>Vice-Presidente</option>
                            <option value="secretario" {{ old('cargo')=='secretario' ? 'selected' : '' }}>Secretário</option>
                            <option value="tesoureiro" {{ old('cargo')=='tesoureiro' ? 'selected' : '' }}>Tesoureiro</option>
                            <option value="guardiao" {{ old('cargo')=='guardiao' ? 'selected' : '' }}>Guardião</option>
                            <option value="membro" {{ old('cargo')=='membro' ? 'selected' : '' }}>Membro</option>
                        </select>
                        @error('cargo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Guardar
                        </button>
                        <a href="{{ route('tenant.membros.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
