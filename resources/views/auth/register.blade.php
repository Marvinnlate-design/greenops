<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4">
        <div class="w-full max-w-md">
            <div class="bg-white py-8 px-10 shadow-xl rounded-2xl border border-gray-100">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('login') }}" class="text-sm text-emerald-600 hover:text-emerald-800">
                            Déjà inscrit ?
                        </a>
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition">
                            S’inscrire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>