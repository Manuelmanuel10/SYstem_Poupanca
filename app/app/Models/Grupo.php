<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'tenant_id', 'nome', 'taxa_juro', 'taxa_atraso',
        'taxa_fundo_social', 'data_inicio', 'data_fim', 'estado', 'datas_encontro'
    ];

    protected $casts = [
        'datas_encontro' => 'array',
    ];

    public function tenant()       { return $this->belongsTo(Tenant::class); }
    public function membros()      { return $this->hasMany(Membro::class); }
    public function contribuicoes(){ return $this->hasMany(Contribuicao::class); }
    public function emprestimos()  { return $this->hasMany(Emprestimo::class); }

    public function mesesComEncontro(int $ano): array
    {
        $datas = $this->datas_encontro ?? [];
        $meses = [];
        foreach ($datas as $data) {
            $d = \Carbon\Carbon::parse($data);
            if ($d->year == $ano) {
                $meses[] = $d->month;
            }
        }
        return array_unique($meses);
    }
}
