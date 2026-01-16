<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Materiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = null;

        if (Auth::user()->role == 'admin') {
            $query = Reservation::with(['materiel', 'utilisateur']);
        } else {
            $query = Reservation::with('materiel')
                ->where('user_id', Auth::id());
        }

        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('materiel', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                    ->orWhere('numero_serie', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        if ($request->has('date_reservation') && $request->date_reservation != '') {
            $query->whereDate('date_reservation', $request->date_reservation);
        }

        $reservations = $query->latest()->paginate(15);

        return view('reservations.index', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        try {
            $validated = $request->validate([
                'materiel_id' => 'required|exists:materiels,id',
                'date_reservation' => 'required|date',
                'heure_debut' => 'required|date_format:H:i',  // Form sends HH:MM
                'heure_fin' => 'required|date_format:H:i|after:heure_debut',  // Form sends HH:MM
                'objet' => 'required|string|max:255',
                'commentaire' => 'nullable|string',
                'statut' => 'sometimes|in:en_attente,confirmee,annulee,terminee',
            ]);

            // ADD SECONDS FOR DATABASE
            $validated['heure_debut'] = $validated['heure_debut'] . ':00';
            $validated['heure_fin'] = $validated['heure_fin'] . ':00';

            $reservation->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Réservation mise à jour avec succès.',
                'redirect' => route('reservations.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Veuillez corriger les erreurs dans le formulaire.'
            ], 422);
        }
    }

    public function showJson(Reservation $reservation)
    {
        return response()->json([
            'id' => $reservation->id,
            'materiel_id' => $reservation->materiel_id,
            'date_reservation' => $reservation->date_reservation,
            // REMOVE SECONDS for form (HH:MM:SS -> HH:MM)
            'heure_debut' => substr($reservation->heure_debut, 0, 5),
            'heure_fin' => substr($reservation->heure_fin, 0, 5),
            'objet' => $reservation->objet,
            'commentaire' => $reservation->commentaire,
            'statut' => $reservation->statut,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'materiel_id' => 'required|exists:materiels,id',
            'date_reservation' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'objet' => 'required|string|max:255',
            'commentaire' => 'nullable|string',
        ]);

        $materiel = Materiel::find($request->materiel_id);

        // Vérifier la disponibilité avec une méthode plus précise
        $isAvailable = $this->checkAvailability(
            $request->materiel_id,
            $request->date_reservation,
            $request->heure_debut,
            $request->heure_fin,
            null // Pas d'ID à exclure pour une nouvelle réservation
        );

        if (!$isAvailable) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'heure_debut' => ['Ce créneau horaire est déjà réservé. Veuillez choisir un autre horaire.'],
                    'heure_fin' => ['Ce créneau horaire est déjà réservé. Veuillez choisir un autre horaire.']
                ]
            ], 422);
        }

        $reservation = Reservation::create([
            'materiel_id' => $request->materiel_id,
            'user_id' => Auth::id(),
            'date_reservation' => $request->date_reservation,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'objet' => $request->objet,
            'commentaire' => $request->commentaire,
            'statut' => (Auth::user()->role == 'admin') ? 'confirmee' : 'en_attente',
        ]);

        if (Auth::user()->role == 'admin') {
            $materiel->update(['statut' => 'reserve']);
        }

        // STOCKER LA SESSION POUR LE MESSAGE
        session()->flash('reservation_created', true);
        session()->flash('success', (Auth::user()->role == 'admin')
            ? 'Réservation confirmée avec succès.'
            : 'Réservation soumise en attente de confirmation.');

        return response()->json([
            'success' => true,
            'message' => (Auth::user()->role == 'admin')
                ? 'Réservation confirmée avec succès.'
                : 'Réservation soumise en attente de confirmation.',
            'redirect' => route('reservations.index')
        ]);
    }


    public function valider(Reservation $reservation)
    {
        if (Auth::user()->role != 'admin') {
            abort(403, 'Action réservée aux administrateurs.');
        }

        $reservation->update(['statut' => 'confirmee']);
        $reservation->materiel()->update(['statut' => 'reserve']);

        return back()->with('success', 'Réservation validée.');
    }

    public function annuler(Reservation $reservation)
    {
        if (Auth::user()->role != 'admin' && $reservation->user_id != Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $reservation->update(['statut' => 'annulee']);
        $hasActiveReservations = Reservation::where('materiel_id', $reservation->materiel_id)
            ->where('statut', 'confirmee')
            ->exists();

        if (!$hasActiveReservations) {
            $reservation->materiel()->update(['statut' => 'disponible']);
        }

        return back()->with('success', 'Réservation annulée.');
    }

    public function checkout(Reservation $reservation)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        $reservation->update(['check_out_at' => now()]);

        return back()->with('success', 'Check-out enregistré.');
    }

    public function checkin(Reservation $reservation)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        $reservation->update([
            'check_in_at' => now(),
            'statut' => 'terminee'
        ]);

        $reservation->materiel()->update(['statut' => 'disponible']);

        return back()->with('success', 'Check-in enregistré. Matériel marqué comme disponible.');
    }

    // Additional admin methods for managing all reservations can be added here
    public function adminIndex(Request $request)
    {
        $this->authorize('viewAny', Reservation::class);

        $status = $request->get('status');
        $query = Reservation::with(['user', 'materiel']);

        if ($status) {
            $query->where('status', $status);
        }

        $reservations = $query->latest()->paginate(20);

        $stats = [
            'total' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'rejected' => Reservation::where('status', 'rejected')->count(),
            'returned' => Reservation::where('status', 'returned')->count(),
        ];

        return view('reservations.admin-index', compact('reservations', 'stats'));
    }

    /**
     * Edit reservation (admin only)
     */
    public function adminEdit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        return view('reservations.admin-edit', compact('reservation'));
    }

    /**
     * Update reservation (admin only)
     */
    public function adminUpdate(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,returned',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $reservation->update($validated);

        // If status changed to approved, update material availability
        if ($reservation->wasChanged('status') && $validated['status'] == 'approved') {
            $reservation->materiel->update(['est_disponible' => false]);
        }

        // If status changed to returned, update material availability
        if ($reservation->wasChanged('status') && $validated['status'] == 'returned') {
            $reservation->materiel->update(['est_disponible' => true]);
        }

        return redirect()->route('reservations.admin')
            ->with('success', 'Réservation mise à jour avec succès.');
    }




    /**
     * Vérifie la disponibilité d'un créneau horaire
     */
    private function checkAvailability($materielId, $date, $heureDebut, $heureFin, $excludeReservationId = null)
    {
        // Convertir les heures en objets DateTime pour comparaison
        $debut = \Carbon\Carbon::createFromFormat('H:i', $heureDebut);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $heureFin);

        // Vérifier que l'heure de fin est après l'heure de début
        if ($fin <= $debut) {
            return false;
        }

        // Chercher les réservations en conflit
        $query = Reservation::where('materiel_id', $materielId)
            ->where('date_reservation', $date)
            ->where('statut', '!=', 'annulee') // Exclure les réservations annulées
            ->where(function ($q) use ($debut, $fin) {
                // Vérifier les chevauchements
                $q->where(function ($q2) use ($debut, $fin) {
                    // Cas 1: La nouvelle réservation commence pendant une réservation existante
                    $q2->where('heure_debut', '<=', $debut->format('H:i'))
                        ->where('heure_fin', '>', $debut->format('H:i'));
                })->orWhere(function ($q2) use ($debut, $fin) {
                    // Cas 2: La nouvelle réservation se termine pendant une réservation existante
                    $q2->where('heure_debut', '<', $fin->format('H:i'))
                        ->where('heure_fin', '>=', $fin->format('H:i'));
                })->orWhere(function ($q2) use ($debut, $fin) {
                    // Cas 3: La nouvelle réservation englobe une réservation existante
                    $q2->where('heure_debut', '>=', $debut->format('H:i'))
                        ->where('heure_fin', '<=', $fin->format('H:i'));
                })->orWhere(function ($q2) use ($debut, $fin) {
                    // Cas 4: La réservation existante englobe la nouvelle réservation
                    $q2->where('heure_debut', '<=', $debut->format('H:i'))
                        ->where('heure_fin', '>=', $fin->format('H:i'));
                });
            });

        // Exclure une réservation spécifique (pour l'édition)
        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        // Si on trouve des réservations en conflit, le créneau n'est pas disponible
        return $query->count() === 0;
    }

    public function checkAvailabilityAjax(Request $request)
    {
        $request->validate([
            'materiel_id' => 'required|exists:materiels,id',
            'date_reservation' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'exclude_reservation_id' => 'nullable|exists:reservations,id'
        ]);

        $isAvailable = $this->checkAvailability(
            $request->materiel_id,
            $request->date_reservation,
            $request->heure_debut,
            $request->heure_fin,
            $request->exclude_reservation_id
        );

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable
                ? 'Ce créneau est disponible.'
                : 'Ce créneau est déjà réservé.'
        ]);
    }

    public function json(Reservation $reservation)
    {
        return response()->json($reservation);
    }
}
