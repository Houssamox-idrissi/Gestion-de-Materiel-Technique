<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        $categories = Categorie::withCount('materiels')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nom' => 'required|string|max:100|unique:categories',
                'description' => 'nullable|string'
            ], [
                'nom.required' => 'Le nom de la catégorie est obligatoire.',
                'nom.unique' => 'Cette catégorie existe déjà.',
                'nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            ]);

            $categorie = Categorie::create([
                'nom' => $request->nom,
                'description' => $request->description
            ]);

            // Retourner avec un message de succès
            return redirect()->route('categories.index')
                ->with('success', 'Catégorie créée avec succès.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création.')
                ->withInput();
        }
    }

    public function update(Request $request, Categorie $categorie)
    {
        try {
            $request->validate([
                'nom' => 'required|string|max:100|unique:categories,nom,' . $categorie->id,
                'description' => 'nullable|string'
            ], [
                'nom.required' => 'Le nom de la catégorie est obligatoire.',
                'nom.unique' => 'Cette catégorie existe déjà.',
                'nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            ]);

            $categorie->update([
                'nom' => $request->nom,
                'description' => $request->description
            ]);

            return redirect()->route('categories.index')
                ->with('success', 'Catégorie mise à jour avec succès.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour.')
                ->withInput();
        }
    }

    public function show(Categorie $categorie)
    {
        $materiels = $categorie->materiels()->paginate(10);
        return view('categories.show', compact('categorie', 'materiels'));
    }



    public function destroy(Categorie $category)
    {
        if ($category->materiels()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer : catégorie utilisée par des matériels.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}
