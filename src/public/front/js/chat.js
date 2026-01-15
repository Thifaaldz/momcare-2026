/**
 * BidanCare AI - Chat JS
 * Selaras dengan Livewire & UI Chat
 */

document.addEventListener('DOMContentLoaded', () => {
    const chat = document.getElementById('chatMessages');
    const input = document.getElementById('messageInput');

    if (!chat) return;

    /**
     * Auto scroll ke bawah (aman untuk Livewire & animasi)
     */
    const scrollToBottom = () => {
        requestAnimationFrame(() => {
            chat.scrollTop = chat.scrollHeight;
        });
    };

    // Initial scroll
    scrollToBottom();

    /**
     * Shortcut "/" fokus ke input (desktop UX)
     */
    document.addEventListener('keydown', (e) => {
        if (
            e.key === '/' &&
            document.activeElement !== input &&
            document.activeElement?.tagName !== 'TEXTAREA'
        ) {
            e.preventDefault();
            input?.focus();
        }
    });

    /**
     * Livewire lifecycle integration
     */
    if (window.Livewire) {

        /**
         * Dipanggil SETELAH Livewire selesai render DOM
         * Aman untuk scroll & typing effect
         */
        Livewire.hook('message.processed', () => {
            scrollToBottom();
        });

        /**
         * Event manual jika dibutuhkan (future-proof)
         */
        Livewire.on('scrollToBottom', () => {
            scrollToBottom();
        });
    }
});
