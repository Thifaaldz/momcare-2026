<div class="card">

    <h2>✨ Register MomCare AI</h2>

    <form wire:submit.prevent="submit">

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input
                type="text"
                wire:model.defer="name"
                placeholder="Nama lengkap"
            >
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input
                type="email"
                wire:model.defer="email"
                placeholder="email@domain.com"
            >
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Password</label>
            <input
                type="password"
                wire:model.defer="password"
                placeholder="Minimal 8 karakter"
            >
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input
                type="password"
                wire:model.defer="password_confirmation"
                placeholder="Ulangi password"
            >
        </div>

        <button type="submit">
            Daftar
        </button>

    </form>

    <div class="auth-link">
        Sudah punya akun?
        <a href="/login">Login</a>
    </div>

</div>
