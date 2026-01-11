<div class="chat-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <div class="brand-icon">👶</div>
                <span class="brand-text">BidanCare AI</span>
            </div>
        </div>

        <button wire:click="newChat" class="new-chat-btn">
            <span>＋ Chat Baru</span>
        </button>

        <div class="chat-history">
            @foreach($chatSessions as $session)
                <div
                    wire:click="loadChat({{ $session['id'] }})"
                    class="history-item {{ $currentSession && $currentSession->id === $session['id'] ? 'active' : '' }}"
                >
                    <div class="history-avatar">
                        {{ strtoupper(substr($session['title'] ?? 'C', 0, 1)) }}
                    </div>
                    <div class="history-content">
                        <div class="history-title">
                            {{ $session['title'] ?? 'Chat Baru' }}
                        </div>
                        <div class="history-preview">
                            Klik untuk membuka
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </aside>

    <!-- MAIN CHAT -->
    <main class="main-chat">

        <header class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-avatar">👶</div>
                <div>
                    <h2>Bidan AI Assistant</h2>
                    <span class="online">Online</span>
                </div>
            </div>
        </header>

        <div class="chat-messages" id="chatMessages">
            @forelse($messages as $msg)
                <div class="message {{ $msg['sender'] === 'user' ? 'user' : 'ai' }}">
                    <div class="message-avatar">
                        {{ $msg['sender'] === 'user' ? '👤' : '👶' }}
                    </div>
                    <div class="message-bubble">
                        {!! nl2br(e($msg['message'])) !!}
                        <div class="message-time">
                            {{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="welcome">
                    <h1>BidanCare AI</h1>
                    <p>Konsultasi kehamilan & persalinan 24/7</p>
                </div>
            @endforelse

            @if($isTyping)
                <div class="message ai">
                    <div class="message-avatar">👶</div>
                    <div class="typing">Mengetik...</div>
                </div>
            @endif
        </div>

        <div class="chat-input-container">
            <div class="chat-input-wrapper">
                <input
                    type="text"
                    wire:model.defer="message"
                    wire:keydown.enter="sendMessage"
                    id="messageInput"
                    placeholder="Ketik pesan..."
                >
                <button
                    wire:click="sendMessage"
                    class="send-btn"
                    id="sendBtn"
                >➤</button>
            </div>
        </div>

    </main>
</div>
