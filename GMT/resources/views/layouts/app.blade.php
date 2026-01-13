<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Stock - @yield('title', 'Tableau de Bord')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Styles personnalisés -->
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #2c3e50; min-height: 100vh; }
        .nav-link { color: #ecf0f1 !important; }
        .nav-link:hover { background-color: #34495e; }
        .navbar-brand { font-weight: bold; color: #fff !important; }
        .stat-card { border-radius: 10px; transition: transform 0.3s; border: none; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .badge-role-admin { background-color: #e74c3c; }
        .badge-role-etudiant { background-color: #3498db; }
    </style>
</head>
<body>
    <!-- Navbar Top -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2980b9;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-boxes"></i> <strong>Gestion Stock</strong>
            </a>

            @auth
            <div class="navbar-nav ms-auto align-items-center">
                <span class="nav-item text-white me-3">
                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                    <span class="badge ms-2 {{ Auth::user()->role == 'admin' ? 'badge-role-admin' : 'badge-role-etudiant' }}">
                        {{ Auth::user()->role == 'admin' ? 'Administrateur' : 'Étudiant' }}
                    </span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="nav-item">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (si connecté) -->
            @auth
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-bars me-2"></i>Navigation
                    </h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link rounded py-2" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Tableau de Bord
                            </a>
                        </li>

                        @if(Auth::user()->role == 'admin')
                        <li class="nav-item mb-2">
                            <a class="nav-link rounded py-2" href="{{ route('materiels.index') }}">
                                <i class="fas fa-tools me-2"></i> Matériels
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link rounded py-2" href="{{ route('categories.index') }}">
                                <i class="fas fa-tags me-2"></i> Catégories
                            </a>
                        </li>
                        @endif

                        <li class="nav-item mb-2">
                            <a class="nav-link rounded py-2" href="{{ route('reservations.index') }}">
                                <i class="fas fa-calendar-check me-2"></i> Mes Réservations
                            </a>
                        </li>

                        @if(Auth::user()->role == 'admin')
                        <li class="nav-item mb-2">
                            <a class="nav-link rounded py-2" href="{{ route('reservations.index') }}">
                                <i class="fas fa-list-check me-2"></i> Toutes Réservations
                            </a>
                        </li>
                        @endif
                    </ul>

                    <hr class="text-white-50 my-4">

                    <!-- Quick Stats -->
                    <div class="text-white-50">
                        <small><i class="fas fa-info-circle me-1"></i> Gestion de matériel technique</small><br>
                        <small>LabManager Pro v1.0</small>
                    </div>
                </div>
            </div>
            @endauth

            <!-- Main Content Area -->
            <main class="@auth col-md-9 col-lg-10 @else col-12 @endauth p-4">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas @yield('icon', 'fa-home') me-2"></i>
                            @yield('title', 'Tableau de Bord')
                        </h1>
                        @hasSection('subtitle')
                            <p class="text-muted mb-0">@yield('subtitle')</p>
                        @endif
                    </div>
                    @yield('header-buttons')
                </div>

                <!-- Page Content -->
                <div class="content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });
    </script>
    @stack('scripts')
</body>
</html>
