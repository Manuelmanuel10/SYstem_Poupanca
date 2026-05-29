<?php

namespace Database\Seeders;

use App\Models\Contribuicao;
use App\Models\Emprestimo;
use App\Models\Grupo;
use App\Models\Membro;
use App\Models\Subscricao;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Utilizador gestor ──────────────────────────────────────────────
        $user = User::updateOrCreate(
            ['email' => 'demo@poupanca.mz'],
            [
                'name'     => 'Manuel da Silva',
                'password' => Hash::make('demo1234'),
            ]
        );

        // ── 2. Tenant ─────────────────────────────────────────────────────────
        $tenant = Tenant::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nome_negocio'   => 'Grupo Solidário Nampula',
                'telefone'       => '+258 84 123 4567',
                'plano'          => 'standard',
                'estado'         => 'ativo',
                'data_expiracao' => now()->addDays(30),
            ]
        );

        // ── 3. Subscrição ─────────────────────────────────────────────────────
        Subscricao::firstOrCreate(
            ['tenant_id' => $tenant->id, 'plano' => 'standard'],
            [
                'valor'            => 1000.00,
                'data_inicio'      => now()->toDateString(),
                'data_fim'         => now()->addDays(30)->toDateString(),
                'estado'           => 'ativo',
                'metodo_pagamento' => 'manual',
            ]
        );

        // ── 4. Grupo de poupança ──────────────────────────────────────────────
        $grupo = Grupo::firstOrCreate(
            ['tenant_id' => $tenant->id, 'nome' => 'Poupança Março–Junho 2026'],
            [
                'taxa_juro'         => 20.00,  // 20% ao mês
                'taxa_atraso'       => 50.00,  // 50 MZN de multa
                'taxa_fundo_social' => 100.00, // 100 MZN fundo social fixo
                'data_inicio'       => '2026-03-01',
                'estado'            => 'ativo',
            ]
        );

        // ── 5. Membros ────────────────────────────────────────────────────────
        $membrosData = [
            ['nome' => 'Manuel da Silva',   'telefone' => '+258 84 123 4567', 'cargo' => 'presidente'],
            ['nome' => 'Ana Machava',       'telefone' => '+258 82 234 5678', 'cargo' => 'secretaria'],
            ['nome' => 'Carlos Tembe',      'telefone' => '+258 86 345 6789', 'cargo' => 'tesoureiro'],
            ['nome' => 'Fátima Cossa',      'telefone' => '+258 84 456 7890', 'cargo' => 'membro'],
            ['nome' => 'João Nhantumbo',    'telefone' => '+258 82 567 8901', 'cargo' => 'membro'],
            ['nome' => 'Rosa Bila',         'telefone' => '+258 86 678 9012', 'cargo' => 'membro'],
        ];

        $membros = [];
        foreach ($membrosData as $dados) {
            $membros[] = Membro::firstOrCreate(
                ['grupo_id' => $grupo->id, 'nome' => $dados['nome']],
                [
                    'telefone' => $dados['telefone'],
                    'cargo'    => $dados['cargo'],
                    'estado'   => 'ativo',
                ]
            );
        }

        // ── 6. Contribuições — 3 meses (Março, Abril, Maio) ──────────────────
        // Cada membro contribui mensalmente:
        //   • 500 MZN de poupança
        //   • 100 MZN de fundo social
        // Alguns membros têm multa de atraso registada

        $meses = [
            ['data' => '2026-03-05', 'obs' => 'Contribuição de Março'],
            ['data' => '2026-04-03', 'obs' => 'Contribuição de Abril'],
            ['data' => '2026-05-07', 'obs' => 'Contribuição de Maio'],
        ];

        // Só cria contribuições se ainda não existirem
        if (Contribuicao::where('grupo_id', $grupo->id)->count() === 0) {

            foreach ($meses as $mes) {
                foreach ($membros as $membro) {
                    // Poupança mensal
                    Contribuicao::create([
                        'grupo_id'   => $grupo->id,
                        'membro_id'  => $membro->id,
                        'tipo'       => 'poupanca',
                        'valor'      => 500.00,
                        'data'       => $mes['data'],
                        'observacao' => $mes['obs'],
                    ]);

                    // Fundo social mensal
                    Contribuicao::create([
                        'grupo_id'   => $grupo->id,
                        'membro_id'  => $membro->id,
                        'tipo'       => 'fundo_social',
                        'valor'      => 100.00,
                        'data'       => $mes['data'],
                        'observacao' => $mes['obs'],
                    ]);
                }
            }

            // Multas — 2 membros atrasaram em Abril
            Contribuicao::create([
                'grupo_id'   => $grupo->id,
                'membro_id'  => $membros[3]->id, // Fátima
                'tipo'       => 'atraso',
                'valor'      => 50.00,
                'data'       => '2026-04-12',
                'observacao' => 'Atraso no pagamento de Abril',
            ]);

            Contribuicao::create([
                'grupo_id'   => $grupo->id,
                'membro_id'  => $membros[4]->id, // João
                'tipo'       => 'atraso',
                'valor'      => 50.00,
                'data'       => '2026-04-15',
                'observacao' => 'Atraso no pagamento de Abril',
            ]);
        }

        // ── 7. Empréstimos ────────────────────────────────────────────────────
        // Só cria empréstimos se ainda não existirem
        if (Emprestimo::where('grupo_id', $grupo->id)->count() === 0) {

            // Carlos pediu 2.000 MZN — já pagou (estado: pago)
            // Juros 20%/mês × 2 meses = M = 2000 × (1.20)^2 = 2.880 MZN
            Emprestimo::create([
                'grupo_id'        => $grupo->id,
                'membro_id'       => $membros[2]->id, // Carlos
                'valor_principal' => 2000.00,
                'taxa_juro'       => 20.00,
                'valor_devido'    => 2880.00,
                'data_emprestimo' => '2026-03-10',
                'data_vencimento' => '2026-05-10',
                'estado'          => 'pago',
            ]);

            // João pediu 1.500 MZN — ainda pendente
            // Juros 20%/mês × 1 mês = M = 1500 × 1.20 = 1.800 MZN
            Emprestimo::create([
                'grupo_id'        => $grupo->id,
                'membro_id'       => $membros[4]->id, // João
                'valor_principal' => 1500.00,
                'taxa_juro'       => 20.00,
                'valor_devido'    => 1800.00,
                'data_emprestimo' => '2026-04-20',
                'data_vencimento' => '2026-05-20',
                'estado'          => 'pendente',
            ]);

            // Rosa pediu 1.000 MZN — em atraso
            // Juros 20%/mês × 1 mês = M = 1000 × 1.20 = 1.200 MZN
            Emprestimo::create([
                'grupo_id'        => $grupo->id,
                'membro_id'       => $membros[5]->id, // Rosa
                'valor_principal' => 1000.00,
                'taxa_juro'       => 20.00,
                'valor_devido'    => 1200.00,
                'data_emprestimo' => '2026-04-01',
                'data_vencimento' => '2026-05-01',
                'estado'          => 'atrasado',
            ]);
        }

        // ── Resumo final ──────────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('✅  Dados de demonstração criados com sucesso!');
        $this->command->info('');
        $this->command->info('  🔑  E-mail  : demo@poupanca.mz');
        $this->command->info('  🔑  Password: demo1234');
        $this->command->info('');
        $this->command->info('  👥  Grupo   : Poupança Março–Junho 2026');
        $this->command->info('  👤  Membros : 6 (presidente, secretária, tesoureiro + 3 membros)');
        $this->command->info('  💰  Contribuições: 3 meses de poupança + fundo social + 2 multas');
        $this->command->info('  📋  Empréstimos: 1 pago | 1 pendente | 1 em atraso');
        $this->command->info('');
    }
}
