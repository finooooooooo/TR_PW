@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="mb-4"><p class="text-primary fw-semibold mb-1">REKAPITULASI</p><h1 class="h2 page-heading mb-1">Laporan</h1><p class="text-secondary mb-0">Tinjau data penghuni dan catatan pembayaran, lalu unduh PDF.</p></div>
    <section class="card mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div><h2 class="h5 mb-1">Laporan penghuni</h2><p class="text-secondary mb-0">{{ $tenants->count() }} penghuni terdaftar</p></div>
            <a class="btn btn-outline-primary" href="{{ route('reports.tenants.pdf') }}">Unduh laporan penghuni PDF</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th class="ps-4">Nama</th><th>NIK / No. KTP</th><th>Telepon</th><th>Asal</th><th>Kamar aktif</th><th>Masa sewa</th></tr></thead>
                <tbody>
                    @forelse ($tenants as $tenant)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $tenant->name }}</td><td>{{ $tenant->id_card_number }}</td><td>{{ $tenant->phone }}</td><td>{{ $tenant->origin_address ?: '—' }}</td>
                            @if ($tenant->activeLease)
                                <td>{{ $tenant->activeLease->room->room_number }}</td><td>{{ $tenant->activeLease->start_date->format('d/m/Y') }} – {{ $tenant->activeLease->end_date->format('d/m/Y') }}</td>
                            @else
                                <td>—</td><td>—</td>
                            @endif
                        </tr>
                    @empty
                        <tr><td class="text-center text-secondary py-4" colspan="6">Belum ada data penghuni.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card">
        <div class="card-body border-bottom d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div><h2 class="h5 mb-1">Laporan pembayaran</h2><p class="text-secondary mb-0">Total lunas: <strong>Rp{{ number_format($totalPaid, 0, ',', '.') }}</strong></p></div>
            <div class="d-flex flex-wrap gap-2 align-items-end">
                <form class="d-flex gap-2 align-items-end" method="GET" action="{{ route('reports.index') }}">
                    <div><label class="form-label mb-1" for="period_month">Periode</label><input class="form-control" id="period_month" name="period_month" type="month" value="{{ $periodMonth }}"></div>
                    <button class="btn btn-outline-primary" type="submit">Terapkan</button>
                </form>
                <a class="btn btn-primary" href="{{ route('reports.payments.pdf', ['period_month' => $periodMonth]) }}">Unduh PDF</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th class="ps-4">Tanggal</th><th>Penghuni</th><th>Kamar</th><th>Periode</th><th>Nominal</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr><td class="ps-4">{{ $payment->payment_date->format('d/m/Y') }}</td><td>{{ $payment->lease?->tenant?->name ?? '—' }}</td><td>{{ $payment->lease?->room?->room_number ?? '—' }}</td><td>{{ $payment->period_month }}</td><td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td><td><span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ $payment->status === 'paid' ? 'Lunas' : 'Menunggu' }}</span></td></tr>
                    @empty
                        <tr><td class="text-center text-secondary py-4" colspan="6">Tidak ada catatan pembayaran pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection