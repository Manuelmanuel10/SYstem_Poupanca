<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribuicao extends Model
{
    protected $fillable = [
        'grupo_id',
        'membro_id',
        'tipo',
        'valor',
        'data',
        'observacao',
    ];

    public function membro() { return $this->belongsTo(Membro::class); }
    public function grupo() { return $this->belongsTo(Grupo::class); }
}
