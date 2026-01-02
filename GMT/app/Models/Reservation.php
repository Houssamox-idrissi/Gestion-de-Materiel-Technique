<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'materiel_id',
        'user_id',
        'date_reservation',
        'heure_debut',
        'heure_fin',
        'objet',
        'commentaire',
        'statut',
        'check_out_at',
        'check_in_at'
    ];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function duree()
    {
        $debut = strtotime($this->heure_debut);
        $fin = strtotime($this->heure_fin);
        $difference = $fin - $debut;

        $heures = floor($difference / 3600);
        $minutes = floor(($difference % 3600) / 60);

        return sprintf('%02d:%02d', $heures, $minutes);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeConfirmee($query)
    {
        return $query->where('statut', 'confirmee');
    }

    public function scopePourMateriel($query, $materielId)
    {
        return $query->where('materiel_id', $materielId);
    }

    public function scopePourUtilisateur($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
