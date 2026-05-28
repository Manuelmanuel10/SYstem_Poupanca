<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contribuicaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained()->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained()->onDelete('cascade');
            $table->string('tipo');
            $table->decimal('valor', 10, 2);
            $table->date('data');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('contribuicaos'); }
};
