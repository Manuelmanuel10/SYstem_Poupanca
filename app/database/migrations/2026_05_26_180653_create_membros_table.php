<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('membros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->string('telefone')->nullable();
            $table->string('cargo')->default('membro'); // presidente, secretario, tesoureiro, guardiao, vice, membro
            $table->string('estado')->default('ativo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('membros'); }
};
