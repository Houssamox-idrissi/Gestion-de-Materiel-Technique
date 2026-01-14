@extends('layouts.app')

@section('title', $categorie->nom)
@section('icon', 'fa-tag')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Catégories</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $categorie->nom }}</li>
@endsection

@section('header-buttons')
    <div class="flex space-x-2">
        <a href="{{ route('categories.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
        @if(Auth::user()->role == 'admin')
            <button onclick="openEditModal({{ $categorie->id }}, '{{ $categorie->nom }}', '{{ $categorie->description }}')"
                    class="btn-primary">
                <i class="fas fa-edit me-2"></i> Modifier
            </button>
        @endif
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Category Info -->
            <div class="lg:col-span-2">
                <!-- Category Card -->
                <div class="category-card p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-tag text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $categorie->nom }}</h2>
                                <p class="text-gray-500">ID: {{ $categorie->id }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $categorie->materiels()->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $categorie->materiels()->count() }} matériel(s)
                        </span>
                    </div>

                    @if($categorie->description)
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Description</h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-700">{{ $categorie->description }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informations</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Créée le</p>
                                <p class="font-medium">{{ $categorie->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Dernière modification</p>
                                <p class="font-medium">{{ $categorie->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Materials List -->
                <div class="category-card">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-boxes me-2"></i> Matériels dans cette catégorie
                        </h3>
                    </div>

                    @if($materiels->isEmpty())
                        <div class="p-8 text-center">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-700 mb-2">Aucun matériel</h3>
                            <p class="text-gray-500">Cette catégorie ne contient aucun matériel pour le moment.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Matériel
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Référence
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantité
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($materiels as $materiel)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $materiel->nom }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $materiel->reference }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $materiel->quantite > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $materiel->quantite }} disponible(s)
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($materiel->quantite > 0)
                                                <span class="badge badge-success">Disponible</span>
                                            @else
                                                <span class="badge badge-danger">Épuisé</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $materiels->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="category-card p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('materiels.index') }}?categorie={{ $categorie->id }}"
                           class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye text-blue-600 mr-3"></i>
                            <span>Voir tous les matériels</span>
                        </a>
                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('materiels.create') }}?categorie={{ $categorie->id }}"
                           class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-plus text-green-600 mr-3"></i>
                            <span>Ajouter un matériel</span>
                        </a>
                        @if($categorie->materiels()->count() == 0)
                        <form action="{{ route('categories.destroy', $categorie) }}" method="POST"
                              onsubmit="return confirm('Supprimer définitivement cette catégorie ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center p-3 border border-gray-200 rounded-lg
                                       hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-colors text-left">
                                <i class="fas fa-trash text-red-600 mr-3"></i>
                                <span>Supprimer la catégorie</span>
                            </button>
                        </form>
                        @else
                        <div class="p-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
                            <i class="fas fa-lock mr-3"></i>
                            <span>Suppression impossible (contient des matériels)</span>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="category-card p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Total matériels</p>
                            <p class="text-2xl font-bold">{{ $categorie->materiels()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Matériels disponibles</p>
                            <p class="text-2xl font-bold">{{ $categorie->materiels()->where('quantite', '>', 0)->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Matériels épuisés</p>
                            <p class="text-2xl font-bold">{{ $categorie->materiels()->where('quantite', '<=', 0)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay" onclick="closeEditModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="modal-content modal-enter">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Modifier la catégorie</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="editForm" method="POST" action="{{ route('categories.update', $categorie) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="edit_nom" class="form-label">Nom de la catégorie *</label>
                            <input type="text" id="edit_nom" name="nom" value="{{ $categorie->nom }}"
                                   class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea id="edit_description" name="description" rows="3"
                                      class="form-textarea">{{ $categorie->description }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeEditModal()" class="btn-secondary">
                                Annuler
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save me-2"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(id, nom, description) {
        document.getElementById('edit_nom').value = nom;
        document.getElementById('edit_description').value = description || '';
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditModal();
    });
</script>
@endpush
