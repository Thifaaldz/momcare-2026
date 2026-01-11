<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function submit()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Assign role default = user
        $user->assignRole('user');

        // Auto login
        Auth::login($user);

        // Redirect ke onboarding
        return redirect()->route('onboarding');
    }

    public function render()
    {
        return view('livewire.register')
            ->layout('components.layouts.app', [
                'title' => 'Register | MomCare AI'
            ]);
    }
}
