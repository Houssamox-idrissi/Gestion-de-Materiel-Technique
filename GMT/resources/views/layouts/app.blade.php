<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic Title -->
    <title>
        @auth
            @if(Auth::user()->role == 'admin')
                Gestion Stock - @yield('title', 'Tableau de Bord Admin')
            @else
                LabReserve - @yield('title', 'Tableau de Bord Étudiant')
            @endif
        @endauth
    </title>

    <!-- Vite CSS (Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Smooth transitions */
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom scrollbar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: #4B5563;
            border-radius: 2px;
        }

        /* Active link indicator */
        .active-nav::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: #3B82F6;
            border-radius: 0 2px 2px 0;
        }

        /* Card hover effects */
        .nav-card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .nav-card-hover:hover {
            transform: translateX(4px);
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }

        /* Fix for full height sidebar */
        html,
        body {
            height: 100%;
            margin: 0;
        }

        /* Ensure the body takes full height */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Make the main container flex to push footer down */
        .main-container {
            display: flex;
            flex: 1;
            min-height: 0;
        }

        /* Sidebar should fill parent height */
        .sidebar-full-height {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Navigation content should scroll */
        .sidebar-navigation {
            flex: 1;
            overflow-y: auto;
        }

        /* Role-specific gradients */
        .student-navbar {
            background: linear-gradient(135deg, #1E3A8A 0%, #3730A3 100%);
        }

        .admin-navbar {
            background: #0F172A;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar - Dynamic -->
    <nav class="@if(Auth::user()->role == 'admin') admin-navbar @else student-navbar @endif text-white border-b border-white/[0.08]">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Logo - Dynamic -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="relative">
                            <div class="absolute inset-0 @if(Auth::user()->role == 'admin') bg-blue-500/10 @else bg-indigo-500/10 @endif rounded-lg blur group-hover:blur-sm transition-all duration-300">
                            </div>
                            <div class="relative bg-white/5 p-2.5 rounded-lg border border-white/10 @if(Auth::user()->role == 'admin') group-hover:border-blue-500/30 @else group-hover:border-indigo-500/30 @endif transition-colors duration-300">
                                @if(Auth::user()->role == 'admin')
                                    <i class="fas fa-boxes text-blue-400 text-lg"></i>
                                @else
                                    <i class="fas fa-laptop-code text-indigo-400 text-lg"></i>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-lg tracking-tight text-white">
                                @if(Auth::user()->role == 'admin')
                                    Gestion Stock
                                @else
                                    LabReserve
                                @endif
                            </div>
                            <div class="text-xs text-gray-300">
                                @if(Auth::user()->role == 'admin')
                                    Système de gestion
                                @else
                                    Portail Étudiant
                                @endif
                            </div>
                        </div>
                    </a>
                </div>

                <!-- User Menu -->
                @auth
                    <div class="relative" id="user-menu-container">
                        <button id="user-menu-button"
                            class="flex items-center space-x-3 p-1.5 rounded-lg hover:bg-white/5 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/20">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full @if(Auth::user()->role == 'admin') bg-gradient-to-br from-blue-500 to-blue-600 @else bg-gradient-to-br from-indigo-500 to-purple-600 @endif flex items-center justify-center">
                                        <span class="text-xs font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border @if(Auth::user()->role == 'admin') border-[#0F172A] bg-red-500 @else border-indigo-900 bg-green-500 @endif">
                                    </div>
                                </div>
                                <div class="text-left hidden md:block">
                                    <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                    <div class="text-xs @if(Auth::user()->role == 'admin') text-gray-400 @else text-indigo-200 @endif">
                                        {{ Auth::user()->role == 'admin' ? 'Admin' : 'Étudiant' }}
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-xs @if(Auth::user()->role == 'admin') text-gray-400 @else text-indigo-300 @endif" id="chevron-icon"></i>
                            </div>
                        </button>

                        <!-- Dropdown -->
                        <div id="user-dropdown"
                            class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible translate-y-1 transition-all duration-200 z-50">
                            <div class="p-3 border-b border-gray-100">
                                <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="p-1">
                                <a href="#"
                                    class="flex items-center px-3 py-2 text-sm text-gray-700 rounded hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-cog mr-2 text-gray-500 text-xs"></i>
                                    Paramètres
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-3 py-2 text-sm text-red-600 rounded hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2 text-xs"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="main-container">
        <!-- Sidebar -->
        @auth
            <aside class="sidebar-transition w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-xl sidebar-full-height">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="@if(Auth::user()->role == 'admin') bg-gradient-to-br from-blue-500 to-blue-600 @else bg-gradient-to-br from-indigo-500 to-purple-600 @endif p-3 rounded-xl shadow">
                            @if(Auth::user()->role == 'admin')
                                <i class="fas fa-boxes text-xl"></i>
                            @else
                                <i class="fas fa-user-graduate text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium">
                                @if(Auth::user()->role == 'admin')
                                    Panneau Admin
                                @else
                                    Mon Espace
                                @endif
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ Auth::user()->role == 'admin' ? 'Administration complète' : 'Réservations & Matériels' }}
                            </div>
                        </div>
                    </div>

                    <!-- Search - Only show for students -->
                    @if(Auth::user()->role == 'student')
                        <div class="mt-6">
                            <div class="relative">
                                <input type="text" placeholder="Rechercher un matériel..."
                                    class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-500 text-sm"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Navigation -->
                <div class="sidebar-navigation p-4 sidebar-scrollbar">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-3">
                        @if(Auth::user()->role == 'admin')
                            Navigation Principale
                        @else
                            Mon Navigation
                        @endif
                    </h3>

                    <nav class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}"
                            class="nav-card-hover flex items-center px-4 py-3 rounded-xl hover:bg-gray-700/50 transition-all relative group">
                            <div class="w-8 h-8 rounded-lg @if(Auth::user()->role == 'admin') bg-blue-500/20 @else bg-indigo-500/20 @endif flex items-center justify-center mr-3 group-hover:bg-blue-500/30">
                                <i class="fas fa-tachometer-alt @if(Auth::user()->role == 'admin') text-blue-400 @else text-indigo-400 @endif"></i>
                            </div>
                            <span class="font-medium">
                                @if(Auth::user()->role == 'admin')
                                    Tableau de Bord
                                @else
                                    Mon Tableau de Bord
                                @endif
                            </span>
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                            </div>
                        </a>

                        <!-- ADMIN ONLY SECTION -->
                        @if(Auth::user()->role == 'admin')
                            <div class="mt-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-3">
                                    Administration
                                </h3>

                                <a href="{{ route('materiels.index') }}"
                                    class="nav-card-hover flex items-center px-4 py-3 rounded-xl hover:bg-gray-700/50 transition-all relative group">
                                    <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center mr-3 group-hover:bg-green-500/30">
                                        <i class="fas fa-tools text-green-400"></i>
                                    </div>
                                    <span class="font-medium">Gestion Matériels</span>
                                </a>

                                <a href="{{ route('categories.index') }}"
                                    class="nav-card-hover flex items-center px-4 py-3 rounded-xl hover:bg-gray-700/50 transition-all relative group">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center mr-3 group-hover:bg-purple-500/30">
                                        <i class="fas fa-tags text-purple-400"></i>
                                    </div>
                                    <span class="font-medium">Catégories</span>
                                </a>
                            </div>
                        @endif

                        <!-- STUDENT ONLY SECTION -->
                        @if(Auth::user()->role == 'student')
                            <div class="mt-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-3">
                                    Catalogue
                                </h3>

                                <a href="{{ route('materiels.catalog') }}"
                                    class="nav-card-hover flex items-center px-4 py-3 rounded-xl hover:bg-gray-700/50 transition-all relative group">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center mr-3 group-hover:bg-emerald-500/30">
                                        <i class="fas fa-search text-emerald-400"></i>
                                    </div>
                                    <span class="font-medium">Explorer Matériels</span>
                                </a>

                                <a href="{{ route('materiels.available') }}"
                                    class="nav-card-hover flex items-center px-4 py-3 rounded-xl hover:bg-gray-700/50 transition-all relative group">
                                    <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center mr-3 group-hover:bg-cyan-500/30">
                                        <i class="fas fa-check-circle text-cyan-400"></i>
                                    </div>
                                    <span class="font-medium">Disponibles Maintenant</span>
                                </a>
                            </div>
                        @endif

                        <!-- RESERVATIONS SECTION - Dynamic -->
                        <div class="mt-6">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-3">
                                @if(Auth::user()->role == 'admin')
                                    Gestion Réservations
                                @else
                                    Mes Réservations
                                @endif
                            </h3>

                            <!-- For Students -->
                            @if(Auth::user()->role == 'etudiant')
                                <a href="{{ route('reservations.index') }}"
                                    class="nav-card-hover flex items-center px-4 py-3 rounded-xl hover:bg-gray-700/50 transition-all relative group">
                                    <div class="w-8 h-8 rounded-lg bg-yellow-500/20 flex items-center justify-center mr-3 group-hover:bg-yellow-500/30">
                                        <i class="fas fa-calendar-check text-yellow-400"></i>
                                    </div>
                                    <span class="font-medium">Mes Réservations</span>
                                </a>
                            @endif

                            <!-- For Admins -->
                            @if(Auth::user()->role == 'admin')
                                <a href="{{ route('reservations.index') }}"
                                    class="nav-card-hover flex items-center px-4 py-3 rounded-xl hover:bg-gray-700/50 transition-all relative group">
                                    <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center mr-3 group-hover:bg-red-500/30">
                                        <i class="fas fa-list-check text-red-400"></i>
                                    </div>
                                    <span class="font-medium">Toutes Réservations</span>
                                </a>
                            @endif
                        </div>

                    </nav>
                </div>

                <!-- Sidebar Footer - Dynamic -->
                <div class="p-4 border-t border-gray-700 bg-gray-900/50 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-400">
                            <div class="flex items-center">
                                <div class="w-2 h-2 @if(Auth::user()->role == 'admin') bg-green-500 @else bg-indigo-500 @endif rounded-full mr-2 animate-pulse"></div>
                                <span>
                                    @if(Auth::user()->role == 'admin')
                                        Système actif
                                    @else
                                        Connecté en tant qu'étudiant
                                    @endif
                                </span>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </div>
                </div>
            </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 overflow-auto min-h-0">
            <div class="p-6">
                <!-- Breadcrumb -->
                <nav class="mb-6">
                    <ol class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <a href="{{ route('dashboard') }}"
                                class="hover:text-blue-600 transition-colors flex items-center gap-2">
                                <i class="fas fa-home"></i>
                                <span>
                                    @if(Auth::user()->role == 'admin')
                                        Tableau de Bord
                                    @else
                                        Mon Tableau de Bord
                                    @endif
                                </span>
                            </a>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </li>
                        @yield('breadcrumb')
                    </ol>
                </nav>

                <!-- Flash Messages -->
                <div class="mb-6 space-y-3">
                    @if (session('success'))
                        <div class="animate-slideIn p-4 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 rounded-r-lg shadow">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                                    </div>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()"
                                    class="text-green-500 hover:text-green-700 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="animate-slideIn p-4 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-r-lg shadow">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                                    </div>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()"
                                    class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="animate-slideIn p-4 bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 rounded-r-lg shadow">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-blue-800 font-medium">{{ session('info') }}</p>
                                    </div>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()"
                                    class="text-blue-500 hover:text-blue-700 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Page Header - Dynamic -->
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-3 rounded-xl @if(Auth::user()->role == 'admin') bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 @else bg-gradient-to-br from-indigo-50 to-purple-100 border border-indigo-200 @endif shadow-sm">
                                    @if(Auth::user()->role == 'admin')
                                        <i class="fas @yield('icon', 'fa-tachometer-alt') text-blue-600 text-xl"></i>
                                    @else
                                        <i class="fas @yield('icon', 'fa-user-graduate') text-indigo-600 text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    @if(Auth::user()->role == 'admin')
                                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                                            @yield('title', 'Tableau de Bord Admin')
                                        </h1>
                                        <p class="text-gray-600 mt-1 text-sm md:text-base">
                                            @hasSection('subtitle')
                                                @yield('subtitle')
                                            @else
                                                Gestion complète du système de stock
                                            @endif
                                        </p>
                                    @else
                                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                                            @yield('title', 'Mon Tableau de Bord Étudiant')
                                        </h1>
                                        <p class="text-gray-600 mt-1 text-sm md:text-base">
                                            @hasSection('subtitle')
                                                @yield('subtitle')
                                            @else
                                                Gérez vos réservations de matériel
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @yield('header-buttons')
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('[class*="bg-gradient-to-r"]').forEach(alert => {
                    if (alert.classList.contains('from-green-50') ||
                        alert.classList.contains('from-red-50') ||
                        alert.classList.contains('from-blue-50')) {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s';
                        setTimeout(() => alert.remove(), 500);
                    }
                });
            }, 5000);

            // Gestion du dropdown du profil utilisateur
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            const chevronIcon = document.getElementById('chevron-icon');

            if (userMenuButton && userDropdown) {
                let isDropdownOpen = false;

                // Fonction pour ouvrir/fermer le dropdown
                function toggleDropdown() {
                    if (isDropdownOpen) {
                        // Fermer le dropdown
                        userDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
                        userDropdown.classList.add('opacity-0', 'invisible', 'translate-y-1');
                        chevronIcon.classList.remove('rotate-180');
                    } else {
                        // Ouvrir le dropdown
                        userDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-1');
                        userDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
                        chevronIcon.classList.add('rotate-180');
                    }
                    isDropdownOpen = !isDropdownOpen;
                }

                // Ouvrir/fermer au clic sur le bouton
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleDropdown();
                });

                // Fermer en cliquant ailleurs sur la page
                document.addEventListener('click', function(e) {
                    if (isDropdownOpen &&
                        !userMenuButton.contains(e.target) &&
                        !userDropdown.contains(e.target)) {
                        toggleDropdown();
                    }
                });

                // Fermer avec la touche ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && isDropdownOpen) {
                        toggleDropdown();
                    }
                });

                // Empêcher la fermeture quand on clique dans le dropdown
                userDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Ajouter une transition sur l'icône chevron
                if (chevronIcon) {
                    chevronIcon.classList.add('transition-transform', 'duration-200');
                }
            }

            // Role-based active navigation
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-card-hover').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active-nav', 'bg-gray-700/70');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
