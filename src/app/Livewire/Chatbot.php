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

    // ==========================
    // KIRIM PESAN (CHAT UTAMA)
    // ==========================
    public function sendMessage()
    {
        if (!$this->message) return;

        // 1️⃣ Simpan pesan user
        $this->currentSession->messages()->create([
            'sender'  => 'user',
            'message' => $this->message,
        ]);

        // 2️⃣ Ambil history terakhir (maks 8)
        $history = $this->currentSession->messages()
            ->orderBy('created_at')
            ->take(8)
            ->get()
            ->map(fn ($m) => [
                'role'    => $m->sender === 'user' ? 'user' : 'assistant',
                'content' => $m->message,
            ])
            ->values();

        // 3️⃣ Reset input & tampilkan "AI mengetik"
        $this->message = '';
        $this->isTyping = true;
        $this->loadMessages();

        // 4️⃣ Kirim ke n8n
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
                ?? 'Baik Bu, saya jelaskan secara umum terlebih dahulu ya.';

        } catch (\Exception $e) {
            $reply = 'Maaf Bu, saat ini sistem sedang sibuk. Silakan coba lagi.';
        }

        // 5️⃣ Simpan balasan AI (FULL TEXT)
        $this->currentSession->messages()->create([
            'sender'  => 'ai',
            'message' => $reply,
        ]);

        // 6️⃣ Matikan typing indicator
        $this->isTyping = false;

        // 7️⃣ Kirim teks ke JavaScript untuk efek mengetik
        $this->dispatch('ai-typing', text: $reply);

        $this->loadMessages();
    }

    // ==========================
    // CHAT BARU
    // ==========================
    public function newChat()
    {
        $user = auth()->user();

        $session = ChatSession::create([
            'user_id'    => $user->id,
            'patient_id' => $user->patient->id,
            'title'      => 'Chat - ' . now()->format('d/m H:i'),
        ]);

        $this->currentSession = $session;

        $this->chatSessions = ChatSession::where('user_id', $user->id)
            ->latest()
            ->get();

        $this->messages = [];
    }

    // ==========================
    // LOAD CHAT LAMA
    // ==========================
    public function loadChat($id)
    {
        $session = ChatSession::find($id);
        if (!$session) return;

        $this->currentSession = $session;
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chatbot')
            ->layout('components.layouts.chat-layout');
    }
}
