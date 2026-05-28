@extends('layouts.app-tenant')
@section('title', 'Actualizar Empréstimo')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-edit me-2 text-warning"></i>Actualizar Estado do Empréstimo
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <small class="text-muted">Membro</small>
                        <p class="fw-bold mb-0">{{ $emprestimo->membro->nome }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Grupo</small>
                        <p class="fw-bold mb-0">{{ $emprestimo->grupo->nome }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Principal</small>
                        <p class="fw-bold mb-0">{{ number_format($emprestimo->valor_principal, 2) }} MT</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Total Devido</small>
                        <p class="fw-bold text-danger mb-0">{{ number_format($emprestimo->valor_devido, 2) }} MT</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Taxa de Juro</small>
                        <p class="fw-bold mb-0">{{ $emprestimo->taxa_juro }}% / mês</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Vencimento</small>
                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($emprestimo->data_vencimento)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <hr>
                <form method="POST" action="{{ route('tenant.emprestimos.update', $emprestimo) }}">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Estado do Empréstimo</label>
                        <select name="estado" class="form-select form-select-lg" required>
                            <option value="pendente" {{ $emprestimo->estado=='pendente' ? 'selected' : '' }}>⏳ Pendente</option>
                            <option value="pago" {{ $emprestimo->estado=='pago' ? 'selected' : '' }}>✅ Pago</option>
                            <option value="atrasado" {{ $emprestimo->estado=='atrasado' ? 'selected' : '' }}>❌ Atrasado</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i>Actualizar
                        </button>
                        <a href="{{ route('tenant.emprestimos.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
