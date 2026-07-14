<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Affiche la liste des annonces avec le vrai total
     */
   public function index(Request $request)
{
    $user = Auth::user();

    $query = Announcement::with([
        'user',
        'service',
        'reads',
        'comments'
    ]);

    // ========= VISIBILITÉ =========

    if (!in_array($user->role, ['admin', 'chef'])) {

        $query->where(function ($q) use ($user) {

            $q->where('service_id', $user->service_id)
              ->orWhereNull('service_id');

        });

    }

    // ========= FILTRE SERVICE =========

    if ($request->filled('service') && $request->service != 'all') {

        $query->where('service_id', $request->service);

    }

    // ========= RECHERCHE =========

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");

        });

    }

    // ========= TRI =========

    $query->latest();

    $announcements = $query->paginate(10);

    return view('announcements.index', [

        'announcements'      => $announcements,

        'services'           => Service::orderBy('name')->get(),

        'services_count'     => Service::count(),

        'monthly_count'      => Announcement::whereMonth(
                                    'created_at',
                                    now()->month
                               )
                               ->whereYear(
                                    'created_at',
                                    now()->year
                               )
                               ->count(),

        'totalAnnouncements' => $query->count()

    ]);
}

 
    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $services = Service::all();
        return view('announcements.create', compact('services'));
    }

    /**
     * Enregistre une nouvelle annonce
     */
   public function store(Request $request)
{
    $request->validate([

        'title' => 'required|max:255',

        'content' => 'required',

        'service_id' => 'nullable'

    ]);

    Announcement::create([

        'title' => $request->title,

        'content' => $request->content,

        'user_id' => Auth::id(),

        'service_id' =>
            $request->service_id == 'all'
                ? null
                : $request->service_id,

        'priority' => 'normal'

    ]);

    return redirect()
        ->route('announcements.index')
        ->with('success', 'Annonce publiée avec succès.');
}

    /**
     * Supprime une annonce (seul l’auteur peut le faire)
     */
    public function destroy(Announcement $announcement)
    {
        if (Auth::id() !== $announcement->user_id) {
            abort(403, 'Vous ne pouvez pas supprimer cette annonce.');
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
                         ->with('success', 'Annonce supprimée.');
    }

   public function show(Announcement $announcement)
{
    $user = Auth::user();


    // Vérification des droits d'accès
    if (
        !in_array($user->role, ['admin', 'chef'])
        &&
        $announcement->service_id !== null
        &&
        $announcement->service_id !== $user->service_id
    ) {
        abort(403);
    }


    // Marquer comme lu
    \App\Models\AnnouncementRead::firstOrCreate(
        [
            'announcement_id' => $announcement->id,
            'user_id' => $user->id,
        ],
        [
            'read_at' => now(),
        ]
    );


    // Ajouter une vue
    $announcement->increment('views');


    return view('announcements.show', compact('announcement'));
} 
}