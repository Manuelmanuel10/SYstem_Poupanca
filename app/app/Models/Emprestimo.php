<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    protected $fillable = ['membro_id', 'grupo_id', 'valor_principal', 'taxa_juro', 'valor_devido', 'data_emprestimo', 'data_vencimento', 'estado'];

    public function membro() { return $this->belongsTo(Membro::class); }
    public function grupo() { return $this->belongsTo(Grupo::class); }
}
