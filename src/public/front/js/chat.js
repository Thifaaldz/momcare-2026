/**
 * BidanCare AI Chat - Cyberpunk Web3 JavaScript
 * Enhanced chat functionality with animations and shortcuts
 */

document.addEventListener('DOMContentLoaded', () => {
    initChat();
});

function initChat() {
    // Auto-scroll to bottom on new messages
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        scrollToBottom(chatMessages);
    }

    // Keyboard shortcuts
    initKeyboardShortcuts();

    // Message input enhancements
    initMessageInput();

    // Parallax effect for cyber background
    initParallax();
}

/**
 * Scroll chat container to bottom
 */
function scrollToBottom(element = null) {
    const chat = element || document.getElementById('chatMessages');
    if (chat) {
        chat.scrollTo({
            top: chat.scrollHeight,
            behavior: 'smooth'
        });
    }
}

/**
 * Initialize keyboard shortcuts
 */
function initKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + Enter to send message
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            const sendBtn = document.getElementById('sendBtn');
            if (sendBtn) {
                sendBtn.click();
            }
        }

        // '/' to focus input
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            if (input) {
                input.focus();
            }
        }

        // Escape to blur input
        if (e.key === 'Escape') {
            const input = document.getElementById('messageInput');
            if (input) {
                input.blur();
            }
        }

        // Ctrl/Cmd + N for new chat
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('newChat');
            }
        }

        // Arrow up to edit last message (if empty input)
        if (e.key === 'ArrowUp' && document.activeElement.tagName === 'INPUT') {
            const input = document.getElementById('messageInput');
            if (input && input.value === '') {
                // Could implement message editing here
            }
        }
    });
}

/**
 * Initialize message input enhancements
 */
function initMessageInput() {
    const input = document.getElementById('messageInput');
    if (!input) return;

    // Auto-resize textarea if we had one
    // For now just focus management

    // Character count
    input.addEventListener('input', () => {
        const length = input.value.length;
        // Could add character counter UI here
    });

    // Prevent line breaks on Enter (handled by Livewire)
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey && !e.ctrlKey && !e.metaKey) {
            // Enter sends message (default behavior)
        }
    });
}

/**
 * Parallax effect for cyber background elements
 */
function initParallax() {
    document.addEventListener('mousemove', (e) => {
        const bg = document.querySelector('.cyber-bg');
        if (!bg) return;

        const x = (e.clientX / window.innerWidth - 0.5) * 10;
        const y = (e.clientY / window.innerHeight - 0.5) * 10;

        bg.style.transform = `translate(${x}px, ${y}px)`;
    });
}

/**
 * Show typing indicator
 */
function showTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    if (indicator) {
        indicator.style.display = 'flex';
        scrollToBottom();
    }
}

/**
 * Hide typing indicator
 */
function hideTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    if (indicator) {
        indicator.style.display = 'none';
    }
}

/**
 * Add message to chat (for real-time updates)
 */
function addMessage(sender, text, time = null) {
    const chatMessages = document.getElementById('chatMessages');
    if (!chatMessages) return;

    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}`;
    messageDiv.innerHTML = `
        <div class="message-avatar">
            ${sender === 'user' ? '👤' : '◈'}
        </div>
        <div class="message-content">
            <div class="message-text">${text}</div>
            <div class="message-time">${time || new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
        </div>
    `;

    chatMessages.appendChild(messageDiv);
    scrollToBottom(chatMessages);
}

/**
 * Animate message on load
 */
function animateMessage(element) {
    element.style.animation = 'none';
    element.offsetHeight; // Trigger reflow
    element.style.animation = 'messageSlide 0.3s ease-out';
}

/**
 * Copy message to clipboard
 */
function copyMessage(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Could show toast notification
        console.log('Message copied to clipboard');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

/**
 * Format timestamp for messages
 */
function formatTime(date) {
    return new Date(date).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Glitch text effect
 */
function glitchText(element) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    const original = element.textContent;
    let iterations = 0;

    const interval = setInterval(() => {
        element.textContent = original
            .split('')
            .map((char, index) => {
                if (index < iterations) {
                    return original[index];
                }
                return chars[Math.floor(Math.random() * chars.length)];
            })
            .join('');

        if (iterations >= original.length) {
            clearInterval(interval);
        }

        iterations += 1 / 3;
    }, 30);
}

/**
 * Matrix rain effect (optional - can be toggled)
 */
function toggleMatrixEffect() {
    const canvas = document.createElement('canvas');
    canvas.id = 'matrix-canvas';
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100%';
    canvas.style.height = '100%';
    canvas.style.zIndex = '-2';
    canvas.style.opacity = '0.1';

    document.body.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz@#$%^&*';
    const fontSize = 14;
    const columns = canvas.width / fontSize;
    const drops = [];

    for (let i = 0; i < columns; i++) {
        drops[i] = 1;
    }

    function draw() {
        ctx.fillStyle = 'rgba(10, 10, 15, 0.05)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = '#0ff';
        ctx.font = `${fontSize}px monospace`;

        for (let i = 0; i < drops.length; i++) {
            const text = chars[Math.floor(Math.random() * chars.length)];
            ctx.fillText(text, i * fontSize, drops[i] * fontSize);

            if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }

            drops[i]++;
        }
    }

    setInterval(draw, 50);
}

/**
 * Sound effects (optional)
 */
const sounds = {
    send: null,  // Can add Audio objects here
    receive: null
};

/**
 * Play send sound
 */
function playSendSound() {
    // sounds.send?.play();
}

/**
 * Play receive sound
 */
function playReceiveSound() {
    // sounds.receive?.play();
}

// Export functions for Livewire
window.ChatUtils = {
    scrollToBottom,
    showTypingIndicator,
    hideTypingIndicator,
    addMessage,
    copyMessage,
    formatTime,
    glitchText
};

