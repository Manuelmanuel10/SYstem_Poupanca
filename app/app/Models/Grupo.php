<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = ['tenant_id', 'nome', 'taxa_juro', 'taxa_atraso', 'taxa_fundo_social', 'data_inicio', 'data_fim', 'estado'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function membros() { return $this->hasMany(Membro::class); }
    public function contribuicoes() { return $this->hasMany(Contribuicao::class); }
    public function emprestimos() { return $this->hasMany(Emprestimo::class); }
}
