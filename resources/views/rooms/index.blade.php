@extends('layouts.app')

@section('title', 'Kamar')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><p class="text-primary fw-semibold mb-1">DATA KOS</p><h1 class="h2 page-heading mb-1">Kamar</h1><p class="text-secondary mb-0">Status terisi mengikuti masa sewa aktif.</p></div>
        <a class="btn btn-primary" href="{{ route('rooms.create') }}">+ Tambah kamar</a>
    </div>
    <section class="card">
        <div class="card-body border-bottom">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-sm-5 col-md-3">
                    <label class="form-label" for="status">Filter status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua status</option>
                        <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                        <option value="occupied" @selected(request('status') === 'occupied')>Terisi</option>
                    </select>
                </div>
                <div class="col-auto"><button class="btn btn-outline-primary" type="submit">Terapkan</button></div>
                <div class="col-auto"><a class="btn btn-link" href="{{ route('rooms.index') }}">Reset</a></div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Nomor kamar</th><th>Tipe & fasilitas</th><th>Harga / bulan</th><th>Status</th><th>Penghuni aktif</th><th class="text-end pe-4">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $room->room_number }}</td>
                            <td><div>{{ $room->type }}</div><div class="small text-secondary">{{ $room->facilities ?: '—' }}</div></td>
                            <td>Rp{{ number_format($room->monthly_price, 0, ',', '.') }}</td>
                            <td><span class="badge text-bg-{{ $room->status === 'available' ? 'success' : 'primary' }}">{{ $room->status === 'available' ? 'Tersedia' : 'Terisi' }}</span></td>
                            <td>{{ $room->activeLease?->tenant?->name ?? '—' }}</td>
                            <td class="text-end pe-4 text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('rooms.edit', $room) }}">Ubah</a>
                                <form class="d-inline" method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Hapus kamar ini? Kamar yang memiliki riwayat sewa tidak dapat dihapus.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-secondary py-5" colspan="6">Belum ada data kamar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
