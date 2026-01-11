<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Chatbot extends Component
{
    public $chatSessions = [];
    public $currentSession = null;
    public $messages = [];
    public $message = '';
    public $isTyping = false;

    protected $listeners = ['newChat' => 'createNewChat'];

    public function mount()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->loadChatSessions();
        
        // Load or create current session
        if (!empty($this->chatSessions)) {
            $this->currentSession = ChatSession::find($this->chatSessions[0]['id']);
            $this->loadMessages();
        } else {
            $this->createNewChat();
        }
    }

    public function loadChatSessions()
    {
        $user = auth()->user();
        $sessions = ChatSession::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();
        $this->chatSessions = $sessions->toArray();
    }

    public function loadMessages()
    {
        if (!$this->currentSession) {
            $this->messages = [];
            return;
        }

        $messages = $this->currentSession
            ->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        $this->messages = $messages->toArray();

        // Emit event for JavaScript (Livewire 3.x)
        $this->dispatch('messagesLoaded');
    }

    public function sendMessage()
    {
        if (trim($this->message) === '') {
            return;
        }

        $userMessageContent = trim($this->message);
        $this->isTyping = true;

        // Save user message
        $this->currentSession->messages()->create([
            'sender' => 'user',
            'message' => $userMessageContent,
        ]);

        $this->message = '';
        $this->loadMessages();
        $this->dispatch('scrollToBottom');

        // Send to n8n webhook with patient data
        try {
            $patient = $this->currentSession->patient;
            $history = collect($this->messages)->map(function($msg) {
                return [
                    'sender' => $msg['sender'],
                    'message' => $msg['message'],
                ];
            })->toArray();

            $response = Http::timeout(30)->post(env('N8N_CHATBOT_WEBHOOK', 'http://localhost:5678/webhook/chatbot'), [
                'message' => $userMessageContent,
                'patient' => [
                    'id' => $patient->id ?? null,
                    'name' => $patient->name ?? 'User',
                    'age' => $patient->age ?? null,
                    'pregnancy_week' => $patient->pregnancy_week ?? null,
                ],
                'history' => $history,
                'session_id' => $this->currentSession->id,
            ]);

            $aiReply = $response->successful() ? ($response->json('reply') ?? $response->json('output') ?? 'Maaf, terjadi kesalahan. Silakan coba lagi.') : 'Maaf, layanan AI sedang tidak tersedia. Silakan coba beberapa saat lagi.';
        } catch (\Exception $e) {
            $aiReply = 'Maaf, terjadi kesalahan koneksi. Silakan periksa koneksi internet Anda dan coba lagi.';
            Log::error('Chatbot Error: ' . $e->getMessage());
        }

        $this->currentSession->messages()->create([
            'sender' => 'ai',
            'message' => $aiReply,
        ]);

        $this->isTyping = false;
        $this->loadMessages();
        $this->dispatch('scrollToBottom');
    }

    public function createNewChat()
    {
        $user = auth()->user();
        
        $this->currentSession = ChatSession::create([
            'user_id' => $user->id,
            'patient_id' => $user->patient->id ?? null,
            'title' => 'Chat Baru - ' . now()->format('d/m H:i'),
            'status' => 'active',
        ]);

        $this->loadChatSessions();
        $this->messages = [];
        $this->dispatch('scrollToBottom');
    }

    public function loadChat($sessionId)
    {
        $this->currentSession = ChatSession::find($sessionId);
        if ($this->currentSession) {
            $this->loadMessages();
            $this->dispatch('scrollToBottom');
        }
    }

    public function deleteChat($sessionId)
    {
        ChatSession::find($sessionId)?->delete();
        $this->loadChatSessions();
        
        if ($this->currentSession && $this->currentSession->id === $sessionId) {
            $this->createNewChat();
        }
    }

    public function getAvatar($sender)
    {
        return $sender === 'user' ? '👤' : '◈';
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}

