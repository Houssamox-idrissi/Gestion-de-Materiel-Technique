@extends('layouts.app')

@section('title', 'Gestion des Réservations')
@section('icon', 'fa-calendar-check')
@section('breadcrumb')
    <li class="text-gray-500">/</li>
    <li class="text-gray-700">Réservations</li>
@endsection

@section('header-buttons')
    <button onclick="openCreateModal()"
        class="px-4 py-2 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
        <i class="fas fa-plus-circle mr-2"></i> Nouvelle Réservation
    </button>
@endsection

@section('content')
    {{-- Toast pour création --}}
   @if (
    session('success') &&
    (session('reservation_created') ||
     session('reservation_updated') ||
     session('reservation_deleted'))
)
    <div id="successToast"
        class="fixed top-4 right-4 z-[100] px-6 py-3 rounded-lg shadow-lg bg-green-600 text-white flex items-center animate-fade-in-up">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('successToast').remove()"
            class="ml-4 text-white/80 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('successToast');
            if (toast) toast.remove();
        }, 5000);
    </script>
@endif


    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 mr-4 ring-1 ring-blue-100">
                        <i class="fas fa-calendar-alt text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1 font-medium">Total Réservations</p>
                        <p id="totalReservationsCount" class="text-2xl font-bold text-gray-800">{{ $reservations->total() }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 text-yellow-600 mr-4 ring-1 ring-yellow-100">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1 font-medium">En Attente</p>
                        <p id="pendingCount" class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Reservation::where('statut', 'en_attente')->count() }}
                        </p>
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
                        <p class="text-sm text-gray-600 mb-1 font-medium">Confirmées</p>
                        <p id="confirmedCount" class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Reservation::where('statut', 'confirmee')->count() }}
                        </p>
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
                        <p class="text-sm text-gray-600 mb-1 font-medium">Annulées</p>
                        <p id="cancelledCount" class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Reservation::where('statut', 'annulee')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <form method="GET" action="{{ route('reservations.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search by Material Name -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par matériel..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/10 focus:border-[#121929]/30 transition-all duration-200 placeholder-gray-400 text-gray-700"
                            onkeyup="if(event.key === 'Enter') this.form.submit()">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="statut" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/10 focus:border-[#121929]/30 transition-all duration-200 text-gray-700">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente
                            </option>
                            <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée
                            </option>
                            <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée
                            </option>
                            <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminée
                            </option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <input type="date" name="date_reservation" value="{{ request('date_reservation') }}"
                            onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/10 focus:border-[#121929]/30 transition-all duration-200 text-gray-700"
                            placeholder="Filtrer par date">
                    </div>

                    <!-- Clear Filters -->
                    <div class="flex gap-2">
                        @if (request()->hasAny(['search', 'statut', 'date_reservation']))
                            <a href="{{ route('reservations.index') }}"
                                class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-300 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i> Effacer les filtres
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Reservations Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            @if (Auth::user()->role == 'admin')
                                Toutes les Réservations
                            @else
                                Mes Réservations
                            @endif
                        </h2>
                        <p id="reservationsFoundCount" class="text-sm text-gray-600 mt-1">{{ $reservations->total() }} réservation(s) trouvée(s)</p>
                    </div>

                    @if (Auth::user()->role == 'admin')
                        <a href="{{ route('reservations.admin') }}"
                            class="px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98] text-sm font-medium">
                            <i class="fas fa-cog mr-2"></i> Gestion Admin
                        </a>
                    @endif
                </div>
            </div>

            @if ($reservations->isEmpty())
                <div class="p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center p-4 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 mb-4 ring-1 ring-gray-200">
                        <i class="fas fa-calendar-check text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Aucune réservation trouvée</h3>
                    <p class="text-gray-500 mb-6">
                        @if (Auth::user()->role == 'admin')
                            Aucune réservation n'a été créée pour le moment.
                        @else
                            Vous n'avez pas encore fait de réservation.
                        @endif
                    </p>
                    <button onclick="openCreateModal()"
                        class="px-4 py-2.5 bg-gradient-to-r from-[#121929] to-[#1a2336] text-white rounded-lg hover:from-[#1a2336] hover:to-[#121929] focus:outline-none focus:ring-2 focus:ring-[#121929]/20 focus:ring-offset-1 transition-all duration-300 shadow-sm hover:shadow-md active:scale-[0.98]">
                        <i class="fas fa-plus mr-2"></i> Créer une réservation
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100/20">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Matériel
                                </th>
                                @if (Auth::user()->role == 'admin')
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Utilisateur
                                    </th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date & Heure
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Objet
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($reservations as $reservation)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150 cursor-pointer group"
                                    onclick="showReservationDetails({{ $reservation->id }})">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center ring-1 ring-blue-100 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-200">
                                                <i class="fas fa-toolbox text-[#121929]"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-semibold text-gray-900 group-hover:text-[#121929] transition-colors">
                                                    {{ $reservation->materiel->nom }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $reservation->materiel->numero_serie }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    @if (Auth::user()->role == 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $reservation->utilisateur->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $reservation->utilisateur->email }}
                                            </div>
                                        </td>
                                    @endif

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">
                                            {{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $reservation->heure_debut }} - {{ $reservation->heure_fin }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            Durée: {{ $reservation->duree() }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ $reservation->objet }}
                                        </div>
                                        @if ($reservation->commentaire)
                                            <div class="text-xs text-gray-500 mt-1 truncate">
                                                {{ $reservation->commentaire }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'en_attente' =>
                                                    'from-yellow-100 to-yellow-50 text-yellow-700 border-yellow-200',
                                                'confirmee' =>
                                                    'from-green-100 to-green-50 text-green-700 border-green-200',
                                                'annulee' => 'from-red-100 to-red-50 text-red-700 border-red-200',
                                                'terminee' => 'from-gray-100 to-gray-50 text-gray-700 border-gray-200',
                                            ];
                                            $statusIcons = [
                                                'en_attente' => 'fa-clock',
                                                'confirmee' => 'fa-check-circle',
                                                'annulee' => 'fa-times-circle',
                                                'terminee' => 'fa-check-double',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 text-xs font-medium rounded-full bg-gradient-to-r {{ $statusColors[$reservation->statut] }} border">
                                            <i class="fas {{ $statusIcons[$reservation->statut] }} mr-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                                        </span>

                                        @if ($reservation->check_out_at && !$reservation->check_in_at)
                                            <div class="mt-1">
                                                <span
                                                    class="px-2 py-0.5 text-xs font-medium rounded-full bg-gradient-to-r from-purple-100 to-purple-50 text-purple-700 border border-purple-200">
                                                    <i class="fas fa-sign-out-alt mr-1"></i> Check-out
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-1" onclick="event.stopPropagation()">
                                            <!-- Edit Button (for creator or admin) -->
                                            @if ($reservation->user_id == Auth::id() || Auth::user()->role == 'admin')
                                                <button onclick="openEditModal({{ $reservation->id }})"
                                                    class="p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200"
                                                    title="Éditer">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- DELETE BUTTON -->
                                                @if (($reservation->user_id == Auth::id() || Auth::user()->role == 'admin') &&
                                                     !in_array($reservation->statut, ['confirmee', 'en_cours']))
                                                    <form action="{{ route('reservations.destroy', $reservation) }}"
                                                        method="POST" class="inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors duration-200 delete-btn"
                                                            title="Supprimer"
                                                            data-reservation-id="{{ $reservation->id }}"
                                                            data-reservation-status="{{ $reservation->statut }}"
                                                            data-reservation-name="{{ $reservation->materiel->nom }} - {{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            <!-- Actions based on status and role -->
                                            @if ($reservation->statut == 'en_attente' && Auth::user()->role == 'admin')
                                                <!-- Validate Button -->
                                                <form action="{{ route('reservations.valider', $reservation) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-2 rounded-lg text-gray-600 hover:text-green-600 hover:bg-green-50 transition-colors duration-200"
                                                        title="Valider"
                                                        onclick="return confirm('Valider cette réservation ?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <!-- Cancel Button -->
                                                <form action="{{ route('reservations.annuler', $reservation) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors duration-200"
                                                        title="Refuser"
                                                        onclick="return confirm('Refuser cette réservation ?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($reservation->statut == 'confirmee')
                                                @if (Auth::user()->role == 'admin')
                                                    @if (!$reservation->check_out_at)
                                                        <!-- Check-out Button -->
                                                        <form action="{{ route('reservations.checkout', $reservation) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="p-2 rounded-lg text-gray-600 hover:text-purple-600 hover:bg-purple-50 transition-colors duration-200"
                                                                title="Check-out"
                                                                onclick="return confirm('Enregistrer le check-out ?')">
                                                                <i class="fas fa-sign-out-alt"></i>
                                                            </button>
                                                        </form>
                                                    @elseif(!$reservation->check_in_at)
                                                        <!-- Check-in Button -->
                                                        <form action="{{ route('reservations.checkin', $reservation) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="p-2 rounded-lg text-gray-600 hover:text-green-600 hover:bg-green-50 transition-colors duration-200"
                                                                title="Check-in"
                                                                onclick="return confirm('Enregistrer le check-in ?')">
                                                                <i class="fas fa-sign-in-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                <!-- Cancel Button (User or Admin) -->
                                                @if ($reservation->user_id == Auth::id() || Auth::user()->role == 'admin')
                                                    <form action="{{ route('reservations.annuler', $reservation) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors duration-200"
                                                            title="Annuler"
                                                            onclick="return confirm('Annuler cette réservation ?')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($reservations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
                        {{ $reservations->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="reservationModal" class="fixed inset-0 z-50 hidden">
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

                    <form id="reservationForm" method="POST">
                        @csrf
                        <div id="methodField"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <!-- Material Selection -->
                                <div>
                                    <label for="materiel_id"
                                        class="block text-sm font-medium text-gray-700 mb-2">Matériel
                                        *</label>
                                    <select id="materiel_id" name="materiel_id"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200"
                                        required>
                                        <option value="">Sélectionner un matériel</option>
                                        @foreach (\App\Models\Materiel::where('statut', 'disponible')->get() as $materiel)
                                            <option value="{{ $materiel->id }}">{{ $materiel->nom }}
                                                ({{ $materiel->numero_serie }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Seuls les matériels disponibles sont affichés
                                    </p>
                                </div>

                                <!-- Date -->
                                <div>
                                    <label for="date_reservation"
                                        class="block text-sm font-medium text-gray-700 mb-2">Date de réservation *</label>
                                    <input type="date" id="date_reservation" name="date_reservation"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200"
                                        min="{{ date('Y-m-d') }}" required>
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-2">Heure
                                        de
                                        début *</label>
                                    <input type="time" id="heure_debut" name="heure_debut"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200"
                                        required>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <!-- End Time -->
                                <div>
                                    <label for="heure_fin" class="block text-sm font-medium text-gray-700 mb-2">Heure de
                                        fin *</label>
                                    <input type="time" id="heure_fin" name="heure_fin"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200"
                                        required>
                                </div>

                                <!-- Purpose -->
                                <div>
                                    <label for="objet" class="block text-sm font-medium text-gray-700 mb-2">Objet de
                                        la
                                        réservation *</label>
                                    <input type="text" id="objet" name="objet"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 placeholder-gray-400"
                                        placeholder="Ex: TP Réseaux Informatiques" required>
                                </div>

                                <!-- Comment -->
                                <div>
                                    <label for="commentaire"
                                        class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                                    <textarea id="commentaire" name="commentaire" rows="3"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200 resize-none placeholder-gray-400"
                                        placeholder="Informations supplémentaires..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Status field (for admin only when editing) -->
                        <div id="statusField" class="hidden mt-4">
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                            <select id="statut" name="statut"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#121929]/15 focus:border-[#121929]/40 transition-all duration-200">
                                <option value="en_attente">En attente</option>
                                <option value="confirmee">Confirmée</option>
                                <option value="annulee">Annulée</option>
                                <option value="terminee">Terminée</option>
                            </select>
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
    // ===== MODAL FUNCTIONS =====
    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Créer une nouvelle réservation';
        document.getElementById('reservationForm').action = "{{ route('reservations.store') }}";
        document.getElementById('methodField').innerHTML = '';

        // Reset fields
        ['materiel_id', 'date_reservation', 'heure_debut', 'heure_fin', 'objet', 'commentaire'].forEach(f => {
            const input = document.getElementById(f);
            if (input) input.value = '';
        });

        document.getElementById('statusField').classList.add('hidden');
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus mr-2"></i> Créer';
        showModal();
        resetFormErrors();
    }

    function openEditModal(id) {
        fetch(`/reservations/${id}/json`)
            .then(r => r.ok ? r.json() : Promise.reject('HTTP error'))
            .then(reservation => {
                document.getElementById('modalTitle').textContent = 'Modifier la réservation';
                document.getElementById('reservationForm').action = `/reservations/${id}`;
                document.getElementById('methodField').innerHTML = `<input type="hidden" name="_method" value="PUT">`;

                document.getElementById('materiel_id').value = reservation.materiel_id || '';
                document.getElementById('date_reservation').value = reservation.date_reservation ?
                    new Date(reservation.date_reservation).toISOString().split('T')[0] : '';
                document.getElementById('heure_debut').value = reservation.heure_debut?.slice(0, 5) || '';
                document.getElementById('heure_fin').value = reservation.heure_fin?.slice(0, 5) || '';
                document.getElementById('objet').value = reservation.objet || '';
                document.getElementById('commentaire').value = reservation.commentaire || '';

                // Status for admin
                if ({{ Auth::user()->role == 'admin' ? 'true' : 'false' }}) {
                    document.getElementById('statusField').classList.remove('hidden');
                    document.getElementById('statut').value = reservation.statut || 'en_attente';
                } else {
                    document.getElementById('statusField').classList.add('hidden');
                }

                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i> Mettre à jour';
                showModal();
                resetFormErrors();
            })
            .catch(e => {
                console.error(e);
                alert('Erreur lors du chargement de la réservation.');
            });
    }

    function showModal() {
        const modal = document.getElementById('reservationModal');
        const content = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('reservationModal');
        const content = document.getElementById('modalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    // ===== TOASTS =====
    function showToast(message, type = 'success', duration = 5000) {
        // Remove any existing custom toast
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.className = `custom-toast fixed top-4 right-4 z-[100] px-6 py-3 rounded-lg shadow-lg text-white flex items-center animate-fade-in-up ${
            type === 'success' ? 'bg-green-600' :
            type === 'error' ? 'bg-red-600' :
            'bg-blue-600'
        }`;

        const icon = type === 'success' ? 'fa-check-circle' :
                    type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

        toast.innerHTML = `
            <i class="fas ${icon} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(toast);

        // Auto-remove after duration
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.transition = 'all 0.3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 300);
            }
        }, duration);
    }

    // ===== FORM ERROR HANDLING =====
    function resetFormErrors() {
        document.querySelectorAll('.text-red-500.text-xs').forEach(el => el.remove());
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            el.classList.add('border-gray-200', 'focus:border-[#121929]/40', 'focus:ring-[#121929]/15');
        });
    }

    // ===== AJAX FORM SUBMISSION =====
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reservationForm');
        if (!form) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
            submitBtn.disabled = true;

            resetFormErrors();

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Collect all input values as JSON
            const payload = {
                materiel_id: document.getElementById('materiel_id').value,
                date_reservation: document.getElementById('date_reservation').value,
                heure_debut: document.getElementById('heure_debut').value,
                heure_fin: document.getElementById('heure_fin').value,
                objet: document.getElementById('objet').value,
                commentaire: document.getElementById('commentaire').value
            };

            // Add status if visible (admin edit)
            if (!document.getElementById('statusField').classList.contains('hidden')) {
                payload.statut = document.getElementById('statut').value;
            }

            // Determine method (POST for create, PUT for update)
            const method = form.querySelector('[name="_method"]')?.value || 'POST';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        ...payload,
                        _method: method
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal();
                    setTimeout(() => {
                        data.redirect ? (window.location.href = data.redirect) : window.location.reload();
                    }, 1500);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(f => {
                            const input = document.querySelector(`[name="${f}"]`);
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'text-red-500 text-xs mt-1 flex items-center';
                            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ${data.errors[f][0]}`;
                            if (input) {
                                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                                const old = input.parentNode.querySelector('.text-red-500');
                                if (old) old.remove();
                                input.parentNode.appendChild(errorDiv);
                            } else {
                                showToast(data.errors[f][0], 'error');
                            }
                        });
                    } else if (data.message) {
                        showToast(data.message, 'error');
                    }
                    submitBtn.innerHTML = originalHtml;
                    submitBtn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                showToast('Une erreur est survenue lors de la soumission', 'error');
                submitBtn.innerHTML = originalHtml;
                submitBtn.disabled = false;
            }
        });

        // ===== DELETE RESERVATION FUNCTIONALITY =====
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const reservationName = this.getAttribute('data-reservation-name');
                const reservationStatus = this.getAttribute('data-reservation-status');

                // Simple confirmation
                if (confirm(`Êtes-vous sûr de vouloir supprimer la réservation : ${reservationName} ?`)) {
                    const form = this.closest('form.delete-form');
                    if (form) {
                        submitDeleteForm(form, reservationStatus);
                    }
                }
            });
        });

        function submitDeleteForm(form, status) {
            const submitBtn = form.querySelector('.delete-btn');
            const originalHtml = submitBtn.innerHTML;

            // Afficher un indicateur de chargement
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;

            // Préparer les données
            const formData = new FormData(form);
            const url = form.action;

            // Récupérer le token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher le message de succès
                    showToast(data.message, 'success');

                    // Supprimer la ligne du tableau
                    const row = form.closest('tr');
                    if (row) {
                        // Animation de disparition
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';
                        row.style.transition = 'all 0.3s ease';

                        setTimeout(() => {
                            row.remove();

                            // Mettre à jour tous les compteurs
                            updateAllCounts(-1, status);

                            // Si plus de lignes, recharger
                            const remainingRows = document.querySelectorAll('tbody tr').length;
                            if (remainingRows === 0) {
                                setTimeout(() => window.location.reload(), 500);
                            }
                        }, 300);
                    }
                } else {
                    showToast(data.message || 'Erreur lors de la suppression', 'error');
                    submitBtn.innerHTML = originalHtml;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue lors de la suppression', 'error');
                submitBtn.innerHTML = originalHtml;
                submitBtn.disabled = false;
            });
        }

        function updateAllCounts(change, status) {
            // 1. Mettre à jour le compteur total principal
            const totalElement = document.getElementById('totalReservationsCount');
            if (totalElement) {
                const currentCount = parseInt(totalElement.textContent) || 0;
                totalElement.textContent = Math.max(0, currentCount + change);
            }

            // 2. Mettre à jour le compteur sous le titre
            const foundElement = document.getElementById('reservationsFoundCount');
            if (foundElement) {
                const text = foundElement.textContent;
                const matches = text.match(/(\d+)\s+réservation\(s\)/);
                if (matches && matches[1]) {
                    const currentCount = parseInt(matches[1]);
                    const newCount = Math.max(0, currentCount + change);
                    foundElement.textContent = `${newCount} réservation(s) trouvée(s)`;
                }
            }

            // 3. Mettre à jour les compteurs par statut
            if (status) {
                const statusMap = {
                    'en_attente': 'pendingCount',
                    'confirmee': 'confirmedCount',
                    'annulee': 'cancelledCount',
                    'terminee': 'terminatedCount'
                };

                const statusElementId = statusMap[status];
                if (statusElementId) {
                    const statusElement = document.getElementById(statusElementId);
                    if (statusElement) {
                        const currentStatusCount = parseInt(statusElement.textContent) || 0;
                        statusElement.textContent = Math.max(0, currentStatusCount + change);
                    }
                }
            }
        }
    });

    // ===== REAL-TIME AVAILABILITY CHECK =====
    ['materiel_id', 'date_reservation', 'heure_debut', 'heure_fin'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', checkRealTimeAvailability);
    });

    async function checkRealTimeAvailability() {
        const materielId = document.getElementById('materiel_id').value;
        const date = document.getElementById('date_reservation').value;
        const start = document.getElementById('heure_debut').value;
        const end = document.getElementById('heure_fin').value;

        if (!materielId || !date || !start || !end) return;

        try {
            const res = await fetch('/reservations/check-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    materiel_id: materielId,
                    date_reservation: date,
                    heure_debut: start,
                    heure_fin: end
                })
            });
            const data = await res.json();
            let msgDiv = document.getElementById('availabilityMessage') || createAvailabilityMessageDiv();
            msgDiv.className = data.available ?
                'p-3 mt-2 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm' :
                'p-3 mt-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm';
            msgDiv.innerHTML = `<div class="flex items-center">
                <i class="fas ${data.available?'fa-check-circle':'fa-exclamation-circle'} mr-2"></i>
                <span>${data.message}</span>
            </div>`;
        } catch (err) {
            console.error('Erreur de vérification:', err);
        }
    }

    function createAvailabilityMessageDiv() {
        const div = document.createElement('div');
        div.id = 'availabilityMessage';
        const container = document.getElementById('heure_fin').closest('.space-y-4');
        if (container) container.appendChild(div);
        return div;
    }

    // ===== ESC KEY CLOSE =====
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush
