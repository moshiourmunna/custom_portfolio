<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerJob;
use App\Models\GalleryItem;
use App\Models\Inquiry;
use App\Models\Media;
use App\Models\MediaFolder;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Inertia\Inertia;
use Inertia\Response;

class LibraryController extends Controller
{
    public function gallery(): Response
    {
        return Inertia::render('Gallery', [
            'items' => GalleryItem::query()->orderBy('sort')->get(),
            'media' => Media::query()->latest()->limit(40)->get(),
        ]);
    }

    public function storeGallery(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'album' => ['required', 'string', 'max:64'],
            'image' => ['required', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);
        GalleryItem::query()->create($data + ['sort' => GalleryItem::query()->max('sort') + 1]);

        return back()->with('status', 'Gallery item added.');
    }

    public function destroyGallery(GalleryItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('status', 'Gallery item removed.');
    }

    public function news(): Response
    {
        return Inertia::render('News/Index', ['posts' => Post::query()->latest('published_on')->get()]);
    }

    public function createPost(): Response
    {
        return Inertia::render('News/Form', ['post' => null]);
    }

    public function storePost(Request $request): RedirectResponse
    {
        $post = Post::query()->create($this->postData($request));

        return redirect()->route('admin.news.edit', $post)->with('status', 'News saved.');
    }

    public function editPost(Post $post): Response
    {
        return Inertia::render('News/Form', ['post' => $post]);
    }

    public function updatePost(Request $request, Post $post): RedirectResponse
    {
        $post->update($this->postData($request, $post->id));

        return back()->with('status', 'News saved.');
    }

    public function destroyPost(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.news')->with('status', 'News removed.');
    }

    public function careers(): Response
    {
        return Inertia::render('Careers/Index', [
            'jobs' => CareerJob::query()->withCount('applications')->orderBy('sort')->get(),
        ]);
    }

    public function createJob(): Response
    {
        return Inertia::render('Careers/Form', ['job' => null]);
    }

    public function storeJob(Request $request): RedirectResponse
    {
        $job = CareerJob::query()->create($this->jobData($request));

        return redirect()->route('admin.careers.edit', $job)->with('status', 'Role saved.');
    }

    public function editJob(CareerJob $job): Response
    {
        $job->load('applications');

        return Inertia::render('Careers/Form', ['job' => $job]);
    }

    public function updateJob(Request $request, CareerJob $job): RedirectResponse
    {
        $job->update($this->jobData($request, $job->id));

        return back()->with('status', 'Role saved.');
    }

    public function destroyJob(CareerJob $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route('admin.careers')->with('status', 'Role removed.');
    }

    public function inquiries(): Response
    {
        $pages = Page::query()->whereIn('slug', ['contact', 'privacy'])->get(['id', 'slug'])->keyBy('slug');

        return Inertia::render('Inquiries', [
            'inquiries' => Inquiry::query()->latest('received_on')->latest('id')->get(),
            'links' => [
                'contact' => $pages->get('contact')?->id,
                'privacy' => $pages->get('privacy')?->id,
            ],
        ]);
    }

    public function updateInquiry(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $inquiry->update($request->validate([
            'status' => ['required', 'string', 'max:32'],
            'note' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Inquiry updated.');
    }

    public function downloadAttachment(Inquiry $inquiry): StreamedResponse
    {
        abort_unless($inquiry->attachment_path && Storage::disk('local')->exists($inquiry->attachment_path), 404);

        return Storage::disk('local')->download($inquiry->attachment_path);
    }

    public function destroyInquiry(Inquiry $inquiry): RedirectResponse
    {
        if ($inquiry->attachment_path) {
            Storage::disk('local')->delete($inquiry->attachment_path);
        }
        $inquiry->delete();

        return back()->with('status', 'Inquiry removed.');
    }

    public function media(): Response
    {
        return Inertia::render('Media', [
            'items' => Media::query()->with('folder:id,name')->latest()->get(),
            'folders' => MediaFolder::query()->withCount('media')->orderBy('sort')->get(),
        ]);
    }

    public function storeMedia(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'file' => ['required', 'image', 'max:8192'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);
        $path = $request->file('file')->store('media/uploads', 'public');
        $size = @getimagesize($request->file('file')->getRealPath()) ?: [null, null];
        $folder = MediaFolder::query()->firstOrCreate(['name' => 'Uploads'], ['sort' => 2]);
        Media::query()->create([
            'media_folder_id' => $folder->id,
            'path' => $path,
            'disk' => 'public',
            'alt' => $data['alt'] ?? null,
            'caption' => $data['caption'] ?? $request->file('file')->getClientOriginalName(),
            'mime' => $request->file('file')->getMimeType(),
            'width' => $size[0],
            'height' => $size[1],
            'bytes' => $request->file('file')->getSize(),
        ]);

        return back()->with('status', 'Image uploaded.');
    }

    public function destroyMedia(Media $medium): RedirectResponse
    {
        $medium->delete();

        return back()->with('status', 'Media record removed. The file stays on disk until cleaned up.');
    }

    private function postData(Request $request, ?int $ignore = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:posts,slug,'.($ignore ?: 'NULL')],
            'lead' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'cover' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:64'],
            'published_on' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:32'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        return $data;
    }

    private function jobData(Request $request, ?int $ignore = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:career_jobs,slug,'.($ignore ?: 'NULL')],
            'location' => ['nullable', 'string', 'max:160'],
            'department' => ['nullable', 'string', 'max:64'],
            'employment_type' => ['nullable', 'string', 'max:32'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:32'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        return $data;
    }
}
