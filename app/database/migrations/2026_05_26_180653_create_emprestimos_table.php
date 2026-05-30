<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained()->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained()->onDelete('cascade');
            $table->decimal('valor_principal', 10, 2);
            $table->decimal('taxa_juro', 5, 2);
            $table->decimal('valor_devido', 10, 2);
            $table->date('data_emprestimo');
            $table->date('data_vencimento');
            $table->string('estado')->default('pendente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('emprestimos'); }
};
