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
        $query = Materiel::with('categorie');

        if ($request->has('search')) {
            $query->where('nom', 'like', '%'.$request->search.'%')
                  ->orWhere('numero_serie', 'like', '%'.$request->search.'%');
        }

        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        $materiels = $query->paginate(10);
        $categories = Categorie::all();

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
            'numero_serie' => 'required|string|unique:materiels,numero_serie,'.$materiel->id,
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
}
