<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Emprestimo;
use App\Models\Contribuicao;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        $grupos = Grupo::where('tenant_id', $tenant?->id)
            ->withCount('membros')->latest()->take(5)->get();

        $totalGrupos    = $grupos->count();
        $totalMembros   = $grupos->sum('membros_count');
        $totalPoupado   = Contribuicao::whereIn('grupo_id', $grupos->pluck('id'))
            ->where('tipo', 'poupanca')->sum('valor');
        $emprestimosActivos = Emprestimo::whereIn('grupo_id', $grupos->pluck('id'))
            ->where('estado', 'pendente')->count();
        $emprestimos = Emprestimo::with('membro')
            ->whereIn('grupo_id', $grupos->pluck('id'))
            ->where('estado', 'pendente')->latest()->take(5)->get();

        return view('tenant.dashboard.index', compact(
            'grupos', 'totalGrupos', 'totalMembros',
            'totalPoupado', 'emprestimosActivos', 'emprestimos'
        ));
    }
}
