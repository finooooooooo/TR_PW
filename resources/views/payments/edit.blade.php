@extends('layouts.app')

@section('title', 'Ubah Pembayaran')

@section('content')
    <div class="mb-4"><a class="small text-decoration-none" href="{{ route('payments.index') }}">← Kembali ke pembayaran</a><h1 class="h2 page-heading mt-2 mb-1">Ubah catatan pembayaran</h1><p class="text-secondary mb-0">{{ $payment->lease->tenant->name }} · Periode {{ $payment->period_month }}</p></div>
    <section class="card"><div class="card-body p-4">
        @include('payments._form', ['action' => route('payments.update', $payment), 'method' => 'PUT'])
    </div></section>
@endsection