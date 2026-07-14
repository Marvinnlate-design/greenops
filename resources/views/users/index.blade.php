<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            GreenOps – Gestion des agents
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Ajouter un nouvel agent</h3>
                <form method="post" action="{{ route('users.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Nom complet" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl" required />
                    </div>
                    <div>
                        <x-input-label for="email" value="Email professionnel" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl" required />
                    </div>
                    <div>
                        <x-input-label for="role" value="Rôle" />
                        <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="agent">Agent</option>
                            <option value="chef">Chef de service</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="service_id" value="Service" />
                        <select id="service_id" name="service_id" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-green-500">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="password" value="Mot de passe" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full rounded-xl" required />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-xl" required />
                    </div>
                    <div class="md:col-span-2">
                        <x-primary-button class="bg-green-700 hover:bg-green-800 rounded-xl px-6 py-3">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Ajouter l'agent
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Liste des agents ÉcoCycle</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-500 border-b border-gray-100">
                                <th class="p-4 font-medium">Nom</th>
                                <th class="p-4 font-medium">Email</th>
                                <th class="p-4 font-medium">Rôle</th>
                                <th class="p-4 font-medium">Service</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 border-b border-gray-100">
                                <td class="p-4 text-gray-900 font-medium">{{ $user->name }}</td>
                                <td class="p-4 text-gray-600">{{ $user->email }}</td>
                                <td class="p-4">
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-600">{{ $user->service->name ?? 'Aucun' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>