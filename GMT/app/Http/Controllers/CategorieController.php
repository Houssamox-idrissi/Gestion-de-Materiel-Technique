<?php
namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role != 'admin') {
                abort(403, 'Accès réservé aux administrateurs.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $categories = Categorie::withCount('materiels')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string'
        ]);

        Categorie::create([
            'nom' => $request->nom,
            'description' => $request->description
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function show(Categorie $categorie)
    {
        $materiels = $categorie->materiels()->paginate(10);
        return view('categories.show', compact('categorie', 'materiels'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'nom' => 'required|string|max:100|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string'
        ]);

        $categorie->update([
            'nom' => $request->nom,
            'description' => $request->description
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $categorie)
    {
        if ($categorie->materiels()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer : catégorie utilisée par des matériels.');
        }

        $categorie->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}
