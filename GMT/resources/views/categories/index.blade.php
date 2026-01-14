@extends('layouts.app')

@section('title', 'Gestion des Catégories')
@section('icon', 'fa-tags')
@section('breadcrumb')
    <li class="text-gray-500">/</li>
    <li class="text-gray-700">Catégories</li>
@endsection

@section('header-buttons')
    @if (Auth::user()->role == 'admin')
        <button onclick="openCreateModal()"
            class="px-4 py-2 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
            <i class="fas fa-plus-circle mr-2"></i> Nouvelle Catégorie
        </button>
    @endif
@endsection

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Statistics Card -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 max-w-xs hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-[#121929]/5 to-[#1a2336]/5 text-[#121929] mr-4 ring-1 ring-[#121929]/5">
                        <i class="fas fa-tags text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1 font-medium">Total Catégories</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $categories->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                       id="searchInput"
                       placeholder="Rechercher une catégorie..."
                       class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#121929]/10 focus:border-[#121929]/30 transition-all duration-200 placeholder-gray-400 text-gray-700"
                       onkeyup="filterCategories()">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span id="searchCount" class="text-sm text-gray-400">{{ $categories->count() }} résultats</span>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Liste des Catégories</h2>
                        <p class="text-sm text-gray-600 mt-1">Cliquez sur une catégorie pour voir les détails</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500" id="filterCount">{{ $categories->count() }} catégories</span>
                    </div>
                </div>
            </div>

            @if ($categories->isEmpty())
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center p-4 rounded-2xl bg-gradient-to-br from-[#121929]/5 to-[#1a2336]/5 mb-4 ring-1 ring-[#121929]/5">
                        <i class="fas fa-tags text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Aucune catégorie trouvée</h3>
                    <p class="text-gray-500 mb-6">Commencez par créer votre première catégorie.</p>
                    @if (Auth::user()->role == 'admin')
                        <button onclick="openCreateModal()"
                            class="px-4 py-2.5 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
                            <i class="fas fa-plus mr-2"></i> Créer une catégorie
                        </button>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100" id="categoriesTable">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100/20">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>Nom</span>
                                        <i class="fas fa-sort text-gray-400 text-xs"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                @if (Auth::user()->role == 'admin')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($categories as $category)
                                <tr class="category-row hover:bg-gray-50/50 transition-colors duration-150 cursor-pointer group"
                                    onclick="showCategoryDetails({{ $category->id }}, '{{ addslashes($category->nom) }}', '{{ addslashes($category->description) }}', {{ $category->materiels_count }})"
                                    data-name="{{ strtolower($category->nom) }}"
                                    data-description="{{ strtolower($category->description ?? '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-[#121929]/5 to-[#1a2336]/5 flex items-center justify-center ring-1 ring-[#121929]/5 group-hover:from-[#121929]/10 group-hover:to-[#1a2336]/10 transition-all duration-200">
                                                <i class="fas fa-tag text-[#121929]"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900 group-hover:text-[#121929] transition-colors">
                                                    {{ $category->nom }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $category->materiels_count }} matériel(s)
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 max-w-xs">
                                            {{ $category->description ? Str::limit($category->description, 100) : 'Aucune description' }}
                                        </div>
                                    </td>
                                    @if (Auth::user()->role == 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-1" onclick="event.stopPropagation()">
                                                <button
                                                    onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->nom) }}', '{{ addslashes($category->description) }}')"
                                                    class="p-2 rounded-lg text-gray-600 hover:text-[#121929] hover:bg-[#121929]/5 transition-colors duration-200"
                                                    title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if ($category->materiels_count == 0)
                                                    <button
                                                        onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->nom) }}')"
                                                        class="p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors duration-200"
                                                        title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <span class="p-2 rounded-lg text-gray-300 cursor-not-allowed bg-gray-50"
                                                        title="Catégorie utilisée">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
                        {{ $categories->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <form id="categoryForm" method="POST">
                        @csrf
                        <div id="methodField"></div>

                        <div class="mb-6">
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom de la catégorie *</label>
                            <input type="text" id="nom" name="nom"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 placeholder-gray-400"
                                placeholder="Ex: Ordinateurs Portables" required>
                            <p class="text-xs text-gray-500 mt-2">Maximum 100 caractères</p>
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 resize-none placeholder-gray-400"
                                placeholder="Description de la catégorie..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
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

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDetailsModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0" id="detailsContent">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <h3 id="detailsTitle" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100/20 rounded-lg p-4 border border-gray-100">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Nom</label>
                            <p id="detailsNom" class="text-gray-900 font-semibold"></p>
                        </div>

                        <div class="bg-gradient-to-r from-gray-50 to-gray-100/20 rounded-lg p-4 border border-gray-100">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Description</label>
                            <p id="detailsDescription" class="text-gray-700"></p>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                            <button onclick="closeDetailsModal()"
                                class="px-4 py-2.5 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white font-medium rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
                                <i class="fas fa-times mr-2"></i> Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Créer une nouvelle catégorie';
            document.getElementById('categoryForm').action = "{{ route('categories.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('nom').value = '';
            document.getElementById('description').value = '';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus mr-2"></i> Créer';

            const modal = document.getElementById('categoryModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            resetFormErrors();
        }

        function openEditModal(id, nom, description) {
            nom = nom.replace(/\\'/g, "'").replace(/\\"/g, '"');
            description = description ? description.replace(/\\'/g, "'").replace(/\\"/g, '"') : '';

            document.getElementById('modalTitle').textContent = 'Modifier la catégorie';
            document.getElementById('categoryForm').action = `/categories/${id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('nom').value = nom;
            document.getElementById('description').value = description;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i> Mettre à jour';

            const modal = document.getElementById('categoryModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            resetFormErrors();
        }

        function showCategoryDetails(id, nom, description, materielsCount) {
            nom = nom.replace(/\\'/g, "'").replace(/\\"/g, '"');
            description = description ? description.replace(/\\'/g, "'").replace(/\\"/g, '"') : '';

            document.getElementById('detailsTitle').textContent = 'Détails de la catégorie';
            document.getElementById('detailsNom').textContent = nom;
            document.getElementById('detailsDescription').textContent = description || 'Aucune description';

            const modal = document.getElementById('detailsModal');
            const content = document.getElementById('detailsContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function deleteCategory(id, nom) {
            nom = nom.replace(/\\'/g, "'").replace(/\\"/g, '"');

            if (!confirm(`Êtes-vous sûr de vouloir supprimer la catégorie "${nom}" ?`)) {
                return;
            }

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/categories/${id}`;

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
                document.getElementById('categoryModal').classList.add('hidden');
            }, 200);
        }

        function closeDetailsModal() {
            const content = document.getElementById('detailsContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                document.getElementById('detailsModal').classList.add('hidden');
            }, 200);
        }

        function resetFormErrors() {
            document.querySelectorAll('.text-red-500.text-xs').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-200');
            });
        }

        // Search functionality
        function filterCategories() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.category-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const description = row.getAttribute('data-description');

                if (name.includes(searchTerm) || description.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update counters
            document.getElementById('searchCount').textContent = `${visibleCount} résultat${visibleCount !== 1 ? 's' : ''}`;
            document.getElementById('filterCount').textContent = `${visibleCount} catégorie${visibleCount !== 1 ? 's' : ''}`;
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const categoryForm = document.getElementById('categoryForm');
            if (categoryForm) {
                categoryForm.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
                    submitBtn.disabled = true;
                });
            }

            // Close modals on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                    closeDetailsModal();
                }
            });

            // Initialize search
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', filterCategories);
            }
        });
    </script>
@endpush
