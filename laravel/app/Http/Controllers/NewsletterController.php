<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (filled($request->input('website_url'))) {
            return back();
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'max:190'],
        ]);

        Subscriber::query()->firstOrCreate(['email' => strtolower($data['email'])]);

        return back()->with('newsletter', 'Saved. Occasional notes from the Dhaka desk.');
    }
}
