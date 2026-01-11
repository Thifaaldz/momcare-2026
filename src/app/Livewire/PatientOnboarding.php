<?php

namespace App\Livewire;

use Livewire\Component;

class PatientOnboarding extends Component
{
    public $usia_ibu;
    public $usia_kehamilan_minggu;
    public $berat_badan;
    public $tinggi_badan;

    protected $rules = [
        'usia_ibu' => 'required|integer|min:10|max:60',
        'usia_kehamilan_minggu' => 'required|integer|min:1|max:42',
        'berat_badan' => 'required|numeric|min:30|max:200',
        'tinggi_badan' => 'required|numeric|min:120|max:200',
    ];

    public function mount()
    {
        if (auth()->user()->patient) {
            redirect()->route('chat');
        }
    }

    public function submit()
    {
        $this->validate();

        auth()->user()->patient()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'usia_ibu' => $this->usia_ibu,
                'usia_kehamilan_minggu' => $this->usia_kehamilan_minggu,
                'berat_badan' => $this->berat_badan,
                'tinggi_badan' => $this->tinggi_badan,
            ]
        );

        return redirect()->route('chat');
    }

    public function render()
    {
        return view('livewire.patient-onboarding');
    }
}
