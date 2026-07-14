<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Section : Mise à jour des informations --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Section : Mise à jour du mot de passe --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Section : Suppression du compte --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>