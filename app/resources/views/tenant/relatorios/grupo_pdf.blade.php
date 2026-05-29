<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }

  .header { background: #1e40af; color: #fff; padding: 18px 24px; margin-bottom: 20px; }
  .header h1 { font-size: 16px; font-weight: bold; }
  .header p  { font-size: 9px; opacity: .8; margin-top: 2px; }

  .section-title {
    font-size: 11px; font-weight: bold; color: #1e40af;
    border-bottom: 1px solid #bfdbfe; padding-bottom: 4px; margin: 16px 0 8px;
  }

  table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
  th { background: #eff6ff; color: #1e40af; text-align: left; padding: 5px 7px; font-size: 9px; }
  td { padding: 4px 7px; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
  tr:last-child td { border-bottom: none; }

  .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px 14px; margin-bottom: 12px; }
  .summary-row { display: flex; justify-content: space-between; padding: 2px 0; }
  .summary-label { color: #64748b; }
  .summary-value { font-weight: bold; }
  .total-row { border-top: 1px solid #cbd5e1; margin-top: 5px; padding-top: 5px; font-size: 11px; }

  .badge-ativo    { background: #dcfce7; color: #166534; padding: 1px 6px; border-radius: 3px; }
  .badge-pendente { background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 3px; }
  .badge-pago     { background: #dcfce7; color: #166534; padding: 1px 6px; border-radius: 3px; }
  .badge-atrasado { background: #fee2e2; color: #991b1b; padding: 1px 6px; border-radius: 3px; }

  .footer { margin-top: 30px; font-size: 8px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 8px; }
  .text-right { text-align: right; }
  .text-bold { font-weight: bold; }
  .highlight { background: #eff6ff; font-weight: bold; }
</style>
</head>
<body>

<div class="header">
  <h1>📊 Extracto do Grupo — {{ $grupo->nome }}</h1>
  <p>Gerado em {{ $geradoEm }} &nbsp;|&nbsp; Gestor: {{ $nomeGestor }}</p>
</div>

{{-- Informações do grupo --}}
<div class="section-title">Informações do Grupo</div>
<div class="summary-box">
  <div class="summary-row"><span class="summary-label">Nome</span><span class="summary-value">{{ $grupo->nome }}</span></div>
  <div class="summary-row"><span class="summary-label">Estado</span><span class="summary-value">{{ ucfirst($grupo->estado) }}</span></div>
  <div class="summary-row"><span class="summary-label">Data de início</span><span class="summary-value">{{ \Carbon\Carbon::parse($grupo->data_inicio)->format('d/m/Y') }}</span></div>
  @if($grupo->data_fim)
  <div class="summary-row"><span class="summary-label">Data de encerramento</span><span class="summary-value">{{ \Carbon\Carbon::parse($grupo->data_fim)->format('d/m/Y') }}</span></div>
  @endif
  <div class="summary-row"><span class="summary-label">Taxa de juro</span><span class="summary-value">{{ $grupo->taxa_juro }}%</span></div>
  <div class="summary-row"><span class="summary-label">Taxa de atraso</span><span class="summary-value">{{ $grupo->taxa_atraso }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Fundo social</span><span class="summary-value">{{ $grupo->taxa_fundo_social }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Nº de membros</span><span class="summary-value">{{ $grupo->membros->count() }}</span></div>
</div>

{{-- Resumo financeiro --}}
<div class="section-title">Resumo Financeiro</div>
<div class="summary-box">
  <div class="summary-row"><span class="summary-label">Total Poupança</span><span class="summary-value">{{ number_format($totais['poupanca'], 2) }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Total Fundo Social</span><span class="summary-value">{{ number_format($totais['fundo_social'], 2) }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Total Multas / Atraso</span><span class="summary-value">{{ number_format($totais['atraso'], 2) }} MZN</span></div>
  <div class="summary-row total-row">
    <span class="summary-label">TOTAL CONTRIBUÍDO</span>
    <span class="summary-value">{{ number_format(array_sum($totais), 2) }} MZN</span>
  </div>
</div>

<div class="summary-box">
  <div class="summary-row"><span class="summary-label">Empréstimos Pagos (valor total)</span><span class="summary-value">{{ number_format($emprestimosResumo['pago'], 2) }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Empréstimos Pendentes</span><span class="summary-value">{{ number_format($emprestimosResumo['pendente'], 2) }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Empréstimos Atrasados</span><span class="summary-value">{{ number_format($emprestimosResumo['atrasado'], 2) }} MZN</span></div>
</div>

{{-- Membros --}}
<div class="section-title">Membros</div>
<table>
  <thead><tr><th>#</th><th>Nome</th><th>Telefone</th><th>Cargo</th><th>Estado</th></tr></thead>
  <tbody>
  @foreach($grupo->membros as $i => $membro)
  <tr>
    <td>{{ $i + 1 }}</td>
    <td>{{ $membro->nome }}</td>
    <td>{{ $membro->telefone ?? '—' }}</td>
    <td>{{ ucfirst($membro->cargo ?? '—') }}</td>
    <td><span class="badge-{{ $membro->estado }}">{{ ucfirst($membro->estado) }}</span></td>
  </tr>
  @endforeach
  </tbody>
</table>

{{-- Contribuições --}}
<div class="section-title">Contribuições</div>
<table>
  <thead><tr><th>Data</th><th>Membro</th><th>Tipo</th><th class="text-right">Valor (MZN)</th><th>Observação</th></tr></thead>
  <tbody>
  @forelse($grupo->contribuicoes->sortByDesc('data') as $c)
  <tr>
    <td>{{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}</td>
    <td>{{ $c->membro->nome ?? '—' }}</td>
    <td>{{ ucfirst(str_replace('_', ' ', $c->tipo)) }}</td>
    <td class="text-right">{{ number_format($c->valor, 2) }}</td>
    <td>{{ $c->observacao ?? '' }}</td>
  </tr>
  @empty
  <tr><td colspan="5" style="text-align:center;color:#94a3b8;">Sem contribuições registadas.</td></tr>
  @endforelse
  </tbody>
</table>

{{-- Empréstimos --}}
<div class="section-title">Empréstimos</div>
<table>
  <thead><tr><th>Membro</th><th>Principal</th><th>Taxa</th><th>Valor Devido</th><th>Vencimento</th><th>Estado</th></tr></thead>
  <tbody>
  @forelse($grupo->emprestimos as $e)
  <tr>
    <td>{{ $e->membro->nome ?? '—' }}</td>
    <td class="text-right">{{ number_format($e->valor_principal, 2) }}</td>
    <td>{{ $e->taxa_juro }}%</td>
    <td class="text-right text-bold">{{ number_format($e->valor_devido, 2) }}</td>
    <td>{{ \Carbon\Carbon::parse($e->data_vencimento)->format('d/m/Y') }}</td>
    <td><span class="badge-{{ $e->estado }}">{{ ucfirst($e->estado) }}</span></td>
  </tr>
  @empty
  <tr><td colspan="6" style="text-align:center;color:#94a3b8;">Sem empréstimos registados.</td></tr>
  @endforelse
  </tbody>
</table>

<div class="footer">
  Sistema de Poupança SaaS &nbsp;|&nbsp; Documento gerado automaticamente &nbsp;|&nbsp; {{ $geradoEm }}
</div>

</body>
</html>
