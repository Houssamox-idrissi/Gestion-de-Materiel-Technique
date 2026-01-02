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
        if (Auth::user()->role == 'admin') {
            $reservations = Reservation::with(['materiel', 'utilisateur'])
                ->latest()
                ->paginate(15);
        } else {
            $reservations = Reservation::with('materiel')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        }

        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $materiel = null;
        if ($request->has('materiel_id')) {
            $materiel = Materiel::find($request->materiel_id);
        }

        $materiels = Materiel::where('statut', 'disponible')->get();

        return view('reservations.create', compact('materiels', 'materiel'));
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

        if (!$materiel->estDisponible($request->date_reservation, $request->heure_debut, $request->heure_fin)) {
            return back()->withErrors([
                'heure_debut' => 'Ce créneau n\'est pas disponible. Veuillez choisir un autre horaire.'
            ])->withInput();
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

        return redirect()->route('reservations.index')
            ->with('success', (Auth::user()->role == 'admin')
                ? 'Réservation confirmée avec succès.'
                : 'Réservation soumise en attente de confirmation.');
    }

    public function show(Reservation $reservation)
    {
        if (Auth::user()->role != 'admin' && $reservation->user_id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('reservations.show', compact('reservation'));
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
}
