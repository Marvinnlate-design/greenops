<?php

namespace App\Http\Controllers;

use App\Models\Alert;

class AlertController extends Controller
{
    /**
     * Liste des alertes actives
     */
    public function index()
    {
        $alerts = Alert::with('sensor')
            ->latest()
            ->paginate(20);

        return view('alerts.index', compact('alerts'));
    }

    /**
     * Marquer une alerte comme lue
     */
    public function markAsRead(Alert $alert)
    {
        $alert->update([
            'is_read' => true
        ]);

        return redirect()->back()->with(
            'success',
            'Alerte marquée comme lue.'
        );
    }

    /**
     * Tout marquer comme lu
     */
    public function markAllAsRead()
    {
        Alert::where('is_read', false)
            ->update([
                'is_read' => true
            ]);

        return redirect()->back()->with(
            'success',
            'Toutes les alertes ont été marquées comme lues.'
        );
    }
}