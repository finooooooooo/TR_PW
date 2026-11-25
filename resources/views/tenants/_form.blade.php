<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="name">Nama lengkap <span class="text-danger">*</span></label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tenant->name ?? '') }}" maxlength="100" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="id_card_number">NIK / Nomor KTP <span class="text-danger">*</span></label>
            <input class="form-control @error('id_card_number') is-invalid @enderror" id="id_card_number" name="id_card_number" type="text" inputmode="numeric" pattern="[0-9]{16}" minlength="16" maxlength="16" value="{{ old('id_card_number', $tenant->id_card_number ?? '') }}" required>
            @error('id_card_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="phone">Nomor telepon <span class="text-danger">*</span></label>
            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="tel" value="{{ old('phone', $tenant->phone ?? '') }}" maxlength="20" required>
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="origin_address">Alamat asal</label>
            <input class="form-control @error('origin_address') is-invalid @enderror" id="origin_address" name="origin_address" value="{{ old('origin_address', $tenant->origin_address ?? '') }}" maxlength="255">
            @error('origin_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="emergency_contact">Kontak darurat</label>
            <input class="form-control @error('emergency_contact') is-invalid @enderror" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $tenant->emergency_contact ?? '') }}" maxlength="100" placeholder="Nama dan nomor telepon">
            @error('emergency_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 d-flex gap-2 pt-2">
            <button class="btn btn-primary" type="submit">{{ $method === 'POST' ? 'Simpan penghuni' : 'Simpan perubahan' }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('tenants.index') }}">Batal</a>
        </div>
    </div>
</form>
