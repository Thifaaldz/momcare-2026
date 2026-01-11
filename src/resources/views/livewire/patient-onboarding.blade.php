<div class="card">

    <h2>🧬 Data Kehamilan</h2>
    <p class="subtitle">
        Lengkapi data ini untuk mendapatkan rekomendasi dan konsultasi yang akurat.
    </p>

    <form wire:submit.prevent="submit">

        <div class="form-grid">

            <div class="form-group">
                <label>Usia Ibu (tahun)</label>
                <input
                    type="number"
                    wire:model.defer="usia_ibu"
                    placeholder="Contoh: 27"
                >
                @error('usia_ibu')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Usia Kehamilan (minggu)</label>
                <input
                    type="number"
                    wire:model.defer="usia_kehamilan_minggu"
                    placeholder="Contoh: 24"
                >
                @error('usia_kehamilan_minggu')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Berat Badan (kg)</label>
                <input
                    type="number"
                    step="0.1"
                    wire:model.defer="berat_badan"
                    placeholder="Contoh: 58.5"
                >
                @error('berat_badan')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Tinggi Badan (cm)</label>
                <input
                    type="number"
                    step="0.1"
                    wire:model.defer="tinggi_badan"
                    placeholder="Contoh: 162"
                >
                @error('tinggi_badan')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <button type="submit" class="btn-primary">
            🚀 Simpan & Mulai Konsultasi
        </button>

    </form>

</div>
