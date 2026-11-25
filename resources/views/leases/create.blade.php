@extends('layouts.app')

@section('title', 'Catat Masa Sewa')

@section('content')
    <div class="mb-4"><a class="small text-decoration-none" href="{{ route('leases.index') }}">← Kembali ke masa sewa</a><h1 class="h2 page-heading mt-2 mb-1">Catat masa sewa</h1><p class="text-secondary mb-0">Kamar akan ditandai terisi setelah masa sewa aktif dicatat.</p></div>
    @if ($rooms->isEmpty() || $tenants->isEmpty())
        <div class="alert alert-warning">Untuk mencatat masa sewa, tambahkan setidaknya satu kamar tersedia dan satu penghuni terlebih dahulu.</div>
    @endif
    <section class="card"><div class="card-body p-4">
        @include('leases._form', ['lease' => null, 'action' => route('leases.store'), 'method' => 'POST'])
    </div></section>
@endsection