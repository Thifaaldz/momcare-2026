<div class="card">

    <h2>🔐 Login MomCare AI</h2>

    <form wire:submit.prevent="submit">

        <div class="form-group">
            <label>Email</label>
            <input
                type="email"
                wire:model.defer="email"
                placeholder="you@email.com"
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
                placeholder="••••••••"
            >
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">
            Masuk
        </button>

    </form>

    <div class="auth-link">
        Belum punya akun?
        <a href="/register">Daftar sekarang</a>
    </div>

</div>
