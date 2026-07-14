<x-app-layout>

<x-slot name="header">

<h2 class="font-bold text-2xl text-gray-900">
📢 {{ $announcement->title }}
</h2>

</x-slot>


<div class="py-8">

<div class="max-w-4xl mx-auto px-4">


<div class="bg-white rounded-2xl shadow-lg p-8">


<div class="flex justify-between items-center mb-6">


<div>

<span class="px-3 py-1 rounded-full text-sm bg-emerald-100 text-emerald-700">

{{ $announcement->service?->name ?? 'Tous les services' }}

</span>


<h1 class="text-3xl font-bold mt-4">
{{ $announcement->title }}
</h1>

</div>


<div class="text-right text-sm text-gray-500">

<p>
Publié par :
<strong>
{{ $announcement->user->name }}
</strong>
</p>


<p>
{{ $announcement->created_at->diffForHumans() }}
</p>


</div>


</div>



<div class="border-t pt-6">

<p class="text-gray-700 whitespace-pre-line leading-relaxed">

{{ $announcement->content }}

</p>

</div>



<div class="mt-8 border-t pt-5 flex gap-6 text-sm text-gray-500">

<span>
👁️ {{ $announcement->views }} vues
</span>


<span>
💬 {{ $announcement->comments->count() }} commentaires
</span>


<span>
📅 {{ $announcement->created_at->format('d/m/Y') }}
</span>


</div>


</div>


</div>

</div>


</x-app-layout>