<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Emprestimo;
use App\Models\Grupo;
use App\Models\Membro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmprestimoController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $grupoIds = Grupo::where('tenant_id', $tenant->id)->pluck('id');
        $emprestimos = Emprestimo::whereIn('grupo_id', $grupoIds)
            ->with(['membro', 'grupo'])->latest()->get();
        $totalPendente = $emprestimos->where('estado', 'pendente')->sum('valor_devido');
        $totalPago     = $emprestimos->where('estado', 'pago')->sum('valor_devido');
        $totalAtrasado = $emprestimos->where('estado', 'atrasado')->sum('valor_devido');
        return view('tenant.emprestimos.index', compact(
            'emprestimos', 'totalPendente', 'totalPago', 'totalAtrasado'
        ));
    }

    public function create()
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)->where('estado', 'ativo')->get();
        $membros = Membro::whereIn('grupo_id', $grupos->pluck('id'))->where('estado', 'ativo')->get();
        return view('tenant.emprestimos.create', compact('grupos', 'membros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grupo_id'        => 'required|exists:grupos,id',
            'membro_id'       => 'required|exists:membros,id',
            'valor_principal' => 'required|numeric|min:1',
            'taxa_juro'       => 'required|numeric|min:0',
            'data_emprestimo' => 'required|date',
            'data_vencimento' => 'required|date|after:data_emprestimo',
        ]);

        // Cálculo de juros compostos
        // Fórmula: M = P * (1 + i)^n
        $principal = $request->valor_principal;
        $taxa      = $request->taxa_juro / 100;
        $inicio    = new \DateTime($request->data_emprestimo);
        $fim       = new \DateTime($request->data_vencimento);
        $meses     = $inicio->diff($fim)->m + ($inicio->diff($fim)->y * 12);
        $meses     = max(1, $meses);
        $valorDevido = $principal * pow(1 + $taxa, $meses);

        Emprestimo::create([
            'grupo_id'        => $request->grupo_id,
            'membro_id'       => $request->membro_id,
            'valor_principal' => $principal,
            'taxa_juro'       => $request->taxa_juro,
            'valor_devido'    => round($valorDevido, 2),
            'data_emprestimo' => $request->data_emprestimo,
            'data_vencimento' => $request->data_vencimento,
            'estado'          => 'pendente',
        ]);

        return redirect()->route('tenant.emprestimos.index')
            ->with('success', 'Empréstimo registado com sucesso!');
    }

    public function show(Emprestimo $emprestimo)
    {
        return view('tenant.emprestimos.show', compact('emprestimo'));
    }

    public function edit(Emprestimo $emprestimo)
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)->get();
        $membros = Membro::whereIn('grupo_id', $grupos->pluck('id'))->get();
        return view('tenant.emprestimos.edit', compact('emprestimo', 'grupos', 'membros'));
    }

    public function update(Request $request, Emprestimo $emprestimo)
    {
        $request->validate([
            'estado' => 'required|in:pendente,pago,atrasado',
        ]);
        $emprestimo->update(['estado' => $request->estado]);
        return redirect()->route('tenant.emprestimos.index')
            ->with('success', 'Estado do empréstimo actualizado!');
    }

    public function destroy(Emprestimo $emprestimo)
    {
        $emprestimo->delete();
        return redirect()->route('tenant.emprestimos.index')
            ->with('success', 'Empréstimo eliminado!');
    }
}
