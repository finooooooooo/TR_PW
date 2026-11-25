@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="text-primary fw-semibold mb-1">RINGKASAN OPERASIONAL</p>
            <h1 class="h2 page-heading mb-1">Dashboard</h1>
            <p class="text-secondary mb-0">Data kamar dan pembayaran untuk {{ now()->translatedFormat('F Y') }}.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('payments.create') }}">+ Catat pembayaran</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="card h-100"><div class="card-body"><div class="text-secondary small">Total kamar</div><div class="stat-value">{{ $totalRooms }}</div><div class="small text-secondary">{{ $availableRooms }} tersedia · {{ $occupiedRooms }} terisi</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card h-100"><div class="card-body"><div class="text-secondary small">Penghuni terdaftar</div><div class="stat-value">{{ $totalTenants }}</div><div class="small text-secondary">{{ $activeLeases }} masa sewa aktif</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card h-100"><div class="card-body"><div class="text-secondary small">Pembayaran lunas bulan ini</div><div class="stat-value fs-4">Rp{{ number_format($monthlyIncome, 0, ',', '.') }}</div><div class="small text-secondary">{{ now()->translatedFormat('F Y') }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card h-100"><div class="card-body"><div class="text-secondary small">Menunggu konfirmasi</div><div class="stat-value">{{ $pendingPayments }}</div><div class="small text-secondary">catatan pembayaran</div></div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <section class="card h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Pembayaran terbaru</h2>
                    <a class="small text-decoration-none" href="{{ route('payments.index') }}">Lihat semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th class="ps-4">Penghuni / Kamar</th><th>Periode</th><th>Nominal</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse ($recentPayments as $payment)
                                <tr>
                                    <td class="ps-4"><div class="fw-semibold">{{ $payment->lease?->tenant?->name ?? '-' }}</div><div class="small text-secondary">{{ $payment->lease?->room?->room_number ?? '-' }}</div></td>
                                    <td>{{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $payment->period_month)->translatedFormat('M Y') }}</td>
                                    <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td><span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ $payment->status === 'paid' ? 'Lunas' : 'Menunggu' }}</span></td>
                                </tr>
                            @empty
                                <tr><td class="text-center text-secondary py-4" colspan="4">Belum ada catatan pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-lg-5">
            <section class="card h-100">
                <div class="card-header bg-white border-0 pt-4 px-4"><h2 class="h5 mb-0">Masa sewa berakhir 30 hari ke depan</h2></div>
                <div class="list-group list-group-flush">
                    @forelse ($expiringLeases as $lease)
                        <div class="list-group-item px-4 py-3 d-flex justify-content-between gap-3">
                            <div><div class="fw-semibold">{{ $lease->tenant->name }}</div><div class="small text-secondary">Kamar {{ $lease->room->room_number }}</div></div>
                            <div class="text-end"><div class="small text-secondary">Berakhir</div><div class="fw-semibold">{{ $lease->end_date->format('d/m/Y') }}</div></div>
                        </div>
                    @empty
                        <div class="list-group-item px-4 py-4 text-secondary">Tidak ada masa sewa yang segera berakhir.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
