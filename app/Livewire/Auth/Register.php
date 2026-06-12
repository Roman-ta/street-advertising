<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role     = ''; // 'client' или 'partner'

    // Правила валидации
    protected function rules(): array
    {
        return [
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:client,partner',
        ];
    }

    protected array $messages = [
        'name.required'     => 'Введите ваше имя',
        'email.required'    => 'Введите email',
        'email.unique'      => 'Этот email уже зарегистрирован',
        'password.required' => 'Введите пароль',
        'password.min'      => 'Пароль минимум 8 символов',
        'password.confirmed'=> 'Пароли не совпадают',
        'role.required'     => 'Выберите тип аккаунта',
    ];

    public function selectRole(string $role): void
    {
        $this->role = $role;
    }

    public function submit(): void
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => $this->role,
        ]);

        // Отправляем письмо верификации (через Queue)
        event(new Registered($user));

        Auth::login($user);

        // После входа — на страницу верификации
        $this->redirect(route('verification.notice'));
    }

    public function render()
    {
        return view('auth.register')
            ->layout('layouts.app');
    }
}
