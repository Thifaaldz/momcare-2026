<div class="chat-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="brand">
                <div class="brand-icon">👶</div>
                <span class="brand-text">BidanCare AI</span>
            </div>
        </div>

        <!-- New Chat Button -->
        <button wire:click="newChat" class="new-chat-btn">
            <svg viewBox="0 0 24 24">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            <span>Chat Baru</span>
        </button>

        <!-- Chat History List (WhatsApp-style) -->
        <div class="chat-history">
            @if(!empty($chatSessions))
                @foreach($chatSessions as $session)
                    <div wire:click="loadChat({{ $session['id'] }})"
                         class="history-item {{ $currentSession && $currentSession->id === $session['id'] ? 'active' : '' }}">
                        <div class="history-avatar">
                            {{ strtoupper(substr($session['title'] ?? 'C', 0, 1)) }}
                        </div>
                        <div class="history-content">
                            <div class="history-header">
                                <div class="history-title">{{ $session['title'] ?? 'Chat Baru' }}</div>
                                <div class="history-time">
                                    {{ isset($session['updated_at']) ? \Carbon\Carbon::parse($session['updated_at'])->format('H:i') : '' }}
                                </div>
                            </div>
                            <div class="history-preview">
                                Klik untuk memulai percakapan
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">💬</div>
                    <div class="empty-state-text">Belum ada percakapan</div>
                    <div class="empty-state-sub">Klik "Chat Baru" untuk memulai</div>
                </div>
            @endif
        </div>
    </aside>

    <!-- MAIN CHAT AREA -->
    <main class="main-chat">
        <!-- Chat Header -->
        <header class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-avatar">👶</div>
                <div class="chat-header-info">
                    <h2>Bidan AI Assistant</h2>
                    <div class="chat-header-status">
                        <span class="status-dot"></span>
                        <span>Online - Siap membantu</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Messages Area -->
        <div class="chat-messages" id="chatMessages">
            <!-- Welcome Message (when no messages) -->
            @if(empty($messages))
                <div class="welcome-container">
                    <div class="welcome-icon">👶</div>
                    <h1 class="welcome-title">BidanCare AI</h1>
                    <p class="welcome-subtitle">
                        Konsultasikan kesehatan ibu hamil dan persiapan persalinan dengan Bidan AI kami yang siap membantu 24 jam.
                    </p>
                    <div class="welcome-features">
                        <div class="welcome-feature">
                            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                            <span>Konsultasi Kesehatan</span>
                        </div>
                        <div class="welcome-feature">
                            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            <span>Persiapan Persalinan</span>
                        </div>
                        <div class="welcome-feature">
                            <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Mendukung 24/7</span>
                        </div>
                    </div>
                    <p style="margin-top: 24px; color: var(--text-secondary); font-size: 14px;">
                        Ceritakan keluhan atau pertanyaan Anda...
                    </p>
                </div>
            @endif

            <!-- Messages -->
            @if(!empty($messages))
                @foreach($messages as $msg)
                    <div class="message {{ $msg['sender'] === 'user' ? 'user' : 'ai' }}">
                        <div class="message-avatar">
                            {{ $msg['sender'] === 'user' ? '👤' : '👶' }}
                        </div>
                        <div class="message-bubble">
                            <div class="message-text">
                                {!! nl2br(e($msg['message'])) !!}
                            </div>
                            <div class="message-meta">
                                {{ isset($msg['created_at']) ? \Carbon\Carbon::parse($msg['created_at'])->format('H:i') : now()->format('H:i') }}
                                @if($msg['sender'] === 'user')
                                    <span class="message-status sent">
                                        <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Typing Indicator -->
            @if($isTyping)
                <div class="typing-indicator">
                    <div class="message-avatar">👶</div>
                    <div class="typing-bubble">
                        <div class="typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Input Area -->
        <div class="chat-input-container">
            <div class="chat-input-wrapper">
                <button class="emoji-btn" title="Emoji">
                    <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                </button>
                <input type="text"
                       wire:model="message"
                       wire:keydown.enter="sendMessage"
                       placeholder="Ketik pesan..."
                       id="messageInput"
                       autofocus>
                @if(trim($message) === '')
                    <button class="send-btn" disabled>
                        <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                @else
                    <button wire:click="sendMessage" class="send-btn" id="sendBtn">
                        <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                @endif
            </div>
        </div>

        <script>
            // Auto scroll to bottom
            document.addEventListener('DOMContentLoaded', function() {
                const chatMessages = document.getElementById('chatMessages');
                const messageInput = document.getElementById('messageInput');

                function scrollToBottom() {
                    if (chatMessages) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                }

                // Scroll on load
                scrollToBottom();

                // Listen for Livewire 3 events
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('messagesLoaded', function() {
                        scrollToBottom();
                    });

                    Livewire.on('scrollToBottom', function() {
                        scrollToBottom();
                    });
                }

                // Focus input on load
                if (messageInput) {
                    messageInput.focus();
                }

                // Keyboard shortcuts
                document.addEventListener('keydown', function(e) {
                    if (e.key === '/' && document.activeElement !== messageInput) {
                        e.preventDefault();
                        if (messageInput) messageInput.focus();
                    }
                });
            });
        </script>
    </main>
</div>

