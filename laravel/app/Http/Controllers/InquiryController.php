<?php

namespace App\Http\Controllers;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (filled($request->input('website_url'))) {
            return redirect()->route('quote.success');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:80'],
            'interest' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'],
        ]);

        $inquiry = Inquiry::query()->create([
            'name' => $data['name'],
            'company' => $data['company'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'country' => $data['country'] ?? null,
            'interest' => $data['interest'],
            'message' => $data['message'],
            'status' => 'new',
            'received_on' => now()->toDateString(),
            'attachment_path' => $request->file('attachment')?->store('inquiries', 'local'),
        ]);

        $email = Setting::current()->email;
        if ($email) {
            Mail::to($email)->send(new InquiryReceived($inquiry));
        }

        return redirect()->route('quote.success');
    }
}
