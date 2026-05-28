<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Contribuicao;
use App\Models\Grupo;
use App\Models\Membro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContribuicaoController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $grupoIds = Grupo::where('tenant_id', $tenant->id)->pluck('id');
        $contribuicoes = Contribuicao::whereIn('grupo_id', $grupoIds)
            ->with(['membro', 'grupo'])->latest()->get();
        $totalPoupanca   = $contribuicoes->where('tipo', 'poupanca')->sum('valor');
        $totalFundo      = $contribuicoes->where('tipo', 'fundo_social')->sum('valor');
        $totalAtraso     = $contribuicoes->where('tipo', 'atraso')->sum('valor');
        return view('tenant.contribuicoes.index', compact(
            'contribuicoes', 'totalPoupanca', 'totalFundo', 'totalAtraso'
        ));
    }

    public function create()
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)->where('estado', 'ativo')->get();
        $membros = Membro::whereIn('grupo_id', $grupos->pluck('id'))->where('estado', 'ativo')->get();
        return view('tenant.contribuicoes.create', compact('grupos', 'membros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grupo_id'  => 'required|exists:grupos,id',
            'membro_id' => 'required|exists:membros,id',
            'tipo'      => 'required|in:poupanca,fundo_social,atraso',
            'valor'     => 'required|numeric|min:0.01',
            'data'      => 'required|date',
        ]);
        Contribuicao::create($request->all());
        return redirect()->route('tenant.contribuicoes.index')
            ->with('success', 'Contribuição registada com sucesso!');
    }

    public function edit(Contribuicao $contribuico)
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)->get();
        $membros = Membro::whereIn('grupo_id', $grupos->pluck('id'))->get();
        return view('tenant.contribuicoes.edit', compact('contribuico', 'grupos', 'membros'));
    }

    public function update(Request $request, Contribuicao $contribuico)
    {
        $request->validate([
            'tipo'  => 'required|in:poupanca,fundo_social,atraso',
            'valor' => 'required|numeric|min:0.01',
            'data'  => 'required|date',
        ]);
        $contribuico->update($request->all());
        return redirect()->route('tenant.contribuicoes.index')
            ->with('success', 'Contribuição actualizada!');
    }

    public function destroy(Contribuicao $contribuico)
    {
        $contribuico->delete();
        return redirect()->route('tenant.contribuicoes.index')
            ->with('success', 'Contribuição eliminada!');
    }

    public function show(Contribuicao $contribuico)
    {
        return view('tenant.contribuicoes.show', compact('contribuico'));
    }
}
