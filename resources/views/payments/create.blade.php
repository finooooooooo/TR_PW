@extends('layouts.app')

@section('title', 'Catat Pembayaran')

@section('content')
    <div class="mb-4"><a class="small text-decoration-none" href="{{ route('payments.index') }}">← Kembali ke pembayaran</a><h1 class="h2 page-heading mt-2 mb-1">Catat pembayaran</h1><p class="text-secondary mb-0">Pembayaran hanya dicatat, tidak diproses secara online.</p></div>
    @if ($leases->isEmpty())
        <div class="alert alert-warning">Belum ada masa sewa aktif. <a href="{{ route('leases.create') }}">Catat masa sewa</a> sebelum memasukkan pembayaran.</div>
    @endif
    <section class="card"><div class="card-body p-4">
        @include('payments._form', ['payment' => null, 'action' => route('payments.store'), 'method' => 'POST'])
    </div></section>
@endsection