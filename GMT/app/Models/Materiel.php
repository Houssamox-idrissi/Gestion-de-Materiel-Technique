<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Materiel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'numero_serie',
        'description',
        'categorie_id',
        'localisation',
        'statut',
        'qr_code_path'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function estDisponible($date, $heureDebut, $heureFin)
    {
        return !$this->reservations()
            ->where('date_reservation', $date)
            ->where('statut', '!=', 'annulee')
            ->where(function($query) use ($heureDebut, $heureFin) {
                $query->whereBetween('heure_debut', [$heureDebut, $heureFin])
                      ->orWhereBetween('heure_fin', [$heureDebut, $heureFin]);
            })
            ->exists();
    }

    public function scopeDisponible($query)
    {
        return $query->where('statut', 'disponible');
    }

    public function scopeParCategorie($query, $categorieId)
    {
        return $query->where('categorie_id', $categorieId);
    }

public function genererQRCode()
{
    $url = route('materiels.show', $this->id);
    $filename = 'qrcodes/materiel-' . $this->id . '.svg';
    $path = public_path($filename);
    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
        ->size(200)
        ->generate($url, $path);
    $this->update(['qr_code_path' => $filename]);
    return $filename;
}
}
