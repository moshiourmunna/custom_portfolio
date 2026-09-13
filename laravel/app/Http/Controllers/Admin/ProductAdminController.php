<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Support\Mill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductAdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Products/Index', [
            'products' => Product::query()->with('category:id,name')->orderBy('sort')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Products/Form', [
            'product' => null,
            'categories' => ProductCategory::query()->orderBy('sort')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = Product::query()->create($this->validated($request));

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product created.');
    }

    public function edit(Product $product): Response
    {
        $product->load(['images', 'specs']);

        return Inertia::render('Products/Form', [
            'product' => $product,
            'categories' => ProductCategory::query()->orderBy('sort')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validated($request, $product->id));

        return back()->with('status', 'Product saved.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product removed.');
    }

    public function categories(): Response
    {
        return Inertia::render('Categories', [
            'categories' => ProductCategory::query()->withCount('products')->orderBy('sort')->get(),
        ]);
    }

    public function updateCategory(Request $request, ProductCategory $category): RedirectResponse
    {
        $category->update($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'text' => ['nullable', 'string'],
            'href' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
        ]));

        return back()->with('status', 'Category saved.');
    }

    private function validated(Request $request, ?int $ignore = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:products,slug,'.($ignore ?: 'NULL')],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'count' => ['nullable', 'string', 'max:80'],
            'weave' => ['nullable', 'string', 'max:80'],
            'finish' => ['nullable', 'string', 'max:80'],
            'construction' => ['nullable', 'string', 'max:120'],
            'gsm' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:32'],
            'image' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['filter_count'] = Mill::filterCount($data['count'] ?? null);

        return $data;
    }
}
