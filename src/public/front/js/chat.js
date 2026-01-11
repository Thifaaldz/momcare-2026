document.addEventListener('DOMContentLoaded', () => {
    const chat = document.getElementById('chatMessages');
    if (chat) chat.scrollTop = chat.scrollHeight;

    document.addEventListener('keydown', e => {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
            e.preventDefault();
            document.getElementById('messageInput')?.focus();
        }
    });

    if (window.Livewire) {
        Livewire.on('scrollToBottom', () => {
            chat.scrollTop = chat.scrollHeight;
        });
    }
});
