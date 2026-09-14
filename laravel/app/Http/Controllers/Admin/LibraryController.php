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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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

    public function createInquiry(): Response
    {
        return Inertia::render('Inquiries/Form');
    }

    public function storeInquiry(Request $request): RedirectResponse
    {
        $data = $this->inquiryData($request, true);
        Inquiry::query()->create([
            'name' => $data['name'] ?? null,
            'company' => $data['company'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
            'interest' => $data['interest'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'image' => $data['image'] ?? null,
            'received_on' => $data['received_on'] ?? now()->toDateString(),
            'attachment_path' => $request->file('attachment')?->store('inquiries', 'local'),
        ]);

        return redirect()->route('admin.inquiries')->with('status', 'Inquiry added.');
    }

    public function updateInquiry(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:32'],
            'note' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'attachment' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp'],
        ]);
        $inquiry->status = $data['status'];
        if ($request->exists('note')) {
            $inquiry->note = $data['note'];
        }
        if ($request->exists('image')) {
            $inquiry->image = $data['image'] ?: null;
        }
        if ($request->hasFile('attachment')) {
            if ($inquiry->attachment_path) {
                Storage::disk('local')->delete($inquiry->attachment_path);
            }
            $inquiry->attachment_path = $request->file('attachment')->store('inquiries', 'local');
        }
        $inquiry->save();

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

    public function upload(Request $request): JsonResponse
    {
        $media = $this->storeUploadedFile($request);

        return response()->json([
            'id' => $media->id,
            'path' => $media->path,
            'url' => Storage::disk('public')->url($media->path),
            'mime' => $media->mime,
            'name' => basename($media->path),
        ]);
    }

    public function storeMedia(Request $request): RedirectResponse
    {
        $this->storeUploadedFile($request);

        return back()->with('status', 'File uploaded.');
    }

    public function destroyMedia(Media $medium): RedirectResponse
    {
        $medium->delete();

        return back()->with('status', 'Media record removed. The file stays on disk until cleaned up.');
    }

    private function inquiryData(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:160'],
            'company' => ['nullable', 'string', 'max:180'],
            'email' => ['nullable', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:64'],
            'country' => ['nullable', 'string', 'max:80'],
            'interest' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:32'],
            'note' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'received_on' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp'],
        ]);
        if ($creating && ! filled($data['name'] ?? null) && ! filled($data['company'] ?? null)) {
            throw ValidationException::withMessages(['company' => 'Add a company or a name.']);
        }

        return $data;
    }

    private function storeUploadedFile(Request $request): Media
    {
        $request->validate([
            'file' => ['required', 'file', 'max:81920'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);
        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $images = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $videos = ['mp4', 'webm', 'mov'];
        $documents = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        if (! in_array($ext, [...$images, ...$videos, ...$documents], true)) {
            throw ValidationException::withMessages([
                'file' => 'Upload a JPG, PNG, WEBP, GIF, MP4, WEBM, MOV, PDF, DOC, DOCX, XLS, or XLSX file.',
            ]);
        }
        $mime = (string) $file->getMimeType();
        if (str_contains($mime, 'php') || str_contains($mime, 'html') || str_contains($mime, 'javascript')) {
            throw ValidationException::withMessages(['file' => 'That file type cannot be uploaded.']);
        }
        $limit = in_array($ext, $videos, true) ? 80 * 1024 * 1024 : (in_array($ext, $documents, true) ? 20 * 1024 * 1024 : 8 * 1024 * 1024);
        if ($file->getSize() > $limit) {
            $label = in_array($ext, $videos, true) ? '80MB' : (in_array($ext, $documents, true) ? '20MB' : '8MB');
            throw ValidationException::withMessages(['file' => "That file is larger than the {$label} limit for its type."]);
        }
        $folderName = in_array($ext, $videos, true) ? 'Videos' : (in_array($ext, $documents, true) ? 'Documents' : 'Uploads');
        $folder = MediaFolder::query()->firstOrCreate(
            ['name' => $folderName],
            ['sort' => $folderName === 'Videos' ? 3 : ($folderName === 'Documents' ? 4 : 2)]
        );
        $path = $file->store('media/uploads', 'public');
        $size = in_array($ext, $images, true) ? (@getimagesize($file->getRealPath()) ?: [null, null]) : [null, null];

        return Media::query()->create([
            'media_folder_id' => $folder->id,
            'path' => $path,
            'disk' => 'public',
            'alt' => $request->input('alt') ?: null,
            'caption' => $request->input('caption') ?: $file->getClientOriginalName(),
            'mime' => $mime ?: $file->getClientMimeType(),
            'width' => $size[0],
            'height' => $size[1],
            'bytes' => $file->getSize(),
        ]);
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
