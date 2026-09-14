<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\PageAdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\SettingAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/products', [SiteController::class, 'products'])->name('products');
Route::get('/products/yarn', [SiteController::class, 'yarn'])->name('products.yarn');
Route::get('/products/woven-fabric', [SiteController::class, 'woven'])->name('products.woven');
Route::get('/products/finished-fabric', [SiteController::class, 'finished'])->name('products.finished');
Route::get('/products/{slug}', [SiteController::class, 'product'])->name('products.show');
Route::get('/process', [SiteController::class, 'process'])->name('process');
Route::get('/facilities', [SiteController::class, 'facilities'])->name('facilities');
Route::get('/quality', [SiteController::class, 'quality'])->name('quality');
Route::get('/sustainability', [SiteController::class, 'sustainability'])->name('sustainability');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/careers', [CareerController::class, 'index'])->name('careers');
Route::get('/careers/{slug}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{slug}', [CareerController::class, 'apply'])->middleware('throttle:forms')->name('careers.apply');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [InquiryController::class, 'store'])->middleware('throttle:forms')->name('quote.store');
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:forms')->name('newsletter.store');
Route::get('/privacy', [SiteController::class, 'privacy'])->name('privacy');
Route::get('/terms', [SiteController::class, 'terms'])->name('terms');
Route::get('/quote-success', [SiteController::class, 'quoteSuccess'])->name('quote.success');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'store'])->middleware('throttle:forms');
    Route::get('/admin/forgot-password', [LoginController::class, 'forgot'])->name('password.request');
    Route::post('/admin/forgot-password', [LoginController::class, 'email'])->name('password.email');
    Route::get('/admin/reset-password/{token}', [LoginController::class, 'reset'])->name('password.reset');
    Route::post('/admin/reset-password', [LoginController::class, 'update'])->name('password.update');
});

Route::post('/admin/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:super-admin|editor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [PageAdminController::class, 'home'])->name('home');
    Route::put('/home', [PageAdminController::class, 'updateHome'])->name('home.update');
    Route::get('/pages', [PageAdminController::class, 'index'])->name('pages');
    Route::get('/pages/{page}', [PageAdminController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageAdminController::class, 'update'])->name('pages.update');

    Route::get('/products', [ProductAdminController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductAdminController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductAdminController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductAdminController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductAdminController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductAdminController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories', [ProductAdminController::class, 'categories'])->name('categories');
    Route::put('/categories/{category}', [ProductAdminController::class, 'updateCategory'])->name('categories.update');

    Route::get('/gallery', [LibraryController::class, 'gallery'])->name('gallery');
    Route::post('/gallery', [LibraryController::class, 'storeGallery'])->name('gallery.store');
    Route::delete('/gallery/{item}', [LibraryController::class, 'destroyGallery'])->name('gallery.destroy');

    Route::get('/news', [LibraryController::class, 'news'])->name('news');
    Route::get('/news/create', [LibraryController::class, 'createPost'])->name('news.create');
    Route::post('/news', [LibraryController::class, 'storePost'])->name('news.store');
    Route::get('/news/{post}/edit', [LibraryController::class, 'editPost'])->name('news.edit');
    Route::put('/news/{post}', [LibraryController::class, 'updatePost'])->name('news.update');
    Route::delete('/news/{post}', [LibraryController::class, 'destroyPost'])->name('news.destroy');

    Route::get('/careers', [LibraryController::class, 'careers'])->name('careers');
    Route::get('/careers/create', [LibraryController::class, 'createJob'])->name('careers.create');
    Route::post('/careers', [LibraryController::class, 'storeJob'])->name('careers.store');
    Route::get('/careers/{job}/edit', [LibraryController::class, 'editJob'])->name('careers.edit');
    Route::put('/careers/{job}', [LibraryController::class, 'updateJob'])->name('careers.update');
    Route::delete('/careers/{job}', [LibraryController::class, 'destroyJob'])->name('careers.destroy');

    Route::get('/inquiries', [LibraryController::class, 'inquiries'])->name('inquiries');
    Route::get('/inquiries/create', [LibraryController::class, 'createInquiry'])->name('inquiries.create');
    Route::post('/inquiries', [LibraryController::class, 'storeInquiry'])->name('inquiries.store');
    Route::put('/inquiries/{inquiry}', [LibraryController::class, 'updateInquiry'])->name('inquiries.update');
    Route::get('/inquiries/{inquiry}/attachment', [LibraryController::class, 'downloadAttachment'])->name('inquiries.attachment');
    Route::delete('/inquiries/{inquiry}', [LibraryController::class, 'destroyInquiry'])->name('inquiries.destroy');

    Route::get('/media', [LibraryController::class, 'media'])->name('media');
    Route::post('/uploads', [LibraryController::class, 'upload'])->name('uploads.store');
    Route::post('/media', [LibraryController::class, 'storeMedia'])->name('media.store');
    Route::delete('/media/{medium}', [LibraryController::class, 'destroyMedia'])->name('media.destroy');
});

Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', [SettingAdminController::class, 'edit'])->name('settings');
    Route::put('/settings', [SettingAdminController::class, 'update'])->name('settings.update');
});
