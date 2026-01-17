<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Gestion Stock</title>

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
                    <i class="fas fa-boxes text-3xl text-blue-300"></i>
                </div>
            </div>
            <div class="absolute top-1/2 right-1/4 floating-icon" style="--i: 1;">
                <div class="p-4 rounded-2xl glass backdrop-blur-sm">
                    <i class="fas fa-tools text-3xl text-purple-300"></i>
                </div>
            </div>
            <div class="absolute bottom-1/3 left-1/3 floating-icon" style="--i: 2;">
                <div class="p-4 rounded-2xl glass backdrop-blur-sm">
                    <i class="fas fa-calendar-check text-3xl text-green-300"></i>
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
                        <h1 class="text-2xl font-bold">Gestion Stock</h1>
                        <p class="text-sm text-gray-300">Système de gestion de matériel</p>
                    </div>
                </div>

                <!-- Hero Text -->
                <div class="mt-12 lg:mt-0 max-w-md">
                    <div class="inline-flex items-center px-4 py-2 rounded-full glass text-sm mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        Système actif et sécurisé
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4 text-white text-shadow-lg">
                        Simplifiez la réservation<br>de votre <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-400">matériel
                            éducatif</span>
                    </h2>
                    <p class="text-gray-300 text-lg mb-8 animate__animated animate__fadeInUp animate__delay-1s">
                        Optimisez la gestion de votre matériel, simplifiez les réservations et boostez votre
                        productivité.
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-lg bg-white/10">
                                <i class="fas fa-shield-alt text-blue-400"></i>
                            </div>
                            <span>Authentification sécurisée</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-lg bg-white/10">
                                <i class="fas fa-bolt text-yellow-400"></i>
                            </div>
                            <span>Interface flexible</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 lg:mt-0 pt-6 border-t border-white/10">
                    <div class="flex flex-wrap items-center justify-between text-sm text-gray-200">
                        <div>
                            © 2024 Gestion Stock. Tous droits réservés.
                        </div>
                        <div class="flex items-center space-x-4 mt-2 lg:mt-0">
                            <a href="#" class="hover:text-white transition-colors">Conditions</a>
                            <a href="#" class="hover:text-white transition-colors">Confidentialité</a>
                            <a href="#" class="hover:text-white transition-colors">Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="lg:w-1/2 min-h-screen flex items-center justify-center p-4 lg:p-8">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-gray-50 to-blue-50 opacity-50"></div>
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60"
                height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none"
                fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath
                d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"
                /%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <!-- Login Card -->
            <div class="relative w-full max-w-md">
                <!-- Decorative Top -->
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center p-4 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg shadow-blue-500/30 mb-4">
                        <i class="fas fa-user-lock text-2xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Bon retour !</h2>
                    <p class="text-gray-600 mt-2">Connectez-vous à votre compte</p>
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

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div class="relative group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                            Adresse email
                        </label>
                        <div class="relative">
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                autofocus autocomplete="email" placeholder="votre@email.com"
                                class="w-full px-4 py-3 pl-12 bg-white border border-gray-300 rounded-xl focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow-lg focus:border-blue-500">
                            <div
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="far fa-envelope"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="relative group">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>
                                Mot de passe
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full px-4 py-3 pl-12 bg-white border border-gray-300 rounded-xl focus-ring focus:outline-none transition-all duration-200 placeholder-gray-400 focus:shadow-lg focus:border-blue-500">
                            <div
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fas fa-key"></i>
                             </div>
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <i id="passwordIcon" class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Terms -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer">
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" required
                                class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer">
                            <span class="ml-2 text-sm text-gray-600">
                                J'accepte les <a href="#"
                                    class="text-blue-600 hover:text-blue-800 transition-colors">conditions</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3.5 px-6 bg-gradient-to-r from-[#0F172A] to-[#1E293B] text-white font-medium rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 shine-effect group">
                        <span class="flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Se connecter
                        </span>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Ou continuer avec</span>
                        </div>
                    </div>

                    <!-- Social Login (Optional) -->
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button"
                            class="flex items-center justify-center p-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            <i class="fab fa-google text-red-500 mr-2 group-hover:scale-110 transition-transform"></i>
                            <span>Google</span>
                        </button>
                        <button type="button"
                            class="flex items-center justify-center p-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            <i
                                class="fab fa-microsoft text-blue-500 mr-2 group-hover:scale-110 transition-transform"></i>
                            <span>Microsoft</span>
                        </button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center mt-8 pt-6 border-t border-gray-200">
                        <p class="text-gray-600">
                            Pas encore de compte ?
                            <a href="{{ route('register') }}"
                                class="font-medium text-blue-600 hover:text-blue-800 transition-colors ml-1">
                                S'inscrire maintenant
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

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
        document.querySelectorAll('input').forEach(input => {
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

        // Shine effect on button hover
        const submitButton = document.querySelector('button[type="submit"]');
        let shineInterval;

        submitButton.addEventListener('mouseenter', () => {
            shineInterval = setInterval(() => {
                submitButton.classList.toggle('shine-effect');
            }, 3000);
        });

        submitButton.addEventListener('mouseleave', () => {
            clearInterval(shineInterval);
        });

        // Add floating animation to decorative icons
        document.querySelectorAll('.floating-icon').forEach((icon, index) => {
            icon.style.animationDelay = `${index * 0.5}s`;
        });

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
