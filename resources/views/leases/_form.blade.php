<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="room_id">Kamar <span class="text-danger">*</span></label>
            <select class="form-select @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required>
                <option value="">Pilih kamar</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected((string) old('room_id', $lease->room_id ?? '') === (string) $room->id)>{{ $room->room_number }} · {{ $room->type }} · Rp{{ number_format($room->monthly_price, 0, ',', '.') }}/bln</option>
                @endforeach
            </select>
            @error('room_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="tenant_id">Penghuni <span class="text-danger">*</span></label>
            <select class="form-select @error('tenant_id') is-invalid @enderror" id="tenant_id" name="tenant_id" required>
                <option value="">Pilih penghuni</option>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" @selected((string) old('tenant_id', $lease->tenant_id ?? '') === (string) $tenant->id)>{{ $tenant->name }} · {{ $tenant->phone }}</option>
                @endforeach
            </select>
            @error('tenant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="start_date">Tanggal mulai <span class="text-danger">*</span></label>
            <input class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" type="date" value="{{ old('start_date', isset($lease) ? $lease->start_date->format('Y-m-d') : '') }}" required>
            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="end_date">Tanggal selesai <span class="text-danger">*</span></label>
            <input class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" type="date" value="{{ old('end_date', isset($lease) ? $lease->end_date->format('Y-m-d') : '') }}" required>
            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="monthly_rent">Harga sewa per bulan (Rp) <span class="text-danger">*</span></label>
            <input class="form-control @error('monthly_rent') is-invalid @enderror" id="monthly_rent" name="monthly_rent" type="number" min="0" step="1000" value="{{ old('monthly_rent', $lease->monthly_rent ?? '') }}" required>
            @error('monthly_rent') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        @if (isset($lease))
            <div class="col-md-6">
                <label class="form-label" for="status">Status masa sewa</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="active" @selected(old('status', $lease->status) === 'active')>Aktif</option>
                    <option value="ended" @selected(old('status', $lease->status) === 'ended')>Selesai</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        @endif
        <div class="col-12">
            <label class="form-label" for="notes">Catatan</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $lease->notes ?? '') }}</textarea>
            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 d-flex gap-2 pt-2">
            <button class="btn btn-primary" type="submit">{{ $method === 'POST' ? 'Simpan masa sewa' : 'Simpan perubahan' }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('leases.index') }}">Batal</a>
        </div>
    </div>
</form>
