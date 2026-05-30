<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PagamentoEmprestimo extends Model
{
    protected $table = 'pagamentos_emprestimo';
    protected $fillable = ['emprestimo_id', 'membro_id', 'valor', 'data', 'mes_referencia'];

    public function emprestimo() { return $this->belongsTo(Emprestimo::class); }
    public function membro()     { return $this->belongsTo(Membro::class); }
}
