<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Membro;
use App\Models\Contribuicao;
use App\Models\Emprestimo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DivisaoFinalController extends Controller
{
    public function encerrar(Grupo $grupo)
    {
        $membros = Membro::where('grupo_id', $grupo->id)->where('estado', 'ativo')->get();
        $totalMembros = $membros->count();

        // Total geral do caixa do grupo
        $totalPoupanca   = Contribuicao::where('grupo_id', $grupo->id)->where('tipo', 'poupanca')->sum('valor');
        $totalFundo      = Contribuicao::where('grupo_id', $grupo->id)->where('tipo', 'fundo_social')->sum('valor');
        $totalMultas     = Contribuicao::where('grupo_id', $grupo->id)->where('tipo', 'atraso')->sum('valor');
        $totalJurosRecebidos = Emprestimo::where('grupo_id', $grupo->id)
            ->where('estado', 'pago')
            ->get()->sum(fn($e) => $e->valor_devido - $e->valor_principal);

        $totalCaixa = $totalPoupanca + $totalFundo + $totalMultas + $totalJurosRecebidos;

        // Calcular divisão por membro
        $divisao = [];
        foreach ($membros as $membro) {
            // Poupança individual
            $poupancaIndividual = Contribuicao::where('membro_id', $membro->id)
                ->where('grupo_id', $grupo->id)->where('tipo', 'poupanca')->sum('valor');

            // Dívida activa
            $divida = Emprestimo::where('membro_id', $membro->id)
                ->where('grupo_id', $grupo->id)->where('estado', 'pendente')
                ->sum('valor_devido');

            // Juros distribuídos proporcionalmente
            $jurosDistribuidos = $totalMembros > 0 ? $totalJurosRecebidos / $totalMembros : 0;

            // Fundo social pago
            $fundoPago = Contribuicao::where('membro_id', $membro->id)
                ->where('grupo_id', $grupo->id)->where('tipo', 'fundo_social')->sum('valor');

            // Valor a receber = poupança + juros distribuídos - dívida
            $valorAReceber = $poupancaIndividual + $jurosDistribuidos - $divida;

            $divisao[] = [
                'membro'             => $membro,
                'poupanca'           => $poupancaIndividual,
                'fundo'              => $fundoPago,
                'juros'              => round($jurosDistribuidos, 2),
                'divida'             => $divida,
                'valorAReceber'      => round($valorAReceber, 2),
            ];
        }

        return view('tenant.grupos.encerrar', compact(
            'grupo', 'divisao', 'totalCaixa',
            'totalPoupanca', 'totalFundo', 'totalMultas',
            'totalJurosRecebidos', 'totalMembros'
        ));
    }

    public function confirmar(Request $request, Grupo $grupo)
    {
        $grupo->update(['estado' => 'encerrado', 'data_fim' => now()]);
        return redirect()->route('tenant.grupos.index')
            ->with('success', 'Grupo encerrado e divisão final calculada com sucesso!');
    }
}
