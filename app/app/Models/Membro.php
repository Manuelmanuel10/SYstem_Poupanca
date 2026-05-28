<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membro extends Model
{
    protected $fillable = ['grupo_id', 'nome', 'telefone', 'cargo', 'estado'];

    public function grupo() { return $this->belongsTo(Grupo::class); }
    public function contribuicoes() { return $this->hasMany(Contribuicao::class); }
    public function emprestimos() { return $this->hasMany(Emprestimo::class); }
}
