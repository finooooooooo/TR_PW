@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><p class="text-primary fw-semibold mb-1">CATATAN KEUANGAN</p><h1 class="h2 page-heading mb-1">Pembayaran</h1><p class="text-secondary mb-0">Catat pembayaran secara manual; aplikasi tidak memproses transaksi online.</p></div>
        <a class="btn btn-primary" href="{{ route('payments.create') }}">+ Catat pembayaran</a>
    </div>
    <section class="card">
        <div class="card-body border-bottom">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-sm-5 col-md-3">
                    <label class="form-label" for="period_month">Periode</label>
                    <input class="form-control" id="period_month" name="period_month" type="month" value="{{ request('period_month') }}">
                </div>
                <div class="col-sm-5 col-md-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua status</option>
                        <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
                        <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                    </select>
                </div>
                <div class="col-auto"><button class="btn btn-outline-primary" type="submit">Terapkan</button></div>
                <div class="col-auto"><a class="btn btn-link" href="{{ route('payments.index') }}">Reset</a></div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Tanggal</th><th>Penghuni / Kamar</th><th>Periode</th><th>Nominal</th><th>Metode</th><th>Status</th><th class="text-end pe-4">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="ps-4">{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td><div class="fw-semibold">{{ $payment->lease?->tenant?->name ?? '—' }}</div><div class="small text-secondary">{{ $payment->lease?->room?->room_number ?? '—' }}</div></td>
                            <td>{{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $payment->period_month)->translatedFormat('F Y') }}</td>
                            <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->method === 'tunai' ? 'Tunai' : 'Lainnya (manual)' }}</td>
                            <td><span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ $payment->status === 'paid' ? 'Lunas' : 'Menunggu' }}</span></td>
                            <td class="text-end pe-4 text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('payments.edit', $payment) }}">Ubah</a>
                                <form class="d-inline" method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Hapus catatan pembayaran ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-secondary py-5" colspan="7">Tidak ada catatan pembayaran untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection