<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)->withCount('membros')->latest()->get();
        return view('tenant.grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('tenant.grupos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'             => 'required|string|max:255',
            'taxa_juro'        => 'required|numeric|min:0',
            'taxa_atraso'      => 'required|numeric|min:0',
            'taxa_fundo_social'=> 'required|numeric|min:0',
            'data_inicio'      => 'required|date',
        ]);

        $datas = array_filter($request->datas_encontro ?? []);
        sort($datas);

        $tenant = Auth::user()->tenant;
        Grupo::create([
            'tenant_id'         => $tenant->id,
            'nome'              => $request->nome,
            'taxa_juro'         => $request->taxa_juro,
            'taxa_atraso'       => $request->taxa_atraso,
            'taxa_fundo_social' => $request->taxa_fundo_social,
            'data_inicio'       => $request->data_inicio,
            'estado'            => 'ativo',
            'datas_encontro'    => array_values($datas),
        ]);

        return redirect()->route('tenant.grupos.index')
            ->with('success', 'Grupo criado com sucesso!');
    }

    public function edit(Grupo $grupo)
    {
        return view('tenant.grupos.edit', compact('grupo'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nome'             => 'required|string|max:255',
            'taxa_juro'        => 'required|numeric|min:0',
            'taxa_atraso'      => 'required|numeric|min:0',
            'taxa_fundo_social'=> 'required|numeric|min:0',
            'data_inicio'      => 'required|date',
        ]);

        $datas = array_filter($request->datas_encontro ?? []);
        sort($datas);

        $grupo->update([
            'nome'              => $request->nome,
            'taxa_juro'         => $request->taxa_juro,
            'taxa_atraso'       => $request->taxa_atraso,
            'taxa_fundo_social' => $request->taxa_fundo_social,
            'data_inicio'       => $request->data_inicio,
            'datas_encontro'    => array_values($datas),
        ]);

        return redirect()->route('tenant.grupos.index')
            ->with('success', 'Grupo actualizado!');
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return redirect()->route('tenant.grupos.index')
            ->with('success', 'Grupo eliminado!');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load('membros');
        return view('tenant.grupos.show', compact('grupo'));
    }
}
