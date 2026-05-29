@extends('layouts.app-tenant')
@section('title', 'Livro-Caixa')
@section('content')

{{-- Totais --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background:#16a34a;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">TOTAL ENTRADAS</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalEntradas, 2) }} MT</h4>
                </div>
                <i class="fas fa-arrow-circle-down fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background:#dc2626;border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">TOTAL SAÍDAS</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalSaidas, 2) }} MT</h4>
                </div>
                <i class="fas fa-arrow-circle-up fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background:{{ $saldoFinal >= 0 ? '#2563a8' : '#7c2d12' }};border-radius:12px;border:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75" style="font-size:13px">SALDO ACTUAL</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($saldoFinal, 2) }} MT</h4>
                </div>
                <i class="fas fa-wallet fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('tenant.caixa.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1" style="font-size:12px">GRUPO</label>
                <select name="grupo_id" class="form-select form-select-sm">
                    <option value="">Todos os grupos</option>
                    @foreach($grupos as $g)
                    <option value="{{ $g->id }}" {{ $grupoFiltro == $g->id ? 'selected' : '' }}>{{ $g->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1" style="font-size:12px">TIPO</label>
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="entrada" {{ $tipoFiltro == 'entrada' ? 'selected' : '' }}>Entradas</option>
                    <option value="saida"   {{ $tipoFiltro == 'saida'   ? 'selected' : '' }}>Saídas</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1" style="font-size:12px">DE</label>
                <input type="date" name="data_inicio" class="form-control form-control-sm" value="{{ $dataInicio }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1" style="font-size:12px">ATÉ</label>
                <input type="date" name="data_fim" class="form-control form-control-sm" value="{{ $dataFim }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card" style="border-radius:12px;border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08)">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold">Movimentos do Período</span>
        <span class="text-muted" style="font-size:13px">{{ $movimentos->count() }} registos</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Grupo</th>
                    <th>Categoria</th>
                    <th class="text-end text-success">Entrada</th>
                    <th class="text-end text-danger">Saída</th>
                    <th class="text-end">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimentos as $m)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($m['data'])->format('d/m/Y') }}</td>
                    <td>{{ $m['descricao'] }}</td>
                    <td><span class="badge bg-light text-dark">{{ $m['grupo'] }}</span></td>
                    <td>
                        @php
                        $cats = [
                            'poupanca'             => ['Poupança',    'success'],
                            'fundo_social'         => ['Fundo Social','primary'],
                            'atraso'               => ['Multa',       'danger'],
                            'emprestimo'           => ['Empréstimo',  'warning'],
                            'pagamento_emprestimo' => ['Pgto. Emprést.','info'],
                        ];
                        $cat = $cats[$m['categoria']] ?? [$m['categoria'], 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $cat[1] }}">{{ $cat[0] }}</span>
                    </td>
                    <td class="text-end fw-semibold text-success">
                        {{ $m['entrada'] > 0 ? '+' . number_format($m['entrada'], 2) . ' MT' : '—' }}
                    </td>
                    <td class="text-end fw-semibold text-danger">
                        {{ $m['saida'] > 0 ? '-' . number_format($m['saida'], 2) . ' MT' : '—' }}
                    </td>
                    <td class="text-end fw-bold" style="color:{{ $m['saldo'] >= 0 ? '#16a34a' : '#dc2626' }}">
                        {{ number_format($m['saldo'], 2) }} MT
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x d-block mb-2 opacity-25"></i>
                        Nenhum movimento no período seleccionado.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($movimentos->count() > 0)
            <tfoot class="table-light">
                <tr>
                    <td colspan="4" class="fw-bold text-end">Totais:</td>
                    <td class="text-end fw-bold text-success">+{{ number_format($totalEntradas, 2) }} MT</td>
                    <td class="text-end fw-bold text-danger">-{{ number_format($totalSaidas, 2) }} MT</td>
                    <td class="text-end fw-bold" style="color:{{ $saldoFinal >= 0 ? '#16a34a' : '#dc2626' }}">
                        {{ number_format($saldoFinal, 2) }} MT
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
