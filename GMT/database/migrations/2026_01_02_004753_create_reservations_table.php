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
       Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date_reservation');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('objet');
            $table->text('commentaire')->nullable();
            $table->enum('statut', [
                'en_attente',
                'confirmee',
                'annulee',
                'terminee'
            ])->default('en_attente');
            $table->timestamp('check_out_at')->nullable();
            $table->timestamp('check_in_at')->nullable();
            $table->timestamps();

            $table->index(['date_reservation', 'statut']);
            $table->index(['materiel_id', 'date_reservation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
