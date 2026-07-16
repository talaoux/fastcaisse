<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Handle the contact form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Here you can add logic to send an email or store the message
        // For now, we'll just redirect back with a success message
        
        return back()->with('success', 'Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.');
    }
}