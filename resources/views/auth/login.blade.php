<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4">
        <div class="w-full max-w-md">
            <div class="bg-white py-8 px-10 shadow-xl rounded-2xl border border-gray-100">
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <div class="flex justify-between items-center">
                            <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-emerald-600">Oublié ?</a>
                            @endif
                        </div>
                        <input type="password" name="password" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-emerald-600">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Rester connecté</label>
                    </div>
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition">
                        Se connecter à GreenOps
                    </button>
                </form>
                <div class="mt-6 text-center text-sm">
                    <a href="{{ route('register') }}" class="text-emerald-600 font-semibold">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>