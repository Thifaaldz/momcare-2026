<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatSession;
use Illuminate\Support\Facades\Http;

class Chatbot extends Component
{
    public $message = '';
    public $messages = [];
    public $chatSessions = [];
    public $currentSession;
    public $isTyping = false;

    public function mount()
    {
        $user = auth()->user();

        // Ambil semua session user
        $this->chatSessions = ChatSession::where('user_id', $user->id)
            ->latest()
            ->get();

        // Ambil session terakhir atau buat baru
        $this->currentSession = $this->chatSessions->first()
            ?? ChatSession::create([
                'user_id'    => $user->id,
                'patient_id' => $user->patient->id,
                'title'      => 'Chat - ' . now()->format('d/m H:i'),
            ]);

        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->currentSession
            ->messages()
            ->orderBy('created_at')
            ->get()
            ->toArray();
    }

    public function sendMessage()
    {
        if (!$this->message) return;

        // 1️⃣ Simpan pesan user
        $this->currentSession->messages()->create([
            'sender'  => 'user',
            'message' => $this->message,
        ]);

        // 2️⃣ Ambil history terakhir (cukup 8)
        $history = $this->currentSession->messages()
            ->orderBy('created_at')
            ->take(8)
            ->get()
            ->map(fn ($m) => [
                'role'    => $m->sender === 'user' ? 'user' : 'assistant',
                'content' => $m->message,
            ])
            ->values();

        $this->isTyping = true;
        $this->message = '';
        $this->loadMessages();

        // 3️⃣ Kirim ke n8n
        try {
            $response = Http::timeout(30)->post(
                config('services.n8n.webhook'),
                [
                    'session_id' => $this->currentSession->id,
                    'patient'    => auth()->user()->patient,
                    'history'    => $history,
                ]
            );

            $reply = $response->json('reply')
                ?? 'Keluhan seperti ini cukup sering terjadi pada kehamilan, Bu. Namun saya ingin memastikan kondisinya aman.';

        } catch (\Exception $e) {
            $reply = 'Maaf ya, Bu, saya sedikit kesulitan merespon. Kita coba lagi sebentar.';
        }

        // 4️⃣ Simpan jawaban AI
        $this->currentSession->messages()->create([
            'sender'  => 'ai',
            'message' => $reply,
        ]);

        $this->isTyping = false;
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chatbot')
            ->layout('components.layouts.chat-layout');
    }
}
