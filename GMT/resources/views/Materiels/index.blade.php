@extends('layouts.app')

@section('title', 'Gestion des Matériels')
@section('icon', 'fa-tools')
@section('breadcrumb')
    <li class="text-gray-500">/</li>
    <li class="text-gray-700">Matériels</li>
@endsection

@section('header-buttons')
    @if (Auth::user()->role == 'admin')
        <button onclick="openCreateModal()"
            class="px-4 py-2 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
            <i class="fas fa-plus-circle mr-2"></i> Nouveau Matériel
        </button>
    @endif
@endsection

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 mr-4 ring-1 ring-blue-100">
                        <i class="fas fa-tools text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1 font-medium">Total Matériels</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $materiels->total() }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-xl bg-gradient-to-br from-green-100 to-green-50 text-green-600 mr-4 ring-1 ring-green-100">
                        <i class="fas fa-check-circle text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1 font-medium">Disponibles</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Materiel::where('statut', 'disponible')->count() }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 text-yellow-600 mr-4 ring-1 ring-yellow-100">
                        <i class="fas fa-wrench text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1 font-medium">En Maintenance</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Materiel::where('statut', 'maintenance')->count() }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-xl bg-gradient-to-br from-red-100 to-red-50 text-red-600 mr-4 ring-1 ring-red-100">
                        <i class="fas fa-times-circle text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1 font-medium">Hors Service</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Materiel::where('statut', 'hors_service')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <form method="GET" action="{{ route('materiels.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search by Material Name -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par nom de matériel..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/10 focus:border-[#121929]/30 transition-all duration-200 placeholder-gray-400 text-gray-700"
                            onkeyup="if(event.key === 'Enter') this.form.submit()">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select name="categorie_id" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/10 focus:border-[#121929]/30 transition-all duration-200 text-gray-700">
                            <option value="">Toutes les catégories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('categorie_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="statut" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/10 focus:border-[#121929]/30 transition-all duration-200 text-gray-700">
                            <option value="">Tous les statuts</option>
                            <option value="disponible" {{ request('statut') == 'disponible' ? 'selected' : '' }}>Disponible
                            </option>
                            <option value="reserve" {{ request('statut') == 'reserve' ? 'selected' : '' }}>Réservé</option>
                            <option value="maintenance" {{ request('statut') == 'maintenance' ? 'selected' : '' }}>
                                Maintenance</option>
                            <option value="hors_service" {{ request('statut') == 'hors_service' ? 'selected' : '' }}>Hors
                                Service</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    <div class="flex gap-2">
                        @if (request()->hasAny(['search', 'categorie_id', 'statut']))
                            <a href="{{ route('materiels.index') }}"
                                class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-300 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i> Effacer les filtres
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Materials Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Liste des Matériels</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $materiels->total() }} matériel(s) trouvé(s)</p>
                    </div>
                </div>
            </div>

            @if ($materiels->isEmpty())
                <div class="p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center p-4 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 mb-4 ring-1 ring-gray-200">
                        <i class="fas fa-tools text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Aucun matériel trouvé</h3>
                    <p class="text-gray-500 mb-6">Commencez par créer votre premier matériel.</p>
                    @if (Auth::user()->role == 'admin')
                        <button onclick="openCreateModal()"
                            class="px-4 py-2.5 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
                            <i class="fas fa-plus mr-2"></i> Créer un matériel
                        </button>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100/20">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Matériel
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Localisation
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                @if (Auth::user()->role == 'admin')
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($materiels as $materiel)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150 cursor-pointer group"
                                    onclick="showMaterielDetails({{ $materiel->id }})">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center ring-1 ring-blue-100 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-200">
                                                <i class="fas fa-toolbox text[#121929]"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-semibold text-gray-900 group-hover:text-[#121929] transition-colors">
                                                    {{ $materiel->nom }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $materiel->numero_serie }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 text-xs font-medium rounded-full bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700 border border-gray-200">
                                            {{ $materiel->categorie->nom }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                            {{ $materiel->localisation }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'disponible' =>
                                                    'from-green-100 to-green-50 text-green-700 border-green-200',
                                                'reserve' => 'from-blue-100 to-blue-50 text-blue-700 border-blue-200',
                                                'maintenance' =>
                                                    'from-yellow-100 to-yellow-50 text-yellow-700 border-yellow-200',
                                                'hors_service' => 'from-red-100 to-red-50 text-red-700 border-red-200',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 text-xs font-medium rounded-full bg-gradient-to-r {{ $statusColors[$materiel->statut] }} border">
                                            @if ($materiel->statut == 'disponible')
                                                <i class="fas fa-check-circle mr-1"></i>
                                            @elseif($materiel->statut == 'reserve')
                                                <i class="fas fa-calendar-check mr-1"></i>
                                            @elseif($materiel->statut == 'maintenance')
                                                <i class="fas fa-wrench mr-1"></i>
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i>
                                            @endif
                                            {{ ucfirst($materiel->statut) }}
                                        </span>
                                    </td>
                                    @if (Auth::user()->role == 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-1" onclick="event.stopPropagation()">
                                                <a href="{{ route('materiels.show', $materiel) }}"
                                                    class="p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200"
                                                    title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button onclick="openEditModal({{ $materiel->id }})"
                                                    class="p-2 rounded-lg text-gray-600 hover:text-green-600 hover:bg-green-50 transition-colors duration-200"
                                                    title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button
                                                    onclick="deleteMateriel({{ $materiel->id }}, '{{ addslashes($materiel->nom) }}')"
                                                    class="p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors duration-200"
                                                    title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($materiels->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
                        {{ $materiels->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="materielModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0"
                id="modalContent">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="closeModal()"
                            class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <form id="materielForm" method="POST">
                        @csrf
                        <div id="methodField"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom du
                                        matériel *</label>
                                    <input type="text" id="nom" name="nom"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 placeholder-gray-400"
                                        placeholder="Ex: Ordinateur Dell XPS 13" required>
                                </div>

                                <div>
                                    <label for="numero_serie" class="block text-sm font-medium text-gray-700 mb-2">Numéro
                                        de série *</label>
                                    <input type="text" id="numero_serie" name="numero_serie"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 placeholder-gray-400"
                                        placeholder="Ex: SN123456789" required>
                                </div>

                                <div>
                                    <label for="categorie_id"
                                        class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                                    <select id="categorie_id" name="categorie_id"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200"
                                        required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="localisation"
                                        class="block text-sm font-medium text-gray-700 mb-2">Localisation *</label>
                                    <input type="text" id="localisation" name="localisation"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 placeholder-gray-400"
                                        placeholder="Ex: Salle 101, Bâtiment A" required>
                                </div>

                                <div id="statutField">
                                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut
                                        *</label>
                                    <select id="statut" name="statut"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200">
                                        <option value="disponible">Disponible</option>
                                        <option value="reserve">Réservé</option>
                                        <option value="maintenance">En maintenance</option>
                                        <option value="hors_service">Hors service</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea id="description" name="description" rows="3"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 resize-none placeholder-gray-400"
                                        placeholder="Description du matériel..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-300 transition-colors duration-200">
                                Annuler
                            </button>
                            <button type="submit" id="submitBtn"
                                class="px-4 py-2.5 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white font-medium rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
                                <i class="fas fa-save mr-2"></i>
                                <span>Enregistrer</span>
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
        let currentMaterielId = null;

        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Créer un nouveau matériel';
            document.getElementById('materielForm').action = "{{ route('materiels.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('nom').value = '';
            document.getElementById('numero_serie').value = '';
            document.getElementById('description').value = '';
            document.getElementById('categorie_id').value = '';
            document.getElementById('localisation').value = '';
            document.getElementById('statut').value = 'disponible';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus mr-2"></i> Créer';

            const modal = document.getElementById('materielModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            resetFormErrors();
        }

        function openEditModal(id) {
            fetch(`/materiels/${id}/json`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error');
                    }
                    return response.json();
                })
                .then(materiel => {
                    document.getElementById('modalTitle').textContent = 'Modifier le matériel';
                    document.getElementById('materielForm').action = `/materiels/${id}`;
                    document.getElementById('methodField').innerHTML =
                        `<input type="hidden" name="_method" value="PUT">`;

                    document.getElementById('nom').value = materiel.nom ?? '';
                    document.getElementById('numero_serie').value = materiel.numero_serie ?? '';
                    document.getElementById('description').value = materiel.description ?? '';
                    document.getElementById('categorie_id').value = materiel.categorie_id ?? '';
                    document.getElementById('localisation').value = materiel.localisation ?? '';
                    document.getElementById('statut').value = materiel.statut ?? 'disponible';

                    document.getElementById('submitBtn').innerHTML =
                        '<i class="fas fa-save mr-2"></i> Mettre à jour';

                    const modal = document.getElementById('materielModal');
                    const content = document.getElementById('modalContent');

                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                })
                .catch(error => {
                    console.error(error);
                    alert('Erreur lors du chargement du matériel.');
                });
        }


        function showMaterielDetails(id) {
            window.location.href = `/materiels/${id}`;
        }

        function deleteMateriel(id, nom) {
            nom = nom.replace(/\\'/g, "'").replace(/\\"/g, '"');

            if (!confirm(`Êtes-vous sûr de vouloir supprimer le matériel "${nom}" ?`)) {
                return;
            }

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/materiels/${id}`;

            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;

            document.body.appendChild(form);
            form.submit();
        }

        function closeModal() {
            const content = document.getElementById('modalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                document.getElementById('materielModal').classList.add('hidden');
            }, 200);
        }

        function resetFormErrors() {
            document.querySelectorAll('.text-red-500.text-xs').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-200');
            });
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const materielForm = document.getElementById('materielForm');
            if (materielForm) {
                materielForm.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
                    submitBtn.disabled = true;
                });
            }

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        });
    </script>
@endpush
