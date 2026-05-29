<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscricao extends Model
{
    protected $fillable = [
        'tenant_id',
        'plano',
        'valor',
        'data_inicio',
        'data_fim',
        'estado',
        'metodo_pagamento',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim'    => 'date',
        'valor'       => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Verifica se a subscrição está activa e não expirou.
     */
    public function estaActiva(): bool
    {
        return $this->estado === 'ativo' && $this->data_fim->isFuture();
    }

    /**
     * Dias restantes da subscrição.
     */
    public function diasRestantes(): int
    {
        return max(0, now()->diffInDays($this->data_fim, false));
    }
}
