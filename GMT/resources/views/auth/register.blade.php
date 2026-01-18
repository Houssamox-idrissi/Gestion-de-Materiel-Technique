<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - Gestion Stock</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        /* Custom animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Glass morphism */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Shine effect for button */
        .shine-effect {
            position: relative;
            overflow: hidden;
        }

        .shine-effect::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right,
                    transparent 0%,
                    rgba(255, 255, 255, 0.3) 50%,
                    transparent 100%);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        /* Floating elements */
        .floating-icon {
            animation: float 4s ease-in-out infinite;
            animation-delay: calc(var(--i) * 0.5s);
        }

        /* Custom transitions */
        .transition-transform-custom {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Focus styles */
        .focus-ring {
            transition: all 0.2s ease;
        }

        .focus-ring:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            border-color: #2563eb;
        }

        /* Text shadows */
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .text-shadow-lg {
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 overflow-hidden">
    <!-- Main Container -->
    <div class="min-h-screen flex flex-col lg:flex-row overflow-hidden">

        <!-- Left Panel - Visual/Branding -->
        <div class="lg:w-1/2 relative min-h-[40vh] lg:min-h-screen overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#0F172A] via-[#111822] to-[#0F172A] animate-gradient">
            </div>

            <!-- Animated Gradient Orbs -->
            <div
                class="absolute top-1/4 -left-20 w-96 h-96 bg-gradient-to-br from-blue-600/20 to-purple-600/20 rounded-full blur-3xl animate-float">
            </div>
            <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-gradient-to-br from-purple-600/20 to-pink-600/20 rounded-full blur-3xl animate-float"
                style="animation-delay: 2s;"></div>

            <!-- Floating Icons -->
            <div class="absolute top-1/3 left-1/4 floating-icon" style="--i: 0;">
                <div class="p-4 rounded-2xl glass backdrop-blur-sm">
                    <i class="fas fa-user-plus text-3xl text-blue-300"></i>
                </div>
            </div>
            <div class="absolute top-1/2 right-1/4 floating-icon" style="--i: 1;">
                <div class="p-4 rounded-2xl glass backdrop-blur-sm">
                    <i class="fas fa-graduation-cap text-3xl text-purple-300"></i>
                </div>
            </div>
            <div class="absolute bottom-1/3 left-1/3 floating-icon" style="--i: 2;">
                <div class="p-4 rounded-2xl glass backdrop-blur-sm">
                    <i class="fas fa-users text-3xl text-green-300"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="relative z-10 h-full flex flex-col justify-between p-8 lg:p-12 text-white">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="p-3 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <i class="fas fa-boxes text-2xl text-blue-300"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white text-shadow">Gestion Stock</h1>
                        <p class="text-sm text-gray-200">Système de gestion de matériel</p>
                    </div>
                </div>

                <!-- Hero Text -->
                <div class="mt-8 lg:mt-0 max-w-md">
                    <div
                        class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-sm mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-white font-medium">Rejoignez notre communauté</span>
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4 text-white text-shadow-lg">
                        Créez votre compte<br>pour accéder au <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-400">matériel
                            pédagogique</span>
                    </h2>
                    <p class="text-gray-200 text-lg mb-8">
                        Inscrivez-vous pour réserver du matériel, suivre vos équipements et gérer vos ressources en
                        toute simplicité.
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-lg glass">
                                <i class="fas fa-calendar-check text-blue-400"></i>
                            </div>
                            <span class="text-gray-100">Réservations simplifiées</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-lg glass">
                                <i class="fas fa-chart-line text-yellow-400"></i>
                            </div>
                            <span class="text-gray-100">Suivi en temps réel</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-lg glass">
                                <i class="fas fa-shield-alt text-green-400"></i>
                            </div>
                            <span class="text-gray-100">Compte sécurisé</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 lg:mt-0 pt-6 border-t border-white/20">
                    <div class="flex flex-wrap items-center justify-between text-sm">
                        <div class="text-gray-300 font-medium">
                            © 2024 Gestion Stock. Tous droits réservés.
                        </div>
                        <div class="flex items-center space-x-4 mt-2 lg:mt-0">
                            <a href="#"
                                class="text-gray-300 hover:text-white transition-colors font-medium">Conditions</a>
                            <a href="#"
                                class="text-gray-300 hover:text-white transition-colors font-medium">Confidentialité</a>
                            <a href="#"
                                class="text-gray-300 hover:text-white transition-colors font-medium">Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Registration Form -->
        <div class="lg:w-1/2 h-screen flex items-start lg:items-center justify-center p-4 lg:p-8 overflow-y-auto">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-gray-50 to-blue-50 opacity-50"></div>
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60"
                height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none"
                fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath
                d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"
                /%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <!-- Registration Card - Now with max-height and scroll -->
            <div class="relative w-full max-w-md my-8">
                <!-- Decorative Top -->
                <div class="text-center mt-96">
                    <div
                        class="inline-flex items-center justify-center p-4 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg shadow-green-500/30 mb-4">
                        <i class="fas fa-user-plus text-2xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Créer un compte</h2>
                    <p class="text-gray-600 mt-2">Rejoignez notre plateforme en quelques étapes</p>
                </div>

                <!-- Flash Messages -->
                @if (session('status'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 animate__animated animate__slideInDown">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            <span class="text-blue-700">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div
                        class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200 animate__animated animate__shakeX">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-700 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4 pb-8">
                    @csrf

                    <!-- Personal Information -->
                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100/30 p-4 rounded-xl border border-gray-200 mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                            Informations personnelles
                        </h3>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom complet *
                            </label>
                            <div class="relative">
                                <input id="name" name="name" type="text" value="{{ old('name') }}"
                                    required autofocus autocomplete="name" placeholder="Votre nom complet"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow focus:border-blue-500">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Adresse email *
                            </label>
                            <div class="relative">
                                <input id="email" name="email" type="email" value="{{ old('email') }}"
                                    required autocomplete="email" placeholder="votre@email.com"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow focus:border-blue-500">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Matricule -->
                        <div class="mb-3">
                            <label for="matricule" class="block text-sm font-medium text-gray-700 mb-1">
                                Numéro d'étudiant
                            </label>
                            <div class="relative">
                                <input id="matricule" name="matricule" type="text" value="{{ old('matricule') }}"
                                    placeholder="Votre numéro d'étudiant"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow focus:border-blue-500">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-id-card"></i>
                                </div>
                            </div>
                            @error('matricule')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Telephone -->
                        <div class="mb-3">
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                                Téléphone
                            </label>
                            <div class="relative">
                                <input id="telephone" name="telephone" type="text"
                                    value="{{ old('telephone') }}" placeholder="Votre numéro de téléphone"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow focus:border-blue-500">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            @error('telephone')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Département -->
                        <div>
                            <label for="departement" class="block text-sm font-medium text-gray-700 mb-1">
                                Département
                            </label>
                            <div class="relative">
                                <select id="departement" name="departement"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus-ring focus:outline-none transition-all duration-200 appearance-none">
                                    <option value="">Sélectionnez votre département...</option>
                                    <option value="Informatique"
                                        {{ old('departement') == 'Informatique' ? 'selected' : '' }}>Informatique
                                    </option>
                                    <option value="Électronique"
                                        {{ old('departement') == 'Électronique' ? 'selected' : '' }}>Électronique
                                    </option>
                                    <option value="Mécanique"
                                        {{ old('departement') == 'Mécanique' ? 'selected' : '' }}>Mécanique</option>
                                    <option value="Génie Civil"
                                        {{ old('departement') == 'Génie Civil' ? 'selected' : '' }}>Génie Civil
                                    </option>
                                    <option value="Autre" {{ old('departement') == 'Autre' ? 'selected' : '' }}>Autre
                                    </option>
                                </select>
                                <div
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            @error('departement')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Security Information -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100/30 p-4 rounded-xl border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-lock mr-2 text-green-500"></i>
                            Sécurité du compte
                        </h3>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Mot de passe *
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                    autocomplete="new-password" placeholder="••••••••"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow focus:border-blue-500">
                                <div class="absolute right-10 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-key"></i>
                                </div>
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i id="passwordIcon" class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères avec chiffres et lettres</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmer le mot de passe *
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    required autocomplete="new-password" placeholder="••••••••"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow focus:border-blue-500">
                                <div class="absolute right-10 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-key"></i>
                                </div>
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i id="passwordConfirmationIcon" class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start space-x-2 mt-4">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer">
                        <label for="terms" class="text-sm text-gray-600">
                            J'accepte les
                            <a href="#"
                                class="text-blue-600 hover:text-blue-800 font-medium transition-colors">conditions
                                d'utilisation</a>
                            et la
                            <a href="#"
                                class="text-blue-600 hover:text-blue-800 font-medium transition-colors">politique de
                                confidentialité</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3.5 px-6 bg-gradient-to-r from-green-600 to-emerald-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-green-500/20 transition-all duration-300 mt-6 group">
                        <span class="flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform"></i>
                            Créer mon compte
                        </span>
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mt-6 pt-4 border-t border-gray-200">
                        <p class="text-gray-600">
                            Vous avez déjà un compte ?
                            <a href="{{ route('login') }}"
                                class="font-medium text-blue-600 hover:text-blue-800 transition-colors ml-1">
                                Se connecter
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const iconId = fieldId === 'password' ? 'passwordIcon' : 'passwordConfirmationIcon';
            const passwordIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Form validation animation
        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('invalid', (e) => {
                e.preventDefault();
                input.classList.add('border-red-500', 'animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    input.classList.remove('animate__animated', 'animate__shakeX');
                }, 1000);
            });

            input.addEventListener('input', () => {
                input.classList.remove('border-red-500');
            });
        });

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const strengthIndicator = document.getElementById('passwordStrength');
                if (!strengthIndicator) return;

                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;

                const strengthText = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
                const strengthColors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500',
                    'text-emerald-500'
                ];

                strengthIndicator.textContent = `Force : ${strengthText[strength]}`;
                strengthIndicator.className = `text-xs mt-1 ${strengthColors[strength]}`;
            });
        }

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('[class*="from-blue-50"], [class*="from-red-50"]').forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>

</html>
