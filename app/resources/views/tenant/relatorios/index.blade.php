@extends('layouts.app-tenant')
@section('title', 'Relatórios em PDF')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-file-pdf text-danger me-2"></i>Relatórios em PDF</h4>
        <p class="text-muted small mb-0">Extractos por grupo ou por membro individual</p>
    </div>
</div>

@forelse($grupos as $grupo)
<div class="card stat-card mb-4">
    {{-- Cabeçalho do grupo --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <div>
            <span class="fw-bold">{{ $grupo->nome }}</span>
            <span class="ms-2 badge {{ $grupo->estado === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($grupo->estado) }}
            </span>
            <span class="ms-2 text-muted small">{{ $grupo->membros_count }} membro(s)</span>
        </div>
        {{-- Botão: extracto do grupo inteiro --}}
        <a href="{{ route('tenant.relatorios.grupo', $grupo) }}"
           class="btn btn-sm btn-outline-danger"
           title="Descarregar extracto completo do grupo">
            <i class="fas fa-download me-1"></i> Extracto do grupo
        </a>
    </div>

    {{-- Lista de membros do grupo --}}
    <div class="card-body p-0">
        @if($grupo->membros->isEmpty())
            <p class="text-muted text-center py-3">Nenhum membro neste grupo.</p>
        @else
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Membro</th>
                    <th>Cargo</th>
                    <th>Estado</th>
                    <th class="text-end">Extracto individual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupo->membros as $membro)
                <tr>
                    <td>{{ $membro->nome }}</td>
                    <td>{{ ucfirst($membro->cargo ?? '—') }}</td>
                    <td>
                        <span class="badge {{ $membro->estado === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($membro->estado) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('tenant.relatorios.membro', $membro) }}"
                           class="btn btn-sm btn-outline-secondary"
                           title="Descarregar extracto de {{ $membro->nome }}">
                            <i class="fas fa-user-circle me-1"></i> PDF
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@empty
<div class="card stat-card">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
        <p>Nenhum grupo criado ainda.</p>
        <a href="{{ route('tenant.grupos.create') }}" class="btn btn-primary btn-sm">Criar grupo</a>
    </div>
</div>
@endforelse

<div class="row g-4 mt-2">
    <div class="col-md-6">
        <div class="card h-100" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <div style="width:60px;height:60px;background:#fce7f3;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto">
                        <i class="fas fa-chart-line fa-2x" style="color:#9d174d"></i>
                    </div>
                    <h5 class="mt-3 fw-bold">Evolução do Grupo</h5>
                    <p class="text-muted" style="font-size:13px">Tabela mensal de poupança de todos os membros com juros e fundo social</p>
                </div>
                <form method="POST" action="{{ route('tenant.relatorios.evolucao.grupo') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Grupo</label>
                        <select name="grupo_id" class="form-select form-select-sm" required>
                            <option value="">Seleccione...</option>
                            @foreach($grupos as $g)
                            <option value="{{ $g->id }}">{{ $g->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px">Ano</label>
                        <input type="number" name="ano" class="form-control form-control-sm" value="{{ date('Y') }}" min="2020" max="2099" required>
                    </div>
                    <button type="submit" class="btn w-100" style="background:#9d174d;color:white">
                        <i class="fas fa-file-pdf me-2"></i>Gerar PDF do Grupo
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <div style="width:60px;height:60px;background:#ede9fe;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto">
                        <i class="fas fa-user-chart fa-2x" style="color:#6d28d9"></i>
                    </div>
                    <h5 class="mt-3 fw-bold">Evolução do Membro</h5>
                    <p class="text-muted" style="font-size:13px">Extracto pessoal com evolução mensal, juros e histórico completo</p>
                </div>
                <form method="POST" action="{{ route('tenant.relatorios.evolucao.membro') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Membro</label>
                        <select name="membro_id" class="form-select form-select-sm" required>
                            <option value="">Seleccione...</option>
                            @foreach($grupos as $g)
                                @foreach($g->membros as $m)
                                <option value="{{ $m->id }}">{{ $m->nome }} ({{ $g->nome }})</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px">Ano</label>
                        <input type="number" name="ano" class="form-control form-control-sm" value="{{ date('Y') }}" min="2020" max="2099" required>
                    </div>
                    <button type="submit" class="btn w-100" style="background:#6d28d9;color:white">
                        <i class="fas fa-file-pdf me-2"></i>Gerar Extracto Pessoal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
