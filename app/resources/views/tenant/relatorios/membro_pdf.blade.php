<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }

  .header { background: #065f46; color: #fff; padding: 18px 24px; margin-bottom: 20px; }
  .header h1 { font-size: 16px; font-weight: bold; }
  .header p  { font-size: 9px; opacity: .8; margin-top: 2px; }

  .section-title {
    font-size: 11px; font-weight: bold; color: #065f46;
    border-bottom: 1px solid #6ee7b7; padding-bottom: 4px; margin: 16px 0 8px;
  }

  table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
  th { background: #ecfdf5; color: #065f46; text-align: left; padding: 5px 7px; font-size: 9px; }
  td { padding: 4px 7px; border-bottom: 1px solid #f0f0f0; }
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
</style>
</head>
<body>

<div class="header">
  <h1>👤 Extracto Individual — {{ $membro->nome }}</h1>
  <p>Grupo: {{ $membro->grupo->nome ?? '—' }} &nbsp;|&nbsp; Gerado em {{ $geradoEm }} &nbsp;|&nbsp; Gestor: {{ $nomeGestor }}</p>
</div>

{{-- Dados do membro --}}
<div class="section-title">Dados do Membro</div>
<div class="summary-box">
  <div class="summary-row"><span class="summary-label">Nome</span><span class="summary-value">{{ $membro->nome }}</span></div>
  <div class="summary-row"><span class="summary-label">Telefone</span><span class="summary-value">{{ $membro->telefone ?? '—' }}</span></div>
  <div class="summary-row"><span class="summary-label">Cargo</span><span class="summary-value">{{ ucfirst($membro->cargo ?? '—') }}</span></div>
  <div class="summary-row"><span class="summary-label">Estado</span><span class="summary-value">{{ ucfirst($membro->estado) }}</span></div>
  <div class="summary-row"><span class="summary-label">Grupo</span><span class="summary-value">{{ $membro->grupo->nome ?? '—' }}</span></div>
</div>

{{-- Resumo financeiro pessoal --}}
<div class="section-title">Resumo Financeiro Pessoal</div>
<div class="summary-box">
  <div class="summary-row"><span class="summary-label">Total Poupança</span><span class="summary-value">{{ number_format($totaisContrib['poupanca'], 2) }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Total Fundo Social</span><span class="summary-value">{{ number_format($totaisContrib['fundo_social'], 2) }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Total Multas / Atraso</span><span class="summary-value">{{ number_format($totaisContrib['atraso'], 2) }} MZN</span></div>
  <div class="summary-row total-row">
    <span class="summary-label">TOTAL CONTRIBUÍDO</span>
    <span class="summary-value">{{ number_format($totalContribuido, 2) }} MZN</span>
  </div>
</div>

<div class="summary-box">
  <div class="summary-row"><span class="summary-label">Total Emprestado (principal)</span><span class="summary-value">{{ number_format($totalEmprestado, 2) }} MZN</span></div>
  <div class="summary-row"><span class="summary-label">Valor ainda em dívida</span><span class="summary-value">{{ number_format($totalDevido, 2) }} MZN</span></div>
</div>

{{-- Histórico de contribuições --}}
<div class="section-title">Histórico de Contribuições</div>
<table>
  <thead><tr><th>Data</th><th>Tipo</th><th class="text-right">Valor (MZN)</th><th>Observação</th></tr></thead>
  <tbody>
  @forelse($membro->contribuicoes->sortByDesc('data') as $c)
  <tr>
    <td>{{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}</td>
    <td>{{ ucfirst(str_replace('_', ' ', $c->tipo)) }}</td>
    <td class="text-right">{{ number_format($c->valor, 2) }}</td>
    <td>{{ $c->observacao ?? '' }}</td>
  </tr>
  @empty
  <tr><td colspan="4" style="text-align:center;color:#94a3b8;">Sem contribuições.</td></tr>
  @endforelse
  </tbody>
</table>

{{-- Histórico de empréstimos --}}
<div class="section-title">Histórico de Empréstimos</div>
<table>
  <thead><tr><th>Data</th><th>Principal</th><th>Taxa</th><th>Valor Devido</th><th>Vencimento</th><th>Estado</th></tr></thead>
  <tbody>
  @forelse($membro->emprestimos->sortByDesc('data_emprestimo') as $e)
  <tr>
    <td>{{ \Carbon\Carbon::parse($e->data_emprestimo)->format('d/m/Y') }}</td>
    <td class="text-right">{{ number_format($e->valor_principal, 2) }}</td>
    <td>{{ $e->taxa_juro }}%</td>
    <td class="text-right text-bold">{{ number_format($e->valor_devido, 2) }}</td>
    <td>{{ \Carbon\Carbon::parse($e->data_vencimento)->format('d/m/Y') }}</td>
    <td><span class="badge-{{ $e->estado }}">{{ ucfirst($e->estado) }}</span></td>
  </tr>
  @empty
  <tr><td colspan="6" style="text-align:center;color:#94a3b8;">Sem empréstimos.</td></tr>
  @endforelse
  </tbody>
</table>

<div class="footer">
  Sistema de Poupança SaaS &nbsp;|&nbsp; Documento gerado automaticamente &nbsp;|&nbsp; {{ $geradoEm }}
</div>

</body>
</html>
