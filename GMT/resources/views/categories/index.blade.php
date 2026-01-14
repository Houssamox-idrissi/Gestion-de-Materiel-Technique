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
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-plus-circle mr-2"></i> Nouvelle Catégorie
        </button>
    @endif
@endsection

@section('content')
    <div class="container mx-auto">
        <!-- Statistics Card - Only Total Catégories -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 max-w-xs">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-tags text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Catégories</p>
                        <p class="text-2xl font-bold">{{ $categories->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Liste des Catégories</h2>
                <p class="text-sm text-gray-600">Cliquez sur une catégorie pour voir les détails</p>
            </div>

            @if ($categories->isEmpty())
                <div class="p-8 text-center">
                    <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Aucune catégorie trouvée</h3>
                    <p class="text-gray-500 mb-4">Commencez par créer votre première catégorie.</p>
                    @if (Auth::user()->role == 'admin')
                        <button onclick="openCreateModal()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Créer une catégorie
                        </button>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                @if (Auth::user()->role == 'admin')
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                                    onclick="showCategoryDetails({{ $category->id }}, '{{ addslashes($category->nom) }}', '{{ addslashes($category->description) }}', {{ $category->materiels_count }})">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-tag text-blue-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $category->nom }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $category->materiels_count }} matériel(s)
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            {{ $category->description ? Str::limit($category->description, 100) : 'Aucune description' }}
                                        </div>
                                    </td>
                                    @if (Auth::user()->role == 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2" onclick="event.stopPropagation()">
                                                <button
                                                    onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->nom) }}', '{{ addslashes($category->description) }}')"
                                                    class="text-blue-600 hover:text-blue-900 p-1" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if ($category->materiels_count == 0)
                                                    <button
                                                        onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->nom) }}')"
                                                        class="text-red-600 hover:text-red-900 p-1" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 cursor-not-allowed p-1"
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
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $categories->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg transform transition-all">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="categoryForm" method="POST">
                        @csrf
                        <div id="methodField"></div>

                        <div class="mb-4">
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie
                                *</label>
                            <input type="text" id="nom" name="nom"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ex: Ordinateurs Portables" required>
                            <p class="text-xs text-gray-500 mt-1">Maximum 100 caractères</p>
                        </div>

                        <div class="mb-4">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                placeholder="Description de la catégorie..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Annuler
                            </button>
                            <button type="submit" id="submitBtn"
                                class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeDetailsModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg transform transition-all">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="detailsTitle" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <p id="detailsNom" class="text-gray-900 font-medium"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p id="detailsDescription" class="text-gray-700"></p>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button onclick="closeDetailsModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Fermer
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
            document.getElementById('categoryModal').classList.remove('hidden');
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
            document.getElementById('categoryModal').classList.remove('hidden');
            resetFormErrors();
        }

        function showCategoryDetails(id, nom, description, materielsCount) {
            // Decode escaped characters
            nom = nom.replace(/\\'/g, "'").replace(/\\"/g, '"');
            description = description ? description.replace(/\\'/g, "'").replace(/\\"/g, '"') : '';

            document.getElementById('detailsTitle').textContent = 'Détails de la catégorie';
            document.getElementById('detailsNom').textContent = nom;
            document.getElementById('detailsDescription').textContent = description || 'Aucune description';
            document.getElementById('detailsModal').classList.remove('hidden');
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
            document.getElementById('categoryModal').classList.add('hidden');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        function resetFormErrors() {
            document.querySelectorAll('.text-red-500.text-xs').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-300');
            });
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

            // Close modals when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('bg-black')) {
                    closeModal();
                    closeDetailsModal();
                }
            });
        });
    </script>
@endpush
