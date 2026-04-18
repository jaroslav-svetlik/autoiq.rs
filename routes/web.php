<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Pages\Account\DashboardPage as AccountDashboardPage;
use App\Livewire\Pages\Admin\DashboardPage as AdminDashboardPage;
use App\Livewire\Pages\Auth\ForgotPasswordPage;
use App\Livewire\Pages\Auth\LoginPage;
use App\Livewire\Pages\Auth\LogoutPage;
use App\Livewire\Pages\Auth\RegisterPage;
use App\Livewire\Pages\Auth\ResetPasswordPage;
use App\Livewire\Pages\Auth\VerifyEmailPage;
use App\Livewire\Pages\Blog\IndexPage as BlogIndexPage;
use App\Livewire\Pages\Blog\ShowPage as BlogShowPage;
use App\Livewire\Pages\Dealers\ShowPage as DealerShowPage;
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\Listings\FormPage as ListingFormPage;
use App\Livewire\Pages\Listings\IndexPage as ListingIndexPage;
use App\Livewire\Pages\Listings\ShowPage as ListingShowPage;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/blog', BlogIndexPage::class)->name('blog.index');
Route::get('/blog/{blogPost}', BlogShowPage::class)->name('blog.show');
Route::get('/oglasi', ListingIndexPage::class)->name('listings.index');
Route::get('/auto/{listing}', ListingShowPage::class)->name('listings.show');
Route::get('/dileri/{dealerProfile}', DealerShowPage::class)->name('dealers.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::middleware('guest')->group(function () {
    Route::get('/nalog/prijava', LoginPage::class)->name('login');
    Route::get('/nalog/registracija', RegisterPage::class)->name('register');
    Route::get('/nalog/{provider}/preusmeri', [OAuthController::class, 'redirect'])
        ->whereIn('provider', ['google', 'facebook'])
        ->name('oauth.redirect');
    Route::get('/nalog/{provider}/povratak', [OAuthController::class, 'callback'])
        ->whereIn('provider', ['google', 'facebook'])
        ->name('oauth.callback');
    Route::get('/nalog/zaboravljena-lozinka', ForgotPasswordPage::class)->name('password.request');
    Route::get('/nalog/reset-lozinke/{token}', ResetPasswordPage::class)->name('password.reset');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/nalog/verifikacija', VerifyEmailPage::class)->name('verification.notice');
    Route::get('/nalog/odjava', LogoutPage::class)->name('logout');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('account.dashboard');
    })->middleware('signed')->name('verification.verify');

    Route::middleware('verified')->group(function () {
        Route::get('/oglasi/nov', ListingFormPage::class)->name('listings.create');
        Route::get('/oglasi/{listing}/izmena', ListingFormPage::class)->name('listings.edit');
        Route::get('/profil', AccountDashboardPage::class)->name('account.dashboard');
    });
});

Route::middleware(['auth', 'active', 'verified', 'permission:view admin dashboard'])->group(function () {
    Route::get('/admin', AdminDashboardPage::class)->name('admin.dashboard');
});
