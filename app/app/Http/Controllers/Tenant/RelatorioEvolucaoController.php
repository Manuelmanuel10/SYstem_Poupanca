<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Membro;
use App\Models\Contribuicao;
use App\Models\Emprestimo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RelatorioEvolucaoController extends Controller
{
    private function calcularMembro($membro, $grupo, $ano, $meses, $totalMembrosAtivos, $mesesComEncontro)
    {
        $poupancaMensal  = [];
        $fundoMensal     = [];
        $juroMensal      = [];
        $acumuladoMensal = [];

        // 1. Poupança por mês
        foreach ($meses as $mes) {
            $val = Contribuicao::where('membro_id', $membro->id)
                ->where('tipo', 'poupanca')
                ->whereYear('data', $ano)->whereMonth('data', $mes)->sum('valor');
            $poupancaMensal[$mes] = floatval($val);
        }

        // 2. Fundo Social — desagregar a partir do mês de início do grupo
        $fundoTaxaMensal = floatval($grupo->taxa_fundo_social);
        $mesInicioGrupo  = Carbon::parse($grupo->data_inicio)->month;
        $anoInicioGrupo  = Carbon::parse($grupo->data_inicio)->year;
        foreach ($meses as $mes) { $fundoMensal[$mes] = 0.0; }

        $pagamentosFundo = Contribuicao::where('membro_id', $membro->id)
            ->where('tipo', 'fundo_social')
            ->whereYear('data', $ano)->orderBy('data')->get();

        foreach ($pagamentosFundo as $pag) {
            $valorPago    = floatval($pag->valor);
            $mesPagamento = Carbon::parse($pag->data)->month;

            if ($fundoTaxaMensal > 0 && $valorPago >= ($fundoTaxaMensal * 2)) {
                // Pagou de uma vez — distribuir a partir do mês de início do grupo
                $mesInicio     = ($ano == $anoInicioGrupo) ? $mesInicioGrupo : 1;
                $valorRestante = $valorPago;
                $mesAtual      = $mesInicio;

                while ($valorRestante > 0 && $mesAtual <= 12) {
                    $valorParaEste = min($fundoTaxaMensal, $valorRestante);
                    $fundoMensal[$mesAtual] = ($fundoMensal[$mesAtual] ?? 0) + $valorParaEste;
                    $valorRestante -= $valorParaEste;
                    $mesAtual++;
                }
            } else {
                // Pagamento normal — distribuir a partir deste mês
                $valorRestante = $valorPago;
                $mesAtual      = $mesPagamento;

                while ($valorRestante > 0 && $mesAtual <= 12) {
                    $valorParaEste = min($fundoTaxaMensal, $valorRestante);
                    $fundoMensal[$mesAtual] = ($fundoMensal[$mesAtual] ?? 0) + $valorParaEste;
                    $valorRestante -= $valorParaEste;
                    $mesAtual++;
                }
            }
        }

        // 3. Juros distribuídos por membro
        $emprestimosGrupo = Emprestimo::where('grupo_id', $grupo->id)
            ->where('estado', 'pendente')->get();

        foreach ($meses as $mes) {
            $juroTotal = 0;
            foreach ($emprestimosGrupo as $emp) {
                $mesEmp = Carbon::parse($emp->data_emprestimo)->month;
                $anoEmp = Carbon::parse($emp->data_emprestimo)->year;
                if ($anoEmp < $ano || ($anoEmp == $ano && $mesEmp <= $mes)) {
                    $taxaJuro   = floatval($grupo->taxa_juro) / 100;
                    $juroTotal += floatval($emp->valor_principal) * $taxaJuro;
                }
            }
            $juroMensal[$mes] = $totalMembrosAtivos > 0
                ? round($juroTotal / $totalMembrosAtivos, 2) : 0;
        }

        // 4. Acumulado Poupança — só nos meses com encontro
        // Regra: Mês 1 = Poup + Juro
        //        Mês N = Acum(N-1) + Poup + Juro
        //        Sem encontro = campo vazio (null)
        $acumulado         = 0;
        $ultimoAcumulado   = 0;
        foreach ($meses as $mes) {
            $temEncontro = in_array($mes, $mesesComEncontro);
            if ($temEncontro) {
                $acumulado = $ultimoAcumulado + $poupancaMensal[$mes] + $juroMensal[$mes];
                $acumuladoMensal[$mes] = round($acumulado, 2);
                $ultimoAcumulado = $acumulado;
            } else {
                $acumuladoMensal[$mes] = null; // sem encontro = em branco
            }
        }

        // 5. Dívida activa
        $emprestimo = Emprestimo::where('membro_id', $membro->id)
            ->where('grupo_id', $grupo->id)->where('estado', 'pendente')
            ->latest()->first();
        $devido = $emprestimo ? floatval($emprestimo->valor_principal) : 0;

        // 6. Totais
        $totalPoupado      = array_sum($poupancaMensal);
        $totalFundo        = array_sum($fundoMensal);
        $totalJuros        = array_sum($juroMensal);
        $acumuladoFinal    = $ultimoAcumulado;
        $grandeTotal       = $acumuladoFinal - $devido;

        return compact(
            'poupancaMensal', 'fundoMensal', 'juroMensal', 'acumuladoMensal',
            'totalPoupado', 'totalFundo', 'totalJuros',
            'devido', 'acumuladoFinal', 'grandeTotal'
        );
    }

    public function grupo(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'ano'      => 'required|integer|min:2020|max:2099',
        ]);

        $grupo              = Grupo::findOrFail($request->grupo_id);
        $ano                = intval($request->ano);
        $meses              = range(1, 12);
        $nomesMeses         = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        $membros            = Membro::where('grupo_id', $grupo->id)->where('estado', 'ativo')->get();
        $totalMembrosAtivos = $membros->count();
        $mesesComEncontro   = $grupo->mesesComEncontro($ano);

        $dadosMembros = [];
        foreach ($membros as $membro) {
            $d = $this->calcularMembro($membro, $grupo, $ano, $meses, $totalMembrosAtivos, $mesesComEncontro);
            $d['membro'] = $membro;
            $dadosMembros[] = $d;
        }

        $totais = [
            'poupancaMensal'  => [],
            'fundoMensal'     => [],
            'juroMensal'      => [],
            'acumuladoMensal' => [],
            'totalPoupado'    => array_sum(array_column($dadosMembros, 'totalPoupado')),
            'totalFundo'      => array_sum(array_column($dadosMembros, 'totalFundo')),
            'totalJuros'      => array_sum(array_column($dadosMembros, 'totalJuros')),
            'devido'          => array_sum(array_column($dadosMembros, 'devido')),
            'acumuladoFinal'  => array_sum(array_column($dadosMembros, 'acumuladoFinal')),
            'grandeTotal'     => array_sum(array_column($dadosMembros, 'grandeTotal')),
        ];
        foreach ($meses as $mes) {
            $totais['poupancaMensal'][$mes]  = array_sum(array_map(fn($d) => $d['poupancaMensal'][$mes], $dadosMembros));
            $totais['fundoMensal'][$mes]     = array_sum(array_map(fn($d) => $d['fundoMensal'][$mes],    $dadosMembros));
            $totais['juroMensal'][$mes]      = array_sum(array_map(fn($d) => $d['juroMensal'][$mes],     $dadosMembros));
            $vals = array_filter(array_map(fn($d) => $d['acumuladoMensal'][$mes], $dadosMembros), fn($v) => $v !== null);
            $totais['acumuladoMensal'][$mes] = count($vals) > 0 ? array_sum($vals) : null;
        }

        $dados = ['membros' => $dadosMembros, 'totais' => $totais];

        $pdf = Pdf::loadView('tenant.relatorios.pdf.evolucao_grupo',
            compact('grupo', 'ano', 'dados', 'meses', 'nomesMeses', 'totalMembrosAtivos', 'mesesComEncontro')
        )->setPaper('a4', 'landscape');

        return $pdf->download('evolucao-grupo-'.str()->slug($grupo->nome).'-'.$ano.'.pdf');
    }

    public function membro(Request $request)
    {
        $request->validate([
            'membro_id' => 'required|exists:membros,id',
            'ano'       => 'required|integer|min:2020|max:2099',
        ]);

        $membro             = Membro::with('grupo')->findOrFail($request->membro_id);
        $grupo              = $membro->grupo;
        $ano                = intval($request->ano);
        $meses              = range(1, 12);
        $nomesMeses         = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        $totalMembrosAtivos = Membro::where('grupo_id', $grupo->id)->where('estado', 'ativo')->count();
        $mesesComEncontro   = $grupo->mesesComEncontro($ano);

        $d = $this->calcularMembro($membro, $grupo, $ano, $meses, $totalMembrosAtivos, $mesesComEncontro);

        $historico = Contribuicao::where('membro_id', $membro->id)
            ->whereYear('data', $ano)->orderBy('data')->get();

        $totalInvestJurado = $d['acumuladoFinal'];

        $pdf = Pdf::loadView('tenant.relatorios.pdf.evolucao_membro', array_merge($d, compact(
            'membro', 'grupo', 'ano', 'meses', 'nomesMeses',
            'historico', 'totalMembrosAtivos', 'mesesComEncontro', 'totalInvestJurado'
        )))->setPaper('a4', 'landscape');

        return $pdf->download('evolucao-'.str()->slug($membro->nome).'-'.$ano.'.pdf');
    }
}
