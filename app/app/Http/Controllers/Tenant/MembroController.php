<?php
namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembroController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $grupoIds = Grupo::where('tenant_id', $tenant->id)->pluck('id');
        $membros = Membro::whereIn('grupo_id', $grupoIds)->with('grupo')->latest()->get();
        return view('tenant.membros.index', compact('membros'));
    }

    public function create()
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)->where('estado', 'ativo')->get();
        return view('tenant.membros.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'nome'     => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'cargo'    => 'required|string',
        ]);
        Membro::create($request->all());
        return redirect()->route('tenant.membros.index')->with('success', 'Membro adicionado com sucesso!');
    }

    public function edit(Membro $membro)
    {
        $tenant = Auth::user()->tenant;
        $grupos = Grupo::where('tenant_id', $tenant->id)->get();
        return view('tenant.membros.edit', compact('membro', 'grupos'));
    }

    public function update(Request $request, Membro $membro)
    {
        $request->validate([
            'nome'  => 'required|string|max:255',
            'cargo' => 'required|string',
        ]);
        $membro->update($request->all());
        return redirect()->route('tenant.membros.index')->with('success', 'Membro actualizado!');
    }

    public function destroy(Membro $membro)
    {
        $membro->delete();
        return redirect()->route('tenant.membros.index')->with('success', 'Membro eliminado!');
    }

    public function show(Membro $membro)
    {
        return view('tenant.membros.show', compact('membro'));
    }
}
