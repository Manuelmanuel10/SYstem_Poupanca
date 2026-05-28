<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome_negocio');
            $table->string('telefone')->nullable();
            $table->string('plano')->default('basico'); // basico, standard, premium
            $table->string('estado')->default('ativo'); // ativo, suspenso, cancelado
            $table->date('data_expiracao')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tenants'); }
};