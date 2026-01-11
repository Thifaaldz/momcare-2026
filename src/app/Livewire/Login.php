<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function submit()
    {
        $this->validate();

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {
            request()->session()->regenerate();
            return redirect()->route('dashboard');
        }

        $this->addError('email', 'Email atau password salah');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
