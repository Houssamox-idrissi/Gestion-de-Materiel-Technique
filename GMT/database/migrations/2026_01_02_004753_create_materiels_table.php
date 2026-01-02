<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('numero_serie')->unique();
            $table->text('description')->nullable();
            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
            $table->string('localisation');
            $table->enum('statut', [
                'disponible',
                'reserve',
                'maintenance',
                'hors_service'
            ])->default('disponible');
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
