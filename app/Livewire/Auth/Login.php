<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    protected array $rules = [
        'email'    => 'required|email',
        'password' => 'required',
    ];

    public function submit(): void
    {
        $this->validate();

        if (!Auth::attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $this->addError('email', 'Неверный email или пароль');
            return;
        }

        $user = Auth::user();

        // Не верифицировал email — на страницу верификации
        if (!$user->hasVerifiedEmail()) {
            $this->redirect(route('verification.notice'));
            return;
        }

        // Не принял оферту — покажем модалку (редирект в кабинет,
        // модалка появится сама через middleware)
        $this->redirect($this->getRedirectRoute($user));
    }

    private function getRedirectRoute(object $user): string
    {
        return match($user->role) {
            'admin'   => route('admin.dashboard'),
            'partner' => route('partner.dashboard'),
            default   => route('client.dashboard'),
        };
    }

    public function render()
    {
        return view('auth.login')
            ->layout('layouts.app');
    }
}
