<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 7px; color: #1a1a1a; margin: 0; padding: 12px; }
    .header { background: #1a3c5e; color: white; padding: 10px 14px; border-radius: 6px; margin-bottom: 8px; }
    .header h1 { margin: 0; font-size: 12px; }
    .header p  { margin: 2px 0 0; font-size: 7.5px; opacity: 0.85; }
    .legenda { background: #f4f6f9; padding: 5px 8px; border-radius: 4px; margin-bottom: 7px; font-size: 7px; }
    table { width: 100%; border-collapse: collapse; font-size: 6.8px; }
    th { background: #1a3c5e; color: white; padding: 4px 3px; text-align: center; border: 0.5px solid #0f2540; }
    th.nome { text-align: left; min-width: 75px; }
    td { padding: 3px; border: 0.5px solid #ddd; text-align: right; }
    td.nome { text-align: left; font-weight: bold; background: #f8fafc; }
    td.zero { color: #ccc; }
    td.blank { background: #f9f9f9; }
    .sub-header th { background: #2563a8; font-size: 6.5px; }
    .total-row td { background: #1a3c5e; color: white; font-weight: bold; }
    .total-row td.nome { background: #1a3c5e; }
    .acum { color: #2563a8; font-weight: bold; }
    .devido { color: #dc2626; }
    .gt-pos { color: #16a34a; font-weight: bold; }
    .gt-neg { color: #dc2626; font-weight: bold; }
    .footer { margin-top: 8px; text-align: center; font-size: 7px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
</style>
</head>
<body>
<div class="header">
    <h1>Evolução do Grupo — {{ $grupo->nome }} — {{ $ano }}</h1>
    <p>
        Juro: {{ $grupo->taxa_juro }}%/mês &nbsp;|&nbsp;
        Fundo Social: {{ number_format($grupo->taxa_fundo_social,0) }} MT/mês &nbsp;|&nbsp;
        Multa: {{ number_format($grupo->taxa_atraso,0) }} MT &nbsp;|&nbsp;
        Membros: {{ $totalMembrosAtivos }} &nbsp;|&nbsp;
        Início: {{ \Carbon\Carbon::parse($grupo->data_inicio)->format('d/m/Y') }} &nbsp;|&nbsp;
        Encontros: {{ count($mesesComEncontro) }} &nbsp;|&nbsp;
        Gerado: {{ now()->format('d/m/Y H:i') }}
    </p>
</div>
<div class="legenda">
    P=Poupança &nbsp;|&nbsp; FS=Fundo Social &nbsp;|&nbsp; J=Juro distribuído ({{ $grupo->taxa_juro }}%÷{{ $totalMembrosAtivos }}) &nbsp;|&nbsp;
    Acum=Acumulado (só meses com encontro) &nbsp;|&nbsp; Devido=Empréstimo por pagar &nbsp;|&nbsp; G.T=Grande Total
</div>
<table>
    <thead>
        <tr>
            <th class="nome" rowspan="2">Membro</th>
            @foreach($nomesMeses as $i => $nm)
            <th colspan="{{ in_array($i+1, $mesesComEncontro) ? 4 : 3 }}" style="{{ in_array($i+1, $mesesComEncontro) ? 'background:#1a5c3e' : '' }}">{{ $nm }}</th>
            @endforeach
            <th rowspan="2">T.Poup</th>
            <th rowspan="2">T.FS</th>
            <th rowspan="2">T.Juro</th>
            <th rowspan="2">Devido</th>
            <th rowspan="2">Acum.Final</th>
            <th rowspan="2">G.Total</th>
        </tr>
        <tr class="sub-header">
            @foreach($meses as $mes)
            <th>P</th><th>FS</th><th>J</th>
            @if(in_array($mes, $mesesComEncontro))<th style="background:#1a5c3e">Acum</th>@endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($dados['membros'] as $d)
        <tr>
            <td class="nome">{{ $d['membro']->nome }}</td>
            @foreach($meses as $mes)
            <td class="{{ $d['poupancaMensal'][$mes] == 0 ? 'zero' : '' }}">{{ $d['poupancaMensal'][$mes] > 0 ? number_format($d['poupancaMensal'][$mes],0) : '—' }}</td>
            <td class="{{ $d['fundoMensal'][$mes] == 0 ? 'zero' : '' }}">{{ $d['fundoMensal'][$mes] > 0 ? number_format($d['fundoMensal'][$mes],0) : '—' }}</td>
            <td class="{{ $d['juroMensal'][$mes] == 0 ? 'zero' : '' }}">{{ $d['juroMensal'][$mes] > 0 ? number_format($d['juroMensal'][$mes],0) : '—' }}</td>
            @if(in_array($mes, $mesesComEncontro))
            <td class="acum" style="background:#e8f5e9">{{ $d['acumuladoMensal'][$mes] !== null ? number_format($d['acumuladoMensal'][$mes],0) : '' }}</td>
            @endif
            @endforeach
            <td>{{ number_format($d['totalPoupado'],0) }}</td>
            <td>{{ number_format($d['totalFundo'],0) }}</td>
            <td>{{ number_format($d['totalJuros'],0) }}</td>
            <td class="devido">{{ $d['devido'] > 0 ? number_format($d['devido'],0) : '—' }}</td>
            <td class="acum">{{ number_format($d['acumuladoFinal'],0) }}</td>
            <td class="{{ $d['grandeTotal'] >= 0 ? 'gt-pos' : 'gt-neg' }}">{{ number_format($d['grandeTotal'],0) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td class="nome">TOTAL</td>
            @foreach($meses as $mes)
            <td>{{ $dados['totais']['poupancaMensal'][$mes] > 0 ? number_format($dados['totais']['poupancaMensal'][$mes],0) : '—' }}</td>
            <td>{{ $dados['totais']['fundoMensal'][$mes]    > 0 ? number_format($dados['totais']['fundoMensal'][$mes],0)    : '—' }}</td>
            <td>{{ $dados['totais']['juroMensal'][$mes]     > 0 ? number_format($dados['totais']['juroMensal'][$mes],0)     : '—' }}</td>
            @if(in_array($mes, $mesesComEncontro))
            <td>{{ $dados['totais']['acumuladoMensal'][$mes] !== null ? number_format($dados['totais']['acumuladoMensal'][$mes],0) : '' }}</td>
            @endif
            @endforeach
            <td>{{ number_format($dados['totais']['totalPoupado'],0) }}</td>
            <td>{{ number_format($dados['totais']['totalFundo'],0) }}</td>
            <td>{{ number_format($dados['totais']['totalJuros'],0) }}</td>
            <td>{{ $dados['totais']['devido'] > 0 ? number_format($dados['totais']['devido'],0) : '—' }}</td>
            <td>{{ number_format($dados['totais']['acumuladoFinal'],0) }}</td>
            <td>{{ number_format($dados['totais']['grandeTotal'],0) }}</td>
        </tr>
    </tfoot>
</table>
<div class="footer">
    Sistema de Poupança SaaS &nbsp;|&nbsp; {{ config('app.name') }} &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i') }}
    &nbsp;|&nbsp; Meses com encontro: {{ implode(', ', array_map(fn($m) => ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'][$m-1], $mesesComEncontro)) }}
</div>
</body>
</html>
