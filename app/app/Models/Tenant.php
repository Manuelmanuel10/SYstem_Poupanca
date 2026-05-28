<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = ['user_id', 'nome_negocio', 'telefone', 'plano', 'estado', 'data_expiracao'];

    public function user() { return $this->belongsTo(User::class); }
    public function grupos() { return $this->hasMany(Grupo::class); }
    public function subscricoes() { return $this->hasMany(Subscricao::class); }
}
