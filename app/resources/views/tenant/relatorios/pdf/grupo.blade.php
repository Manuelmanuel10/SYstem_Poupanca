<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 20px; }
    .header { background: #1a3c5e; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .header h1 { margin: 0; font-size: 18px; }
    .header p { margin: 4px 0 0; font-size: 12px; opacity: 0.8; }
    .stats { display: table; width: 100%; margin-bottom: 20px; border-spacing: 8px; }
    .stat { display: table-cell; background: #f4f6f9; border-radius: 6px; padding: 12px; text-align: center; border-left: 4px solid #2563a8; }
    .stat .val { font-size: 16px; font-weight: bold; color: #1a3c5e; }
    .stat .lbl { font-size: 10px; color: #666; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #1a3c5e; color: white; padding: 8px; text-align: left; font-size: 10px; }
    td { padding: 7px 8px; border-bottom: 1px solid #eee; }
    tr:nth-child(even) td { background: #f9f9f9; }
    .section-title { font-size: 13px; font-weight: bold; color: #1a3c5e; border-bottom: 2px solid #2563a8; padding-bottom: 4px; margin: 16px 0 10px; }
    .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; }
    .bg-success { background: #d1fae5; color: #065f46; }
    .bg-primary { background: #dbeafe; color: #1e40af; }
    .bg-danger  { background: #fee2e2; color: #991b1b; }
    .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    .text-right { text-align: right; }
    .total-row td { font-weight: bold; background: #f0f4ff; }
</style>
</head>
<body>
<div class="header">
    <h1>Relatório do Grupo — {{ $grupo->nome }}</h1>
    <p>Período: {{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }} &nbsp;|&nbsp; Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="stats">
    <div class="stat"><div class="val">{{ $membros->count() }}</div><div class="lbl">Membros</div></div>
    <div class="stat"><div class="val">{{ number_format($totalPoupanca, 2) }} MT</div><div class="lbl">Poupança</div></div>
    <div class="stat"><div class="val">{{ number_format($totalFundo, 2) }} MT</div><div class="lbl">Fundo Social</div></div>
    <div class="stat"><div class="val">{{ number_format($totalAtraso, 2) }} MT</div><div class="lbl">Multas</div></div>
    <div class="stat"><div class="val">{{ number_format($totalEntradas, 2) }} MT</div><div class="lbl">Total Entradas</div></div>
</div>

<div class="section-title">Lista de Membros</div>
<table>
    <thead><tr><th>#</th><th>Nome</th><th>Cargo</th><th>Telefone</th><th>Estado</th></tr></thead>
    <tbody>
        @foreach($membros as $i => $m)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $m->nome }}</td>
            <td>{{ ucfirst($m->cargo) }}</td>
            <td>{{ $m->telefone ?? '-' }}</td>
            <td>{{ ucfirst($m->estado) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="section-title">Contribuições no Período</div>
<table>
    <thead><tr><th>Data</th><th>Membro</th><th>Tipo</th><th class="text-right">Valor (MT)</th></tr></thead>
    <tbody>
        @forelse($contribuicoes as $c)
        <tr>
            <td>{{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}</td>
            <td>{{ $c->membro->nome }}</td>
            <td>
                @if($c->tipo=='poupanca') <span class="badge bg-success">Poupança</span>
                @elseif($c->tipo=='fundo_social') <span class="badge bg-primary">Fundo Social</span>
                @else <span class="badge bg-danger">Multa</span>
                @endif
            </td>
            <td class="text-right">{{ number_format($c->valor, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#999">Sem contribuições no período.</td></tr>
        @endforelse
        <tr class="total-row">
            <td colspan="3">Total</td>
            <td class="text-right">{{ number_format($totalEntradas, 2) }}</td>
        </tr>
    </tbody>
</table>

<div class="section-title">Empréstimos</div>
<table>
    <thead><tr><th>Data</th><th>Membro</th><th>Principal (MT)</th><th>Juro</th><th>Total Devido (MT)</th><th>Estado</th></tr></thead>
    <tbody>
        @forelse($emprestimos as $e)
        <tr>
            <td>{{ \Carbon\Carbon::parse($e->data_emprestimo)->format('d/m/Y') }}</td>
            <td>{{ $e->membro->nome }}</td>
            <td class="text-right">{{ number_format($e->valor_principal, 2) }}</td>
            <td>{{ $e->taxa_juro }}%</td>
            <td class="text-right">{{ number_format($e->valor_devido, 2) }}</td>
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
