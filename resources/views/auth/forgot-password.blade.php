<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4">
        <div class="w-full max-w-md">
            <div class="bg-white py-8 px-10 shadow-xl rounded-2xl border border-gray-100">
                <div class="mb-6 text-sm text-gray-600 text-center">
                   Vous avez oublié votre mot de passe ? Indiquez votre adresse email, nous vous enverrons un lien de réinitialisation.
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition">
                            Envoyer le lien
                        </button>
                    </div>
                    <div class="text-center pt-4 border-t border-gray-100">
                        <a href="{{ route('login') }}" class="text-sm text-emerald-600 hover:text-emerald-800">
                            Retour à la connexion
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>