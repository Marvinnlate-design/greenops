<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                GreenOps – <span class="text-emerald-600">Monitoring en temps réel</span>
            </h2>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">
                    🟢 Dernière mise à jour : {{ now()->format('H:i:s') }}
                </span>
                <button onclick="refreshData()" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-emerald-700 transition">
                    🔄 Actualiser
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats rapides -->
            <div class="grid grid-cols-3 gap-4 mb-8">

    <div class="bg-white p-5 rounded-2xl shadow border">
        <p class="text-xs text-gray-500 uppercase">Capteurs actifs</p>
        <p class="text-3xl font-bold text-gray-900">
            {{ $stats['total_sensors'] }}
        </p>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow border">
        <p class="text-xs text-gray-500 uppercase">Actionneurs actifs</p>
        <p class="text-3xl font-bold text-emerald-600">
            {{ $stats['active_actuators'] }}
        </p>
    </div>

    <button
        onclick="openAlerts()"
        class="bg-red-600 hover:bg-red-700 text-white rounded-2xl shadow p-5 text-left transition">

        <p class="text-xs uppercase opacity-80">
            Alertes actives
        </p>

        <p class="text-4xl font-black mt-2">
            {{ $stats['alerts'] }}
        </p>

        <p class="text-sm mt-2">
            Cliquer pour afficher
        </p>

    </button>

</div>

            <!-- Cartes des capteurs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($latestReadings as $id => $data)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-2xl">{{ $data['icon'] }}</span>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-2">
                                    {{ $data['sensor']->type }}
                                </p>
                                <p class="text-sm font-medium text-gray-900">{{ $data['sensor']->name }}</p>
                            </div>
                            @if($data['value'] !== null)
                                <span class="text-xs px-2 py-1 rounded-full {{ $data['threshold_ok'] ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $data['threshold_ok'] ? '✅ OK' : '⚠️ Alerte' }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-4">
                            @if($data['value'] !== null)
                                <p class="text-3xl font-black text-gray-900">
                                    {{ $data['value'] }} <span class="text-sm font-normal text-gray-400">{{ $data['unit'] }}</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $data['time'] ? $data['time']->diffForHumans() : 'Jamais mesuré' }}
                                </p>
                            @else
                                <p class="text-gray-400 italic">Aucune donnée</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                @foreach($chartData as $id => $data)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wider">
                            {{ $data['name'] }}
                        </h3>
                        <canvas id="chart-{{ $id }}" data-chart="{{ json_encode($data) }}"></canvas>
                    </div>
                @endforeach
            </div>

            <!-- Fenêtre Alertes -->

<div
    id="alertsModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-11/12 max-w-3xl max-h-[80vh] overflow-y-auto">

        <div class="flex justify-between items-center border-b px-6 py-4">

            <h2 class="text-xl font-bold">
                🚨 Alertes actives
            </h2>

            <button
                onclick="closeAlerts()"
                class="text-gray-500 hover:text-black text-2xl">

                ✕

            </button>

        </div>

        <div class="p-6">

            @if($alerts->count())

                @foreach($alerts as $alert)

                    <div class="border rounded-xl p-4 mb-3">

                        <div class="flex justify-between">

                            <div>

                                <h3 class="font-bold">
                                    {{ $alert->sensor->name }}
                                </h3>

                                <p class="text-gray-600 mt-1">

                                    Valeur :

                                    <strong>

                                        {{ $alert->value }}

                                        {{ $alert->sensor->unit }}

                                    </strong>

                                </p>

                                <p class="text-sm text-red-600">

                                    {{ $alert->message }}

                                </p>

                            </div>

                            <div class="text-right">

                                <p class="text-xs text-gray-500">

                                    {{ $alert->created_at->diffForHumans() }}

                                </p>

                                <form
                                    action="{{ route('alerts.read',$alert) }}"
                                    method="POST">

                                    @csrf

                                    @method('PATCH')

                                    <button
                                        class="mt-3 bg-emerald-600 text-white px-3 py-2 rounded">

                                        Marquer comme lu

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            @else

                <div class="text-center py-10">

                    ✅ Aucune alerte active.

                </div>

            @endif

        </div>

    </div>

</div>
            
                            

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser tous les graphiques
            document.querySelectorAll('[id^="chart-"]').forEach(canvas => {
                const data = JSON.parse(canvas.dataset.chart);
                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: data.unit,
                            data: data.values,
                            borderColor: getColor(data.color),
                            backgroundColor: getColor(data.color) + '20',
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f3f4f6' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            });
        });

        function getColor(name) {
            const colors = {
                'blue': '#3b82f6',
                'red': '#ef4444',
                'cyan': '#06b6d4',
                'indigo': '#6366f1',
                'purple': '#8b5cf6',
                'yellow': '#eab308',
                'green': '#22c55e',
                'orange': '#f97316',
                'gray': '#6b7280'
            };
            return colors[name] || '#10b981';
        }

        function openAlerts() {

    document
        .getElementById('alertsModal')
        .classList
        .remove('hidden');

    document
        .getElementById('alertsModal')
        .classList
        .add('flex');

}

function closeAlerts() {

    document
        .getElementById('alertsModal')
        .classList
        .remove('flex');

    document
        .getElementById('alertsModal')
        .classList
        .add('hidden');

}

        function refreshData() {
            location.reload();
        }

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">

    <h2 class="text-xl font-bold mb-5">
        🚨 Alertes en cours
    </h2>

    @forelse($alerts as $alert)

        <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded mb-3">

            <div class="font-bold text-red-700">
                {{ $alert->sensor->name }}
            </div>

            <div class="text-gray-700">
                {{ $alert->message }}
            </div>

            <div class="text-xs text-gray-500 mt-2">
                {{ $alert->created_at->diffForHumans() }}
            </div>

        </div>

    @empty

        <div class="text-green-600">
            ✅ Aucune alerte active
        </div>

    @endforelse

</div>

        // Auto-refresh toutes les 60 secondes
        setTimeout(() => { location.reload(); }, 60000);
    </script>
</x-app-layout>