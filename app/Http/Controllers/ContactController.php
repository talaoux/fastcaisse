<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Enregistre un message envoyé depuis la section #contact
     * de la page d'accueil FastCaisse.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // TODO: enregistrer $validated en base de données ou envoyer un e-mail
        // Contact::create($validated);

        return back()->with('success', 'Votre message a bien été envoyé, merci !');
    }
}