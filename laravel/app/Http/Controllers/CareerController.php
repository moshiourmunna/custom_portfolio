<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationReceived;
use App\Models\CareerJob;
use App\Models\JobApplication;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        return view('pages.careers', [
            'page' => Page::query()->where('slug', 'careers')->first(),
            'jobs' => CareerJob::query()->where('status', 'open')->orderBy('sort')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $job = CareerJob::query()->where('slug', $slug)->where('status', 'open')->firstOrFail();

        return view('pages.career', compact('job'));
    }

    public function apply(Request $request, string $slug): RedirectResponse
    {
        if (filled($request->input('website_url'))) {
            return redirect()->route('quote.success');
        }

        $job = CareerJob::query()->where('slug', $slug)->where('status', 'open')->firstOrFail();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:4000'],
            'cv' => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx'],
        ]);

        $path = $request->file('cv')?->store('cvs', 'local');
        $application = JobApplication::query()->create([
            'career_job_id' => $job->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'] ?? null,
            'cv_path' => $path,
        ]);

        $email = Setting::current()->email;
        if ($email) {
            Mail::to($email)->send(new ApplicationReceived($application));
        }

        return redirect()->route('quote.success');
    }
}
