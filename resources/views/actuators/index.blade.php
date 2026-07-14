<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            ⚙️ Contrôle des équipements
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($actuators as $actuator)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $actuator->name }}</h3>
                                <p class="text-sm text-gray-500">Type : {{ $actuator->type }}</p>
                                <p class="text-xs text-gray-400">GPIO : {{ $actuator->gpio_pin ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $actuator->status ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $actuator->status ? 'ON' : 'OFF' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button onclick="toggleActuator({{ $actuator->id }}, this)" 
                                    class="flex-1 px-4 py-2 rounded-xl text-white font-bold transition
                                    {{ $actuator->status ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                {{ $actuator->status ? '🔴 Éteindre' : '🟢 Allumer' }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function toggleActuator(id, button) {
            fetch(`/actuator/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors du changement d\'état.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erreur réseau.');
            });
        }
    </script>
</x-app-layout>