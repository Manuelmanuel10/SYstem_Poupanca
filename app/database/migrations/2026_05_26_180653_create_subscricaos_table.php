<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscricaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('plano'); // basico, standard, premium
            $table->decimal('valor', 8, 2);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('estado')->default('ativo');
            $table->string('metodo_pagamento')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('subscricaos'); }
};
