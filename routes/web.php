<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

// ── Публичные ───────────────────────────────────────────
Route::get('/', fn() => view('pages.home'))->name('home');
Route::get('/spots/{id}', fn($id) => view('pages.spot-show', compact('id')))->name('spots.show');
Route::get('/cart', fn() => view('pages.cart'))->name('cart');

// ── Аутентификация ──────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', fn() => view('pages.register'))->name('register');
    Route::get('/login', fn() => view('pages.login'))->name('login');

    Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);
        Password::broker('users')->sendResetLink($request->only('email'));
        return back()->with('status', 'Письмо отправлено на ваш email');
    })->name('password.email');

    Route::get('/reset-password/{token}', fn(string $token) => view('auth.reset-password', ['token' => $token]))->name('password.reset');
    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Пароль успешно изменён')
            : back()->withErrors(['email' => 'Ссылка недействительна или устарела']);
    })->name('password.update');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout')->middleware('auth');

// ── Верификация email ───────────────────────────────────
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user = $request->user();
    return redirect(match($user->role) {
        'admin'   => '/admin',
        'partner' => route('partner.dashboard'),
        default   => route('client.dashboard'),
    });
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ── Кабинеты ────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Клиент
    Route::get('/client/dashboard', fn() => view('pages.client.dashboard'))->name('client.dashboard');
    Route::get('/client/orders', fn() => view('pages.client.orders'))->name('client.orders');
    Route::get('/client/orders/{id}', fn($id) => view('pages.order-show', compact('id')))->name('client.orders.show');

    // Партнёр
    Route::get('/partner/dashboard', fn() => view('pages.partner.dashboard'))->name('partner.dashboard');
    Route::get('/partner/spots', fn() => view('pages.partner.spots'))->name('partner.spots');
    Route::get('/partner/spots/create', fn() => view('pages.partner.spot-create'))->name('partner.spots.create');
    Route::get('/partner/spots/{spotId}/edit', fn($spotId) => view('pages.partner.spot-edit', compact('spotId')))->name('partner.spots.edit');
    Route::get('/partner/orders', fn() => view('pages.partner.orders'))->name('partner.orders');
    Route::get('/partner/orders/{id}', fn($id) => view('pages.partner.order-show', compact('id')))->name('partner.orders.show');
    Route::get('/partner/spots/{spotId}/availability', fn($spotId) => view('pages.partner.spot-availability', compact('spotId')))
        ->name('partner.spots.availability');
    // Админ → редирект на Filament
    Route::get('/admin/dashboard', fn() => redirect('/admin'))->name('admin.dashboard');
});


Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['ru', 'ro', 'en'])) {
        session(['locale' => $locale]);

        if (auth()->check()) {
            auth()->user()->update(['lang' => $locale]);
        }
    }
    return back();
})->name('lang.switch');
