<?php

use App\Livewire\Client\OrderShow;
use App\Livewire\Partner\SpotForm;
use App\Livewire\Partner\SpotList;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

// Главная
Route::get('/', fn() => view('pages.home'))->name('home');

// Страница площадки
Route::get('/spots/{id}', fn($id) => view('pages.spot-show', compact('id')))->name('spots.show');

// Корзина (доступна без авторизации для просмотра)
Route::get('/cart', fn() => view('pages.cart'))->name('cart');

// Заказ (только для клиента)
Route::middleware(['auth', 'verified'])->group(function () {
    // ... существующие роуты ...
    Route::get('/client/orders/{id}', OrderShow::class)->name('client.orders.show');
});


// ── Аутентификация ──────────────────────────────────────
Route::get('/register', fn() => view('pages.register'))->name('register')->middleware('guest');
Route::get('/login', fn() => view('pages.login'))->name('login')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout')->middleware('auth'); // ← было 'livewire', исправлено

// ── Восстановление пароля ───────────────────────────────
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    Password::broker('users')->sendResetLink($request->only('email'));
    return back()->with('status', 'Письмо отправлено на ваш email');
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

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
})->middleware('guest')->name('password.update');

// ── Верификация email ───────────────────────────────────
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user = $request->user();
    return redirect(match($user->role) {
        'admin'   => route('admin.dashboard'),
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
    Route::get('/client/dashboard', fn() => view('pages.client.dashboard'))
        ->name('client.dashboard');
    Route::get('/client/orders', fn() => view('pages.client.orders'))
        ->name('client.orders');
    Route::get('/client/orders/{id}', fn($id) => view('pages.order-show', compact('id')))
        ->name('client.orders.show');

    Route::get('/partner/dashboard', function () {
        return view('partner.dashboard');
    })->name('partner.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/partner/spots', fn() => view('pages.partner.spots'))->name('partner.spots');
    Route::get('/partner/spots/create', SpotForm::class)->name('partner.spots.create');
    Route::get('/partner/spots/{spotId}/edit', SpotForm::class)->name('partner.spots.edit');
    Route::get('/partner/spots/{spotId}/edit', fn() => view('pages.partner.spots-form'))->name('partner.spots-form');

    Route::get('/partner/dashboard', fn() => view('pages.partner.dashboard'))
        ->name('partner.dashboard');

    Route::get('/partner/orders', fn() => view('pages.partner.orders'))
        ->name('partner.orders');
    Route::get('/partner/orders', fn() => view('pages.partner.orders'))
        ->name('partner.orders');

});
