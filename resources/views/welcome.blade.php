<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GreenOps – Communication interne ÉcoCycle</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css2?family=Figtree:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 30%, #dcfce7 60%, #bbf7d0 100%);
        }
        .agriculture-pattern {
            background-image: radial-gradient(circle at 10% 20%, rgba(134, 239, 172, 0.1) 0%, transparent 20%),
                              radial-gradient(circle at 90% 80%, rgba(74, 222, 128, 0.1) 0%, transparent 20%);
        }
        .floating { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 font-['Figtree']">
    <div class="min-h-screen flex flex-col hero-gradient agriculture-pattern">
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-emerald-100 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/restacycle.png.jpeg') }}" alt="Logo ÉcoCycle" class="h-12 w-auto" />
                    <div>
                        <span class="text-xl font-bold text-gray-900">Green<span class="text-emerald-600">Ops</span></span>
                        <p class="text-xs text-gray-500">par ÉcoCycle</p>
                    </div>
                </div>
                @if (Route::has('login'))
                    <nav class="flex items-center gap-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-500 text-white font-semibold rounded-full hover:shadow-lg transition">
                                Accéder à GreenOps
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-full hover:shadow-lg transition">
                                Connexion GreenOps
                            </a>
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 py-12 lg:py-24">
                <div class="text-center space-y-6">
                    <div class="inline-flex items-center gap-2 bg-emerald-100 rounded-full px-5 py-2">
                        <span class="text-emerald-700 font-bold">🌱 GreenOps</span>
                        <span class="text-sm text-emerald-600">Plateforme de communication interne ÉcoCycle</span>
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900">
                        Centralisez, échangez,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-green-500">agissez durablement</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        GreenOps est l’espace collaboratif des équipes ÉcoCycle. Annonces, mesures, gestion des agents : tout pour piloter votre impact écologique.
                    </p>
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-emerald-700 transition">
                            Accéder au tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-800 transition">
                            Connexion GreenOps
                        </a>
                    @endauth
                </div>

                <!-- Features (garder les mêmes cartes) -->
                <div class="grid md:grid-cols-3 gap-8 mt-20">
                    <div class="bg-white/70 rounded-2xl p-6 text-center shadow-md">
                        <span class="text-3xl">📢</span>
                        <h3 class="font-bold mt-2">Annonces internes</h3>
                        <p class="text-gray-600">Diffusez des informations ciblées par service.</p>
                    </div>
                    <div class="bg-white/70 rounded-2xl p-6 text-center shadow-md">
                        <span class="text-3xl">📊</span>
                        <h3 class="font-bold mt-2">Mesures techniques</h3>
                        <p class="text-gray-600">Suivez solaire, eau, biogaz, compost.</p>
                    </div>
                    <div class="bg-white/70 rounded-2xl p-6 text-center shadow-md">
                        <span class="text-3xl">👥</span>
                        <h3 class="font-bold mt-2">Gestion des agents</h3>
                        <p class="text-gray-600">Attribuez rôles et services facilement.</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="border-t border-emerald-100 bg-white/80 py-6 text-center text-sm text-gray-500">
            © {{ date('Y') }} ÉcoCycle – GreenOps, la communication interne responsable.
        </footer>
    </div>
</body>
</html>