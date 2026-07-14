<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl lg:text-3xl text-gray-900 leading-tight">
                    GreenOps – Annonces
                </h2>
                <p class="text-gray-600 mt-2">Plateforme de communication interne ÉcoCycle</p>
            </div>
            <a href="{{ route('announcements.create') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-green-500 text-white font-bold rounded-xl px-6 py-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Annonce
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cartes statistiques avec le vrai total -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100 rounded-2xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-emerald-700 font-medium">Total Annonces</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalAnnouncements }}</p>
                        </div>
                        <div class="bg-emerald-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Services actifs</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $services_count }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-100 rounded-2xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-amber-700 font-medium">Mois en cours</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $monthly_count }}</p>
                        </div>
                        <div class="bg-amber-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="mb-8 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl">
                <div class="flex items-center gap-4">
                    <span class="font-medium text-gray-700">Filtrer par :</span>
                    <select id="serviceFilter" class="border-gray-300 rounded-xl focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                        <option value="all">Tous les services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative w-full sm:w-auto">
                    <input type="text" id="searchInput" placeholder="Rechercher une annonce..." class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-xl focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Liste des annonces -->
            <div class="space-y-6" id="announcementsList">
                @forelse($announcements as $announcement)
                    <div class="group bg-gradient-to-br from-white to-emerald-50 p-6 shadow-xl border border-gray-200 hover:border-emerald-300 rounded-2xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="bg-gradient-to-br from-emerald-500 to-green-500 text-white font-bold text-sm px-3 py-1 rounded-full">
                                        {{ $announcement->service ? $announcement->service->name : '📢 Tous les services' }}
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ $announcement->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <a href="{{ route('announcements.show',$announcement) }}">

<h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-700 transition-colors">

{{ $announcement->title }}

</h3>

</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Publié par</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ optional($announcement->user)->name ?? 'Utilisateur inconnu' }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($announcement->user->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $announcement->content }}</p>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $announcement->created_at->format('d/m/Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $announcement->created_at->format('H:i') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <!-- Bouton Commenter -->
                                <button onclick="openCommentModal({{ $announcement->id }})" 
                                        class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 font-medium text-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Commenter
                                </button>
                                <!-- Bouton Partager -->
                                <button onclick="shareAnnouncement({{ $announcement->id }}, '{{ addslashes($announcement->title) }}')"
                                        class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 font-medium text-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Partager
                                </button>
                                <!-- Bouton Supprimer (visible uniquement pour l’auteur) -->
                                @if(auth()->id() === $announcement->user_id)
                                    <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" 
                                          class="inline" onsubmit="return confirm('Supprimer définitivement cette annonce ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 font-medium text-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-100 to-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune annonce pour le moment</h3>
                        <p class="text-gray-600 mb-6">Soyez le premier à publier une annonce importante</p>
                        <a href="{{ route('announcements.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-green-500 text-white font-bold rounded-xl px-8 py-3 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer la première annonce
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($announcements->hasPages())
                <div class="mt-8">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts pour les interactions commentaire / partage -->
    <script>
        function openCommentModal(announcementId) {
            let comment = prompt("Écrivez votre commentaire :");
            if (comment && comment.trim() !== "") {
                // À remplacer par une vraie route si vous implémentez les commentaires
                alert("Fonctionnalité de commentaire en cours de développement.\nVotre commentaire : " + comment);
                // Exemple d’appel AJAX futur :
                /*
                fetch(`/announcements/${announcementId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content: comment })
                }).then(response => {
                    if (response.ok) location.reload();
                    else alert("Erreur lors de l'ajout du commentaire.");
                });
                */
            }
        }

        function shareAnnouncement(announcementId, title) {
            let url = window.location.href.split('?')[0] + '#annonce-' + announcementId;
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: "Lisez cette annonce sur GreenOps",
                    url: url
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url);
                alert("Lien copié dans le presse-papier !");
            }
        }

        // Filtrage simple par service et recherche (côté client pour l’exemple, mais vous pouvez faire du JS avec AJAX)
        document.getElementById('serviceFilter')?.addEventListener('change', function() {
            let service = this.value;
            // Ici vous pouvez recharger la page avec un paramètre GET ou faire une requête AJAX
           const url = new URL(window.location);

url.searchParams.set('service', service);

window.location = url;  
        });
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            let term = this.value.toLowerCase();
            let cards = document.querySelectorAll('#announcementsList > div');
            cards.forEach(card => {
                let title = card.querySelector('h3')?.innerText.toLowerCase() || '';
                let content = card.querySelector('.prose p')?.innerText.toLowerCase() || '';
                if (title.includes(term) || content.includes(term)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>