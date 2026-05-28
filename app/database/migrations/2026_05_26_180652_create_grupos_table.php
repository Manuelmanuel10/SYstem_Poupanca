<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->decimal('taxa_juro', 5, 2)->default(20.00);
            $table->decimal('taxa_atraso', 8, 2)->default(50.00);
            $table->decimal('taxa_fundo_social', 8, 2)->default(100.00);
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->string('estado')->default('ativo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('grupos'); }
};