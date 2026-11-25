<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" for="lease_id">Masa sewa <span class="text-danger">*</span></label>
            <select class="form-select @error('lease_id') is-invalid @enderror" id="lease_id" name="lease_id" required>
                <option value="">Pilih penghuni dan kamar</option>
                @foreach ($leases as $leaseOption)
                    <option value="{{ $leaseOption->id }}" @selected((string) old('lease_id', $payment->lease_id ?? '') === (string) $leaseOption->id)>
                        {{ $leaseOption->tenant->name }} · Kamar {{ $leaseOption->room->room_number }} · Rp{{ number_format($leaseOption->monthly_rent, 0, ',', '.') }}/bln{{ $leaseOption->status === 'active' ? '' : ' · Selesai' }}
                    </option>
                @endforeach
            </select>
            @error('lease_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label" for="payment_date">Tanggal pembayaran <span class="text-danger">*</span></label>
            <input class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" type="date" value="{{ old('payment_date', isset($payment) ? $payment->payment_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            @error('payment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label" for="period_month">Periode sewa <span class="text-danger">*</span></label>
            <input class="form-control @error('period_month') is-invalid @enderror" id="period_month" name="period_month" type="month" value="{{ old('period_month', $payment->period_month ?? now()->format('Y-m')) }}" required>
            @error('period_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label" for="amount">Jumlah dibayar (Rp) <span class="text-danger">*</span></label>
            <input class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" type="number" min="1" step="1000" value="{{ old('amount', $payment->amount ?? '') }}" required>
            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="method">Metode pencatatan <span class="text-danger">*</span></label>
            <select class="form-select @error('method') is-invalid @enderror" id="method" name="method" required>
                <option value="tunai" @selected(old('method', $payment->method ?? 'tunai') === 'tunai')>Tunai</option>
                <option value="lainnya" @selected(old('method', $payment->method ?? '') === 'lainnya')>Lainnya (dicatat manual)</option>
            </select>
            @error('method') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="status">Status pembayaran <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="paid" @selected(old('status', $payment->status ?? 'paid') === 'paid')>Lunas</option>
                <option value="pending" @selected(old('status', $payment->status ?? '') === 'pending')>Menunggu konfirmasi</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="notes">Catatan</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $payment->notes ?? '') }}</textarea>
            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 d-flex gap-2 pt-2">
            <button class="btn btn-primary" type="submit">{{ $method === 'POST' ? 'Simpan pembayaran' : 'Simpan perubahan' }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('payments.index') }}">Batal</a>
        </div>
    </div>
</form>
