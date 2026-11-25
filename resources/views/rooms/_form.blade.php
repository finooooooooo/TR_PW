<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="room_number">Nomor kamar <span class="text-danger">*</span></label>
            <input class="form-control @error('room_number') is-invalid @enderror" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number ?? '') }}" maxlength="20" required>
            @error('room_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="type">Tipe kamar <span class="text-danger">*</span></label>
            <input class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', $room->type ?? '') }}" maxlength="50" placeholder="Contoh: Standar, AC + KM Dalam" required>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="monthly_price">Harga sewa per bulan (Rp) <span class="text-danger">*</span></label>
            <input class="form-control @error('monthly_price') is-invalid @enderror" id="monthly_price" name="monthly_price" type="number" min="0" step="1000" value="{{ old('monthly_price', $room->monthly_price ?? '') }}" required>
            @error('monthly_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="facilities">Fasilitas</label>
            <textarea class="form-control @error('facilities') is-invalid @enderror" id="facilities" name="facilities" rows="3" placeholder="Contoh: Kasur, lemari, WiFi">{{ old('facilities', $room->facilities ?? '') }}</textarea>
            @error('facilities') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        @if (isset($room))
            <div class="col-12"><div class="alert alert-info mb-0">Status kamar dikelola otomatis berdasarkan masa sewa aktif. Saat ini: <strong>{{ $room->status === 'available' ? 'Tersedia' : 'Terisi' }}</strong>.</div></div>
        @endif
        <div class="col-12 d-flex gap-2 pt-2">
            <button class="btn btn-primary" type="submit">{{ $method === 'POST' ? 'Simpan kamar' : 'Simpan perubahan' }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('rooms.index') }}">Batal</a>
        </div>
    </div>
</form>
