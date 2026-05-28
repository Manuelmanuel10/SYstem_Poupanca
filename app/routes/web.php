<?php
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\GrupoController;
use App\Http\Controllers\Tenant\MembroController;
use App\Http\Controllers\Tenant\ContribuicaoController;
use App\Http\Controllers\Tenant\EmprestimoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->get('/dashboard', fn() => redirect()->route('tenant.dashboard'));

Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('grupos', GrupoController::class);
    Route::resource('membros', MembroController::class);
    Route::resource('contribuicoes', ContribuicaoController::class);
    Route::resource('emprestimos', EmprestimoController::class);
});
