@extends('layouts.app')

@section('title', 'Masa Sewa')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><p class="text-primary fw-semibold mb-1">DATA KOS</p><h1 class="h2 page-heading mb-1">Masa sewa</h1><p class="text-secondary mb-0">Kelola penempatan penghuni dan periode sewa kamar.</p></div>
        <a class="btn btn-primary" href="{{ route('leases.create') }}">+ Catat masa sewa</a>
    </div>
    <section class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Penghuni</th><th>Kamar</th><th>Periode</th><th>Sewa / bulan</th><th>Status</th><th class="text-end pe-4">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($leases as $lease)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $lease->tenant->name }}</td><td>{{ $lease->room->room_number }} <span class="text-secondary">· {{ $lease->room->type }}</span></td>
                            <td>{{ $lease->start_date->format('d/m/Y') }} – {{ $lease->end_date->format('d/m/Y') }}</td>
                            <td>Rp{{ number_format($lease->monthly_rent, 0, ',', '.') }}</td>
                            <td><span class="badge text-bg-{{ $lease->status === 'active' ? 'success' : 'secondary' }}">{{ $lease->status === 'active' ? 'Aktif' : 'Selesai' }}</span></td>
                            <td class="text-end pe-4 text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('leases.edit', $lease) }}">Ubah</a>
                                <form class="d-inline" method="POST" action="{{ route('leases.destroy', $lease) }}" onsubmit="return confirm('Hapus masa sewa ini? Riwayat yang memiliki pembayaran tidak dapat dihapus.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-secondary py-5" colspan="6">Belum ada data masa sewa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection