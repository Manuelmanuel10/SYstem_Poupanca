<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 20px; }
    .header { background: #1a3c5e; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .header h1 { margin: 0; font-size: 18px; }
    .header p { margin: 4px 0 0; font-size: 12px; opacity: 0.8; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #1a3c5e; color: white; padding: 8px; text-align: left; font-size: 10px; }
    td { padding: 7px 8px; border-bottom: 1px solid #eee; }
    tr:nth-child(even) td { background: #f9f9f9; }
    .text-right { text-align: right; }
    .text-success { color: #16a34a; font-weight: bold; }
    .text-danger  { color: #dc2626; font-weight: bold; }
    .total-row td { font-weight: bold; background: #f0f4ff; font-size: 12px; }
    .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    .summary { display: table; width: 100%; margin-bottom: 16px; border-spacing: 8px; }
    .sum-box { display: table-cell; text-align: center; padding: 10px; border-radius: 6px; }
    .sum-in  { background: #d1fae5; }
    .sum-out { background: #fee2e2; }
    .sum-bal { background: #dbeafe; }
    .sum-box .val { font-size: 14px; font-weight: bold; }
    .sum-box .lbl { font-size: 10px; margin-top: 2px; }
</style>
</head>
<body>
<div class="header">
    <h1>Livro-Caixa — {{ $grupo->nome }}</h1>
    <p>Período: {{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }} &nbsp;|&nbsp; Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="summary">
    <div class="sum-box sum-in">
        <div class="val text-success">+{{ number_format($totalEntradas, 2) }} MT</div>
        <div class="lbl">Total Entradas</div>
    </div>
    <div class="sum-box sum-out">
        <div class="val text-danger">-{{ number_format($totalSaidas, 2) }} MT</div>
        <div class="lbl">Total Saídas</div>
    </div>
    <div class="sum-box sum-bal">
        <div class="val" style="color:#1e40af">{{ number_format($saldoFinal, 2) }} MT</div>
        <div class="lbl">Saldo Final</div>
    </div>
</div>

<table>
    <thead>
        <tr><th>Data</th><th>Descrição</th><th class="text-right">Entrada (MT)</th><th class="text-right">Saída (MT)</th><th class="text-right">Saldo (MT)</th></tr>
    </thead>
    <tbody>
        @forelse($movimentos as $m)
        <tr>
            <td>{{ \Carbon\Carbon::parse($m['data'])->format('d/m/Y') }}</td>
            <td>{{ $m['descricao'] }}</td>
            <td class="text-right text-success">{{ $m['entrada'] > 0 ? '+'.number_format($m['entrada'],2) : '—' }}</td>
            <td class="text-right text-danger">{{ $m['saida'] > 0 ? '-'.number_format($m['saida'],2) : '—' }}</td>
            <td class="text-right" style="color:{{ $m['saldo'] >= 0 ? '#16a34a' : '#dc2626' }}">{{ number_format($m['saldo'],2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:#999">Sem movimentos no período.</td></tr>
        @endforelse
        <tr class="total-row">
            <td colspan="2">Totais</td>
            <td class="text-right text-success">+{{ number_format($totalEntradas,2) }}</td>
            <td class="text-right text-danger">-{{ number_format($totalSaidas,2) }}</td>
            <td class="text-right">{{ number_format($saldoFinal,2) }}</td>
        </tr>
    </tbody>
</table>

<div class="footer">Sistema de Poupança SaaS &nbsp;|&nbsp; {{ config('app.name') }} &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
