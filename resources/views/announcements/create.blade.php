<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl lg:text-3xl text-gray-900 leading-tight">
                    GreenOps – Publier une annonce
                </h2>
                <p class="text-gray-600 mt-2 flex items-center gap-2">
                    <span class="text-sm bg-gradient-to-r from-emerald-50 to-green-50 text-emerald-700 font-medium px-3 py-1 rounded-full">
                        📢 Communication interne
                    </span>
                </p>
            </div>
            <a href="{{ route('announcements.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour aux annonces
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Informations de publication -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 text-white p-6 rounded-2xl shadow-lg mb-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-white/30 to-white/10 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Vous publiez en tant que :</h3>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6 mt-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center sm:text-right">
                        <div class="text-sm opacity-90">Date de publication</div>
                        <div class="text-2xl font-bold">{{ now()->format('d/m/Y') }}</div>
                        <div class="text-sm opacity-80">{{ now()->format('H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Formulaire principal -->
            <div class="bg-gradient-to-br from-white to-emerald-50 p-6 sm:p-8 shadow-xl border border-emerald-100 rounded-2xl">
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-gradient-to-br from-emerald-500 to-green-500 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Nouvelle Annonce</h3>
                        <p class="text-gray-600 text-sm">Remplissez les champs ci-dessous pour publier votre message</p>
                    </div>
                </div>

                <!-- Indicateur de progression -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-emerald-700">Progression du formulaire</span>
                        <span class="text-sm font-bold text-gray-700">1/3</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-green-500 rounded-full" style="width: 33%"></div>
                    </div>
                </div>

                <form method="post" action="{{ route('announcements.store') }}" class="space-y-8">
                    @csrf
                    
                    <!-- Section 1 : Informations de base -->
                    <div class="space-y-6 border-l-4 border-emerald-500 pl-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">1</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Informations de base</h4>
                        </div>

                        <!-- Titre -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <x-input-label for="title" value="Titre de l'annonce *" class="text-lg font-semibold text-gray-900" />
                            </div>
                            <x-text-input 
                                id="title" 
                                name="title" 
                                type="text" 
                                class="block w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all duration-200 p-4 text-lg" 
                                placeholder="Ex: Maintenance système solaire - Importante mise à jour" 
                                required 
                            />
                            <div class="flex items-start gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Choisissez un titre clair et informatif qui résume votre annonce</span>
                            </div>
                        </div>

                        <!-- Service -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <x-input-label for="service_id" value="Service concerné *" class="text-lg font-semibold text-gray-900" />
                            </div>
                            <div class="relative">
                               <select 
    id="service_id" 
    name="service_id" 
    class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200 p-4 text-gray-900 bg-white appearance-none"
    required
>
    <option value="" disabled selected>Sélectionnez un service</option>
    <option value="all">📢 Tous les services</option>  <!-- Nouvelle option -->
    @foreach($services as $service)
        <option value="{{ $service->id }}" class="py-2">{{ $service->name }}</option>
    @endforeach
</select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Cette annonce sera visible par tous les membres du service sélectionné</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 : Contenu détaillé -->
                    <div class="space-y-6 border-l-4 border-emerald-500 pl-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">2</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Contenu détaillé</h4>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <x-input-label for="content" value="Contenu de l'annonce *" class="text-lg font-semibold text-gray-900" />
                            </div>
                            <textarea 
                                id="content" 
                                name="content" 
                                rows="8" 
                                class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200 p-4 resize-none" 
                                placeholder="Décrivez en détail votre annonce...&#10;&#10;• Informations importantes&#10;• Dates clés&#10;• Actions requises&#10;• Contact concerné"
                                required
                            ></textarea>
                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-start gap-2 text-gray-500">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Soyez précis et complet dans votre message pour une meilleure compréhension</span>
                                </div>
                                <span class="text-gray-400 font-medium">Minimum 50 caractères</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3 : Confirmation -->
                    <div class="space-y-6 border-l-4 border-emerald-500 pl-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">3</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Confirmation</h4>
                        </div>

                        <!-- Récapitulatif -->
                        <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-200 rounded-2xl p-6">
                            <h5 class="font-bold text-emerald-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Récapitulatif de votre annonce
                            </h5>
                            <div class="space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <div class="flex-1">
                                        <div class="text-sm text-gray-500">Vous publiez en tant que :</div>
                                        <div class="font-semibold text-gray-900 flex items-center gap-2">
                                            <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-green-500 rounded-full flex items-center justify-center text-white text-xs">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                            {{ Auth::user()->name }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm text-gray-500">Date de publication :</div>
                                        <div class="font-semibold text-gray-900">{{ now()->format('d/m/Y à H:i') }}</div>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-emerald-200">
                                    <div class="text-sm text-gray-500">Votre annonce sera visible par :</div>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-sm font-medium px-3 py-1.5 rounded-full">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Tous les membres du service sélectionné
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions finales -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                                <div class="text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span>Vous êtes responsable du contenu publié</span>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                                    <a href="{{ route('announcements.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Annuler
                                    </a>
                                    <x-primary-button class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-700 hover:to-green-600 text-white font-bold rounded-xl px-8 py-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Publier l'annonce
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Conseils -->
            <div class="mt-8 bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100 rounded-2xl p-6">
                <h4 class="font-bold text-lg text-emerald-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Bonnes pratiques de publication
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3 p-3 bg-white/50 rounded-xl">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-emerald-600 font-bold">✓</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Clarté</div>
                            <div class="text-sm text-gray-600 mt-1">Utilisez un langage simple et direct</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white/50 rounded-xl">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-emerald-600 font-bold">✓</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Pertinence</div>
                            <div class="text-sm text-gray-600 mt-1">Ciblez le bon service concerné</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white/50 rounded-xl">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-emerald-600 font-bold">✓</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Concision</div>
                            <div class="text-sm text-gray-600 mt-1">Allez droit au but, mais soyez complet</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white/50 rounded-xl">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-emerald-600 font-bold">✓</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Utilité</div>
                            <div class="text-sm text-gray-600 mt-1">Fournissez des informations actionnables</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>