<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(Request $request): View
    {
        $subject = $request->query('objet');

        return view('pages.contact', [
            'selectedSubject' => array_key_exists((string) $subject, ContactMessage::SUBJECTS) ? $subject : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return to_route('contact')->with('status', 'Merci, votre message a bien été envoyé.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+().\s-]{10,20}$/'],
            'subject' => ['required', Rule::in(array_keys(ContactMessage::SUBJECTS))],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'consent' => ['accepted'],
        ]);

        $contactMessage = ContactMessage::create($validated);

        rescue(fn () => Mail::to(config('site.notification_email'))->send(new ContactMessageReceived($contactMessage)));

        return to_route('contact')->with('status', 'Merci, votre message a bien été envoyé. Nous vous répondons rapidement.');
    }
}
