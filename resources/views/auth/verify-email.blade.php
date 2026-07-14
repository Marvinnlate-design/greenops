<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4">
        <div class="w-full max-w-md">
            <div class="bg-white py-8 px-10 shadow-xl rounded-2xl border border-gray-100 text-center space-y-6">
                <div class="text-sm text-gray-600">
                    Merci de vous être inscrit ! Avant de continuer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
                </div>
                @if (session('status') == 'verification-link-sent')
                    <div class="text-sm text-emerald-600 font-medium bg-emerald-50 p-3 rounded-xl">
                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                    </div>
                @endif
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition">
                        Renvoyer l’email de vérification
                    </button>
                </form>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="text-sm text-gray-500 hover:text-gray-700 transition">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>