@extends('layouts.app-tenant')
@section('title', 'Novo Empréstimo')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-money-bill-wave me-2 text-primary"></i>Registar Novo Empréstimo
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.emprestimos.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Grupo <span class="text-danger">*</span></label>
                            <select name="grupo_id" id="grupo_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}" data-juro="{{ $grupo->taxa_juro }}">{{ $grupo->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Membro <span class="text-danger">*</span></label>
                            <select name="membro_id" id="membro_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($membros as $membro)
                                <option value="{{ $membro->id }}" data-grupo="{{ $membro->grupo_id }}">{{ $membro->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Valor Principal (MT) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">MT</span>
                                <input type="number" name="valor_principal" id="valor_principal" step="0.01" min="1"
                                    class="form-control" placeholder="0.00" required oninput="calcular()">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Taxa de Juro Mensal (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="taxa_juro" id="taxa_juro" step="0.01" min="0"
                                    class="form-control" placeholder="Ex: 20" required oninput="calcular()">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Data do Empréstimo <span class="text-danger">*</span></label>
                            <input type="date" name="data_emprestimo" id="data_emprestimo"
                                class="form-control" value="{{ date('Y-m-d') }}" required onchange="calcular()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Data de Vencimento <span class="text-danger">*</span></label>
                            <input type="date" name="data_vencimento" id="data_vencimento"
                                class="form-control" required onchange="calcular()">
                        </div>
                    </div>

                    {{-- Calculadora automática --}}
                    <div id="resultado" class="alert alert-info d-none mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <small class="text-muted d-block">Principal</small>
                                <strong id="r_principal">0.00 MT</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Juros (<span id="r_meses">0</span> meses)</small>
                                <strong id="r_juros" class="text-danger">0.00 MT</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Total a Pagar</small>
                                <strong id="r_total" class="text-primary fs-5">0.00 MT</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Guardar Empréstimo
                        </button>
                        <a href="{{ route('tenant.emprestimos.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('grupo_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const juro = opt.dataset.juro || '';
    document.getElementById('taxa_juro').value = juro;
    const grupoId = this.value;
    document.querySelectorAll('#membro_id option[data-grupo]').forEach(o => {
        o.style.display = (!grupoId || o.dataset.grupo === grupoId) ? '' : 'none';
    });
    document.getElementById('membro_id').value = '';
    calcular();
});

function calcular() {
    const principal = parseFloat(document.getElementById('valor_principal').value) || 0;
    const taxa      = parseFloat(document.getElementById('taxa_juro').value) / 100 || 0;
    const d1        = document.getElementById('data_emprestimo').value;
    const d2        = document.getElementById('data_vencimento').value;

    if (!principal || !taxa || !d1 || !d2) return;

    const inicio = new Date(d1);
    const fim    = new Date(d2);
    let meses = (fim.getFullYear() - inicio.getFullYear()) * 12 + (fim.getMonth() - inicio.getMonth());
    meses = Math.max(1, meses);

    const total  = principal * Math.pow(1 + taxa, meses);
    const juros  = total - principal;

    document.getElementById('r_principal').textContent = principal.toFixed(2) + ' MT';
    document.getElementById('r_juros').textContent     = juros.toFixed(2) + ' MT';
    document.getElementById('r_total').textContent     = total.toFixed(2) + ' MT';
    document.getElementById('r_meses').textContent     = meses;
    document.getElementById('resultado').classList.remove('d-none');
}
</script>
@endsection
