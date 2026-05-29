<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Contribuicao;
use App\Models\Emprestimo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LivroCaixaController extends Controller
{
    public function index(Request $request)
    {
        $tenant    = Auth::user()->tenant;
        $grupoIds  = Grupo::where('tenant_id', $tenant->id)->pluck('id');
        $grupos    = Grupo::where('tenant_id', $tenant->id)->get();

        $grupoFiltro  = $request->grupo_id;
        $tipoFiltro   = $request->tipo;
        $dataInicio   = $request->data_inicio ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $dataFim      = $request->data_fim    ?? Carbon::now()->format('Y-m-d');

        $ids = $grupoFiltro ? [$grupoFiltro] : $grupoIds->toArray();

        // Contribuições como entradas
        $contribuicoes = Contribuicao::whereIn('grupo_id', $ids)
            ->whereBetween('data', [$dataInicio, $dataFim])
            ->with(['membro', 'grupo'])
            ->get()
            ->map(function ($c) {
                return [
                    'data'      => $c->data,
                    'descricao' => ucfirst(str_replace('_', ' ', $c->tipo)) . ' — ' . $c->membro->nome,
                    'grupo'     => $c->grupo->nome,
                    'entrada'   => $c->valor,
                    'saida'     => 0,
                    'tipo'      => 'entrada',
                    'categoria' => $c->tipo,
                ];
            });

        // Empréstimos como saídas
        $emprestimos = Emprestimo::whereIn('grupo_id', $ids)
            ->whereBetween('data_emprestimo', [$dataInicio, $dataFim])
            ->with(['membro', 'grupo'])
            ->get()
            ->map(function ($e) {
                return [
                    'data'      => $e->data_emprestimo,
                    'descricao' => 'Empréstimo concedido — ' . $e->membro->nome,
                    'grupo'     => $e->grupo->nome,
                    'entrada'   => 0,
                    'saida'     => $e->valor_principal,
                    'tipo'      => 'saida',
                    'categoria' => 'emprestimo',
                ];
            });

        // Pagamentos de empréstimos como entradas
        $pagamentos = Emprestimo::whereIn('grupo_id', $ids)
            ->where('estado', 'pago')
            ->whereBetween('updated_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
            ->with(['membro', 'grupo'])
            ->get()
            ->map(function ($e) {
                return [
                    'data'      => Carbon::parse($e->updated_at)->format('Y-m-d'),
                    'descricao' => 'Pagamento de empréstimo — ' . $e->membro->nome,
                    'grupo'     => $e->grupo->nome,
                    'entrada'   => $e->valor_devido,
                    'saida'     => 0,
                    'tipo'      => 'entrada',
                    'categoria' => 'pagamento_emprestimo',
                ];
            });

        $movimentos = $contribuicoes
            ->concat($emprestimos)
            ->concat($pagamentos)
            ->sortBy('data')
            ->values();

        // Filtro por tipo
        if ($tipoFiltro === 'entrada') {
            $movimentos = $movimentos->filter(fn($m) => $m['tipo'] === 'entrada')->values();
        } elseif ($tipoFiltro === 'saida') {
            $movimentos = $movimentos->filter(fn($m) => $m['tipo'] === 'saida')->values();
        }

        // Calcular saldo acumulado
        $saldo = 0;
        $movimentos = $movimentos->map(function ($m) use (&$saldo) {
            $saldo += $m['entrada'] - $m['saida'];
            $m['saldo'] = $saldo;
            return $m;
        });

        $totalEntradas = $movimentos->sum('entrada');
        $totalSaidas   = $movimentos->sum('saida');
        $saldoFinal    = $totalEntradas - $totalSaidas;

        return view('tenant.caixa.index', compact(
            'movimentos', 'grupos', 'totalEntradas',
            'totalSaidas', 'saldoFinal',
            'grupoFiltro', 'tipoFiltro', 'dataInicio', 'dataFim'
        ));
    }
}
