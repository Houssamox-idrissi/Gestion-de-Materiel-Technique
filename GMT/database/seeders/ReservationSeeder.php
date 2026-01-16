<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Materiel;
use App\Models\User;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenir les matériels disponibles
        $materiels = Materiel::where('statut', 'disponible')->get();

        if ($materiels->isEmpty()) {
            $this->command->info('Aucun matériel disponible trouvé. Création de matériels...');

            // Créer quelques matériels si aucun n'existe
            $materiels = Materiel::factory()->count(10)->create(['statut' => 'disponible']);
        }

        // Obtenir les utilisateurs
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé. Création d\'utilisateurs...');

            // Créer des utilisateurs avec des vrais noms français
            $us = [
                ['name' => 'Houssam', 'email' => 'houssam@gmail.com', 'role' => 'etudiant'],
                ['name' => 'Mouhcine', 'email' => 'mouhcine@gmail.com', 'role' => 'etudiant'],
                ['name' => 'Ahmad', 'email' => 'ahmad@gmail.com', 'role' => 'etudiant'],
                ['name' => 'Anas', 'email' => 'anas@gmail.com', 'role' => 'etudiant'],
                ['name' => 'Badr', 'email' => 'badr@gmail.com', 'role' => 'etudiant'],
                ['name' => 'Adam', 'email' => 'adam@gmail.com', 'role' => 'admin'],
                ['name' => 'Julie Moreau', 'email' => 'julie.moreau@email.com', 'role' => 'admin'],
            ];

            foreach ($us as $userData) {
                User::create(array_merge($userData, [
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]));
            }

            $users = User::all();
        }

        // Vrais objets de réservation en français
        $objetsReservation = [
            'TP Réseaux Informatiques',
            'Projet Arduino - Contrôle d\'accès',
            'Laboratoire de Programmation C++',
            'Travaux Pratiques Électronique',
            'Projet de Fin d\'Études - Robotique',
            'Cours d\'Initiation à la IoT',
            'Workshop Raspberry Pi',
            'Session de Débogage Matériel',
            'Développement Application Mobile',
            'Test de Compatibilité Matérielle',
            'Analyse de Performance Serveur',
            'Configuration Switch Cisco',
            'Étude de Faisabilité Projet',
            'Préparation Examen Pratique',
            'Démonstration Client'
        ];

        // Statuts disponibles
        $statuts = ['en_attente', 'confirmee', 'annulee', 'terminee'];

        // Créer des réservations réalistes
        $reservations = [];

        for ($i = 0; $i < 25; $i++) {
            $dateReservation = Carbon::now()->addDays(rand(1, 30));
            $heureDebut = rand(8, 16) . ':00';
            $heureFin = (rand(8, 16) + rand(1, 3)) . ':00';

            // Assurer que l'heure de fin est après l'heure de début
            if ((int)str_replace(':', '', $heureFin) <= (int)str_replace(':', '', $heureDebut)) {
                $heureFin = ((int)substr($heureDebut, 0, 2) + 2) . ':00';
            }

            $materiel = $materiels->random();
            $user = $users->where('role', 'etudiant')->random();
            $statut = $statuts[array_rand($statuts)];

            $reservations[] = [
                'materiel_id' => $materiel->id,
                'user_id' => $user->id,
                'date_reservation' => $dateReservation->format('Y-m-d'),
                'heure_debut' => $heureDebut,
                'heure_fin' => $heureFin,
                'objet' => $objetsReservation[array_rand($objetsReservation)],
                'commentaire' => $this->getCommentaireAleatoire(),
                'statut' => $statut,
                'check_out_at' => $statut === 'terminee' || $statut === 'confirmee' ? Carbon::now()->subDays(rand(1, 5)) : null,
                'check_in_at' => $statut === 'terminee' ? Carbon::now()->subDays(rand(0, 4)) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Mettre à jour le statut du matériel si nécessaire
            if (in_array($statut, ['confirmee', 'terminee'])) {
                $materiel->update(['statut' => 'reserve']);
            }
        }

        // Insérer les réservations
        Reservation::insert($reservations);

        $this->command->info('25 réservations créées avec des vrais noms français !');
    }

    /**
     * Générer un commentaire aléatoire réaliste
     */
    private function getCommentaireAleatoire(): string
    {
        $commentaires = [
            'Besoin d\'un écran HDMI supplémentaire si possible.',
            'Matériel à utiliser pour le projet de groupe.',
            'Prévoir une multiprise.',
            'Pour démonstration avec les clients.',
            'Session de formation des nouveaux étudiants.',
            'Test de la nouvelle configuration réseau.',
            'Préparation pour la soutenance.',
            'Équipe de 4 personnes.',
            'Besoin d\'accès internet stable.',
            'Matériel nécessaire pour les tests de performance.',
            'Pour le club informatique.',
            'Session de mentorat.',
            'Projet de recherche.',
            'Atelier ouvert à tous les étudiants.',
            'Matériel pour backup de données.',
            '',
            null
        ];

        return $commentaires[array_rand($commentaires)] ?? '';
    }
}
