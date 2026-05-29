<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 20px; }
    .header { background: #1a3c5e; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .header h1 { margin: 0; font-size: 18px; }
    .header p { margin: 4px 0 0; font-size: 12px; opacity: 0.8; }
    .info-box { background: #f4f6f9; border-radius: 6px; padding: 12px; margin-bottom: 16px; border-left: 4px solid #2563a8; }
    .info-box p { margin: 3px 0; }
    .stats { display: table; width: 100%; margin-bottom: 20px; border-spacing: 8px; }
    .stat { display: table-cell; background: #f4f6f9; border-radius: 6px; padding: 12px; text-align: center; }
    .stat .val { font-size: 15px; font-weight: bold; color: #1a3c5e; }
    .stat .lbl { font-size: 10px; color: #666; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #1a3c5e; color: white; padding: 8px; text-align: left; font-size: 10px; }
    td { padding: 7px 8px; border-bottom: 1px solid #eee; }
    tr:nth-child(even) td { background: #f9f9f9; }
    .section-title { font-size: 13px; font-weight: bold; color: #1a3c5e; border-bottom: 2px solid #2563a8; padding-bottom: 4px; margin: 16px 0 10px; }
    .text-right { text-align: right; }
    .total-row td { font-weight: bold; background: #f0f4ff; }
    .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
</style>
</head>
<body>
<div class="header">
    <h1>Extracto do Membro — {{ $membro->nome }}</h1>
    <p>Período: {{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }} &nbsp;|&nbsp; Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="info-box">
    <p><strong>Nome:</strong> {{ $membro->nome }}</p>
    <p><strong>Grupo:</strong> {{ $membro->grupo->nome }}</p>
    <p><strong>Cargo:</strong> {{ ucfirst($membro->cargo) }}</p>
    <p><strong>Telefone:</strong> {{ $membro->telefone ?? '-' }}</p>
</div>

<div class="stats">
    <div class="stat"><div class="val">{{ number_format($totalPoupanca, 2) }} MT</div><div class="lbl">Poupança</div></div>
    <div class="stat"><div class="val">{{ number_format($totalFundo, 2) }} MT</div><div class="lbl">Fundo Social</div></div>
    <div class="stat"><div class="val">{{ number_format($totalAtraso, 2) }} MT</div><div class="lbl">Multas</div></div>
    <div class="stat"><div class="val">{{ number_format($totalDivida, 2) }} MT</div><div class="lbl">Dívida activa</div></div>
</div>

<div class="section-title">Contribuições</div>
<table>
    <thead><tr><th>Data</th><th>Tipo</th><th class="text-right">Valor (MT)</th><th>Observação</th></tr></thead>
    <tbody>
        @forelse($contribuicoes as $c)
        <tr>
            <td>{{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$c->tipo)) }}</td>
            <td class="text-right">{{ number_format($c->valor, 2) }}</td>
            <td>{{ $c->observacao ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#999">Sem contribuições no período.</td></tr>
        @endforelse
        <tr class="total-row">
            <td colspan="2">Total Contribuído</td>
            <td class="text-right">{{ number_format($totalPoupanca + $totalFundo + $totalAtraso, 2) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>

<div class="section-title">Empréstimos</div>
<table>
    <thead><tr><th>Data</th><th>Principal (MT)</th><th>Taxa</th><th>Total Devido (MT)</th><th>Vencimento</th><th>Estado</th></tr></thead>
    <tbody>
        @forelse($emprestimos as $e)
        <tr>
            <td>{{ \Carbon\Carbon::parse($e->data_emprestimo)->format('d/m/Y') }}</td>
            <td class="text-right">{{ number_format($e->valor_principal, 2) }}</td>
            <td>{{ $e->taxa_juro }}%</td>
            <td class="text-right">{{ number_format($e->valor_devido, 2) }}</td>
            <td>{{ \Carbon\Carbon::parse($e->data_vencimento)->format('d/m/Y') }}</td>
            <td>{{ ucfirst($e->estado) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#999">Sem empréstimos.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">Sistema de Poupança SaaS &nbsp;|&nbsp; {{ config('app.name') }} &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
