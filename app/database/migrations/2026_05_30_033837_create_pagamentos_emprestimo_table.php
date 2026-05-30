<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pagamentos_emprestimo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emprestimo_id')->constrained()->onDelete('cascade');
            $table->foreignId('membro_id')->constrained()->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->date('data');
            $table->string('mes_referencia');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pagamentos_emprestimo'); }
};