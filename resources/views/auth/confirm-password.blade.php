<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4">
        <div class="w-full max-w-md">
            <div class="bg-white py-8 px-10 shadow-xl rounded-2xl border border-gray-100">
                <div class="mb-6 text-sm text-gray-600 text-center">
                    Veuillez confirmer votre mot de passe avant de continuer.
                </div>
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition">
                            Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>