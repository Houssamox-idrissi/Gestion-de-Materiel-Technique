<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Materiel;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les utilisateurs
        $admin = User::create([
            'name' => 'Admin Lab',
            'email' => 'admin@lab.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'matricule' => 'ADM001',
            'telephone' => '0600000000',
            'departement' => 'Administration',
        ]);

        $etudiant1 = User::create([
            'name' => 'houssam',
            'email' => 'houssamt@isga.com',
            'password' => bcrypt('password123'),
            'role' => 'etudiant',
            'matricule' => 'ETU2024001',
            'telephone' => '0612345678',
            'departement' => 'Informatique',
        ]);

        $etudiant2 = User::create([
            'name' => 'Marie Martin',
            'email' => 'moouhsine@isga.com',
            'password' => bcrypt('password123'),
            'role' => 'etudiant',
            'matricule' => 'ETU2024002',
            'telephone' => '0623456789',
            'departement' => 'Électronique',
        ]);

        // Créer les catégories
        $categories = [
            ['nom' => 'Électronique', 'description' => 'Matériel électronique de mesure'],
            ['nom' => 'Informatique', 'description' => 'Ordinateurs et périphériques'],
            ['nom' => 'Mécanique', 'description' => 'Machines et outils mécaniques'],
            ['nom' => 'Laboratoire', 'description' => 'Équipement de laboratoire'],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }

        // Créer les matériels
        $materiels = [
            [
                'nom' => 'Oscilloscope numérique',
                'numero_serie' => 'OSC-2024-001',
                'description' => 'Oscilloscope 4 canaux, 100MHz',
                'categorie_id' => 1,
                'localisation' => 'Lab A-12',
                'statut' => 'disponible',
            ],
            [
                'nom' => 'Multimètre digital',
                'numero_serie' => 'MM-2024-001',
                'description' => 'Multimètre True RMS',
                'categorie_id' => 1,
                'localisation' => 'Lab B-05',
                'statut' => 'disponible',
            ],
            [
                'nom' => 'Station de soudage',
                'numero_serie' => 'SOLD-2024-001',
                'description' => 'Station à souder réglable',
                'categorie_id' => 1,
                'localisation' => 'Atelier Électronique',
                'statut' => 'maintenance',
            ],
            [
                'nom' => 'PC Portable Dell',
                'numero_serie' => 'PC-2024-001',
                'description' => 'Dell Latitude, i7, 16GB RAM',
                'categorie_id' => 2,
                'localisation' => 'Salle Info 3',
                'statut' => 'disponible',
            ],
            [
                'nom' => 'Imprimante 3D',
                'numero_serie' => '3DP-2024-001',
                'description' => 'Creality Ender 3 V2',
                'categorie_id' => 3,
                'localisation' => 'FabLab',
                'statut' => 'reserve',
            ],
        ];
        foreach ($materiels as $materielData) {
            $materiel = Materiel::create($materielData);

            // Utilise un try-catch pour éviter l'erreur
            try {
                $materiel->genererQRCode();
            } catch (\Exception $e) {
                // Si échec, crée juste un fichier texte
                $filename = 'qrcodes/materiel-' . $materiel->id . '.txt';
                $path = public_path($filename);
                file_put_contents($path, "QR Code pour: " . $materiel->nom);
                $materiel->update(['qr_code_path' => $filename]);
            }
        }



        // Créer des réservations
        $reservations = [
            [
                'materiel_id' => 1,
                'user_id' => $etudiant1->id,
                'date_reservation' => now()->addDays(1)->format('Y-m-d'),
                'heure_debut' => '10:00',
                'heure_fin' => '12:00',
                'objet' => 'Projet circuits électroniques',
                'statut' => 'confirmee',
            ],
            [
                'materiel_id' => 4,
                'user_id' => $etudiant2->id,
                'date_reservation' => now()->addDays(2)->format('Y-m-d'),
                'heure_debut' => '14:00',
                'heure_fin' => '16:00',
                'objet' => 'Programmation Arduino',
                'statut' => 'en_attente',
            ],
        ];

        foreach ($reservations as $reservation) {
            Reservation::create($reservation);
        }

        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->info('👨‍💼 Admin: admin@lab.com / password123');
        $this->command->info('👨‍🎓 Étudiants: jean.dupont@ecole.com / password123');
    }
}
