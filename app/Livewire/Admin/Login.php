<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Credenciales incorrectas.');

            return;
        }

        session()->regenerate();

        return $this->redirect(route('admin.dashboard'), navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.login')
            ->layout('layouts.auth');
    }
}
