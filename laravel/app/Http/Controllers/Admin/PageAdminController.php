<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\PageField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageAdminController extends Controller
{
    public function home(): Response
    {
        $page = Page::query()->with(['fields', 'blocks'])->where('slug', 'home')->firstOrFail();

        return Inertia::render('Home', ['page' => $this->payload($page)]);
    }

    public function updateHome(Request $request): RedirectResponse
    {
        $page = Page::query()->where('slug', 'home')->firstOrFail();
        $this->save($page, $request);

        return back()->with('status', 'Home saved.');
    }

    public function index(): Response
    {
        return Inertia::render('Pages/Index', [
            'pages' => Page::query()->where('slug', '!=', 'home')->orderBy('title')->get(['id', 'slug', 'title', 'status', 'updated_at']),
        ]);
    }

    public function edit(Page $page): Response
    {
        $page->load(['fields', 'blocks']);

        return Inertia::render('Pages/Edit', ['page' => $this->payload($page)]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->save($page, $request);

        return back()->with('status', 'Page saved.');
    }

    private function payload(Page $page): array
    {
        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $page->title,
            'eyebrow' => $page->eyebrow,
            'lead' => $page->lead,
            'body' => $page->body,
            'line' => $page->line,
            'image' => $page->image,
            'card_title' => $page->card_title,
            'card_lead' => $page->card_lead,
            'commitment_title' => $page->commitment_title,
            'status' => $page->status,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'fields' => $page->fields->map(fn ($field) => ['key' => $field->key, 'value' => $field->value])->values(),
            'blocks' => $page->blocks->map(fn ($block) => $block->only([
                'id', 'group', 'sort', 'title', 'text', 'value', 'suffix', 'meta', 'year', 'role', 'initials', 'note', 'href', 'link_label', 'image',
            ]))->values(),
        ];
    }

    private function save(Page $page, Request $request): void
    {
        $page->update($request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'lead' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'line' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'card_title' => ['nullable', 'string', 'max:255'],
            'card_lead' => ['nullable', 'string'],
            'commitment_title' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:32'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]));

        foreach ($request->input('fields', []) as $field) {
            if (empty($field['key'])) {
                continue;
            }
            PageField::query()->updateOrCreate(
                ['page_id' => $page->id, 'key' => $field['key']],
                ['value' => $field['value'] ?? '']
            );
        }

        $kept = [];
        foreach ($request->input('blocks', []) as $index => $block) {
            $payload = [
                'page_id' => $page->id,
                'group' => $block['group'] ?? 'items',
                'sort' => $block['sort'] ?? ($index + 1),
                'title' => $block['title'] ?? null,
                'text' => $block['text'] ?? null,
                'value' => $block['value'] ?? null,
                'suffix' => $block['suffix'] ?? null,
                'meta' => $block['meta'] ?? null,
                'year' => $block['year'] ?? null,
                'role' => $block['role'] ?? null,
                'initials' => $block['initials'] ?? null,
                'note' => $block['note'] ?? null,
                'href' => $block['href'] ?? null,
                'link_label' => $block['link_label'] ?? null,
                'image' => $block['image'] ?? null,
            ];
            $row = ! empty($block['id'])
                ? PageBlock::query()->where('page_id', $page->id)->whereKey($block['id'])->first()
                : null;
            if ($row) {
                $row->update($payload);
            } else {
                $row = PageBlock::query()->create($payload);
            }
            $kept[] = $row->id;
        }

        if ($request->has('blocks')) {
            $page->blocks()->when($kept !== [], fn ($query) => $query->whereNotIn('id', $kept))->delete();
        }
    }
}
