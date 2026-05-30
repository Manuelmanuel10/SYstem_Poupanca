<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1a1a1a; margin: 0; padding: 15px; }
    .header { background: #1a3c5e; color: white; padding: 12px 16px; border-radius: 6px; margin-bottom: 12px; }
    .header h1 { margin: 0; font-size: 14px; }
    .header p  { margin: 3px 0 0; font-size: 9px; opacity: 0.8; }
    .info-box { display: table; width: 100%; margin-bottom: 12px; border-spacing: 6px; }
    .info-cell { display: table-cell; background: #f4f6f9; border-radius: 6px; padding: 8px; text-align: center; border-top: 3px solid #2563a8; }
    .info-cell .val { font-size: 13px; font-weight: bold; color: #1a3c5e; }
    .info-cell .lbl { font-size: 8px; color: #666; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    th { background: #1a3c5e; color: white; padding: 6px 5px; text-align: center; font-size: 8px; }
    th.left { text-align: left; }
    td { padding: 5px; border: 0.5px solid #ddd; text-align: right; font-size: 8.5px; }
    td.mes { text-align: left; font-weight: bold; background: #f8fafc; }
    td.zero { color: #ccc; }
    td.blank { background: #f9f9f9; color: #ccc; font-style: italic; }
    td.encontro { background: #e8f5e9; }
    .total-row td { background: #1a3c5e; color: white; font-weight: bold; }
    .acum { color: #2563a8; font-weight: bold; }
    .section-title { font-size: 11px; font-weight: bold; color: #1a3c5e; border-bottom: 2px solid #2563a8; padding-bottom: 3px; margin: 12px 0 8px; }
    .footer { margin-top: 10px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #eee; padding-top: 6px; }
</style>
</head>
<body>
<div class="header">
    <h1>Extracto de Evolução — {{ $membro->nome }} — {{ $ano }}</h1>
    <p>
        Grupo: {{ $grupo->nome }} &nbsp;|&nbsp;
        Cargo: {{ ucfirst($membro->cargo) }} &nbsp;|&nbsp;
        Juro: {{ $grupo->taxa_juro }}%/mês &nbsp;|&nbsp;
        Fundo Social: {{ number_format($grupo->taxa_fundo_social,0) }} MT/mês &nbsp;|&nbsp;
        Membros Activos: {{ $totalMembrosAtivos }} &nbsp;|&nbsp;
        Gerado: {{ now()->format('d/m/Y H:i') }}
    </p>
</div>

<div class="info-box">
    <div class="info-cell">
        <div class="val">{{ number_format($totalPoupado, 2) }} MT</div>
        <div class="lbl">Total Poupado</div>
    </div>
    <div class="info-cell">
        <div class="val">{{ number_format($totalFundo, 2) }} MT</div>
        <div class="lbl">Total Fundo Social</div>
    </div>
    <div class="info-cell">
        <div class="val" style="color:#16a34a">{{ number_format($totalJuros, 2) }} MT</div>
        <div class="lbl">Juros Recebidos</div>
    </div>
    <div class="info-cell">
        <div class="val" style="color:#dc2626">{{ number_format($devido, 2) }} MT</div>
        <div class="lbl">Dívida Activa</div>
    </div>
    <div class="info-cell">
        <div class="val" style="color:#2563a8">{{ number_format($acumuladoFinal, 2) }} MT</div>
        <div class="lbl">Acumulado Final</div>
    </div>
    <div class="info-cell">
        <div class="val" style="color:{{ $grandeTotal >= 0 ? '#16a34a' : '#dc2626' }}">{{ number_format($grandeTotal, 2) }} MT</div>
        <div class="lbl">Grande Total</div>
    </div>
</div>

<div class="section-title">Evolução Mensal</div>
<table>
    <thead>
        <tr>
            <th class="left">Mês</th>
            <th>Poupança (MT)</th>
            <th>Fundo Social (MT)</th>
            <th>Juro Recebido (MT)</th>
            <th>Acumulado Poupança (MT)</th>
            <th>Encontro</th>
        </tr>
    </thead>
    <tbody>
        @foreach($meses as $mes)
        @php $temEncontro = in_array($mes, $mesesComEncontro); @endphp
        <tr>
            <td class="mes">{{ $nomesMeses[$mes-1] }}</td>
            <td class="{{ $poupancaMensal[$mes] == 0 ? 'zero' : '' }}">
                {{ $poupancaMensal[$mes] > 0 ? number_format($poupancaMensal[$mes], 2) : '—' }}
            </td>
            <td class="{{ $fundoMensal[$mes] == 0 ? 'zero' : '' }}">
                {{ $fundoMensal[$mes] > 0 ? number_format($fundoMensal[$mes], 2) : '—' }}
            </td>
            <td class="{{ $juroMensal[$mes] == 0 ? 'zero' : '' }}">
                {{ $juroMensal[$mes] > 0 ? number_format($juroMensal[$mes], 2) : '—' }}
            </td>
            @if($temEncontro)
            <td class="acum encontro">{{ $acumuladoMensal[$mes] !== null ? number_format($acumuladoMensal[$mes], 2) : '' }}</td>
            @else
            <td class="blank">—</td>
            @endif
            <td style="text-align:center">{{ $temEncontro ? '✓' : '' }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td class="left">TOTAL</td>
            <td>{{ number_format($totalPoupado, 2) }}</td>
            <td>{{ number_format($totalFundo, 2) }}</td>
            <td>{{ number_format($totalJuros, 2) }}</td>
            <td>{{ number_format($acumuladoFinal, 2) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>

<div class="section-title">Histórico de Contribuições</div>
<table>
    <thead>
        <tr>
            <th class="left">Data</th>
            <th class="left">Tipo</th>
            <th>Valor (MT)</th>
            <th class="left">Observação</th>
        </tr>
    </thead>
    <tbody>
        @forelse($historico as $h)
        <tr>
            <td style="text-align:left">{{ \Carbon\Carbon::parse($h->data)->format('d/m/Y') }}</td>
            <td style="text-align:left">{{ ucfirst(str_replace('_',' ',$h->tipo)) }}</td>
            <td>{{ number_format($h->valor, 2) }}</td>
            <td style="text-align:left">{{ $h->observacao ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#999">Sem contribuições em {{ $ano }}.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="footer">Sistema de Poupança SaaS &nbsp;|&nbsp; {{ config('app.name') }} &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
