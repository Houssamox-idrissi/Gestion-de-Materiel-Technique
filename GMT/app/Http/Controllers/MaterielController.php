<?php

namespace App\Http\Controllers;

use App\Models\Materiel;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterielController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

  public function index(Request $request)
{
    $materiels = Materiel::with('categorie')
        ->where(function ($query) use ($request) {

            // 🔍 SEARCH
            if ($request->filled('search')) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'LIKE', "%{$search}%")
                      ->orWhere('numero_serie', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('categorie_id')) {
                $query->where('categorie_id', $request->categorie_id);
            }

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();

    $categories = Categorie::orderBy('nom')->get();

    return view('materiels.index', compact('materiels', 'categories'));
}


    public function create()
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        $categories = Categorie::all();
        return view('materiels.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'numero_serie' => 'required|string|unique:materiels',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'localisation' => 'required|string|max:255',
        ]);

        $materiel = Materiel::create($request->all());
        $materiel->genererQRCode();

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel ajouté avec succès.');
    }

    public function show(Materiel $materiel)
    {
        return view('materiels.show', compact('materiel'));
    }

    public function edit(Materiel $materiel)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        $categories = Categorie::all();
        return view('materiels.edit', compact('materiel', 'categories'));
    }

    public function update(Request $request, Materiel $materiel)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'numero_serie' => 'required|string|unique:materiels,numero_serie,' . $materiel->id,
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'localisation' => 'required|string|max:255',
            'statut' => 'required|in:disponible,reserve,maintenance,hors_service',
        ]);

        $materiel->update($request->all());

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel mis à jour.');
    }

    public function destroy(Materiel $materiel)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        $materiel->delete();

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel supprimé.');
    }

    public function apiShow(Materiel $materiel)
    {
        return response()->json([
            'id' => $materiel->id,
            'nom' => $materiel->nom,
            'numero_serie' => $materiel->numero_serie,
            'description' => $materiel->description,
            'categorie_id' => $materiel->categorie_id,
            'localisation' => $materiel->localisation,
            'statut' => $materiel->statut,
            'qr_code_path' => $materiel->qr_code_path,
        ]);
    }
    public function json(Materiel $materiel)
{
    return response()->json($materiel);
}

}
