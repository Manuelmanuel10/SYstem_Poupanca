@extends('layouts.app-tenant')
@section('title', 'Minha Subscrição')
@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-crown me-2 text-warning"></i>Plano Actual
            </div>
            <div class="card-body p-4 text-center">
                @if($tenant)
                    <div class="mb-3">
                        @if($tenant->plano == 'premium')
                            <span class="badge bg-warning text-dark fs-6 px-4 py-2">⭐ Premium</span>
                        @elseif($tenant->plano == 'standard')
                            <span class="badge bg-primary fs-6 px-4 py-2">Standard</span>
                        @else
                            <span class="badge bg-secondary fs-6 px-4 py-2">Básico</span>
                        @endif
                    </div>
                    <p class="mb-1"><strong>Estado:</strong>
                        <span class="badge bg-{{ $tenant->estado == 'ativo' ? 'success' : 'danger' }}">
                            {{ ucfirst($tenant->estado) }}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Expira em:</strong>
                        {{ $tenant->data_expiracao ? \Carbon\Carbon::parse($tenant->data_expiracao)->format('d/m/Y') : 'N/A' }}
                    </p>
                    @if($tenant->data_expiracao && \Carbon\Carbon::parse($tenant->data_expiracao)->diffInDays(now()) <= 7)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Subscrição expira em breve! Renove agora.
                        </div>
                    @endif
                @else
                    <p class="text-muted">Nenhuma subscrição activa.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-sync me-2 text-primary"></i>Renovar / Mudar Plano
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tenant.onboarding.renovar') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Seleccione o plano</label>
                        <div class="row g-3">
                            @foreach([
                                'basico'   => ['label'=>'Básico',   'preco'=>'500 MT/mês',   'cor'=>'secondary'],
                                'standard' => ['label'=>'Standard', 'preco'=>'1.000 MT/mês', 'cor'=>'primary'],
                                'premium'  => ['label'=>'Premium',  'preco'=>'2.000 MT/mês', 'cor'=>'warning'],
                            ] as $key => $p)
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="plano" id="p_{{ $key }}"
                                    value="{{ $key }}" {{ $tenant?->plano == $key ? 'checked' : '' }}>
                                <label class="btn btn-outline-{{ $p['cor'] }} w-100 py-3" for="p_{{ $key }}">
                                    <strong>{{ $p['label'] }}</strong><br>
                                    <small>{{ $p['preco'] }}</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-sync me-2"></i>Confirmar Renovação
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-history me-2 text-secondary"></i>Histórico de Subscrições
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Plano</th><th>Valor</th><th>Início</th><th>Fim</th><th>Estado</th></tr>
                    </thead>
                    <tbody>
                        @forelse($subscricoes as $s)
                        <tr>
                            <td>{{ ucfirst($s->plano) }}</td>
                            <td>{{ number_format($s->valor, 2) }} MT</td>
                            <td>{{ \Carbon\Carbon::parse($s->data_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->data_fim)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-{{ $s->estado == 'ativo' ? 'success' : 'secondary' }}">{{ $s->estado }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sem histórico.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
