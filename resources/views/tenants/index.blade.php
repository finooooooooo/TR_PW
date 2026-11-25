@extends('layouts.app')

@section('title', 'Penghuni')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><p class="text-primary fw-semibold mb-1">DATA KOS</p><h1 class="h2 page-heading mb-1">Penghuni</h1><p class="text-secondary mb-0">Data penghuni dan kamar yang sedang ditempati.</p></div>
        <a class="btn btn-primary" href="{{ route('tenants.create') }}">+ Tambah penghuni</a>
    </div>
    <section class="card">
        <div class="card-body border-bottom">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-sm-8 col-md-5">
                    <label class="form-label" for="search">Cari penghuni</label>
                    <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Nama, NIK, atau nomor telepon">
                </div>
                <div class="col-auto"><button class="btn btn-outline-primary" type="submit">Cari</button></div>
                @if ($search)
                    <div class="col-auto"><a class="btn btn-link" href="{{ route('tenants.index') }}">Reset</a></div>
                @endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Nama penghuni</th><th>NIK / No. KTP</th><th>Telepon</th><th>Asal</th><th>Kamar aktif</th><th class="text-end pe-4">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($tenants as $tenant)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $tenant->name }}</td><td>{{ $tenant->id_card_number }}</td><td>{{ $tenant->phone }}</td><td>{{ $tenant->origin_address ?: '—' }}</td>
                            <td>{{ $tenant->activeLease?->room?->room_number ?? 'Belum ada' }}</td>
                            <td class="text-end pe-4 text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('tenants.edit', $tenant) }}">Ubah</a>
                                <form class="d-inline" method="POST" action="{{ route('tenants.destroy', $tenant) }}" onsubmit="return confirm('Hapus data penghuni ini? Data dengan riwayat sewa tidak dapat dihapus.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-secondary py-5" colspan="6">{{ $search ? 'Data penghuni tidak ditemukan.' : 'Belum ada data penghuni.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection