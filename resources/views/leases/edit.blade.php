@extends('layouts.app')

@section('title', 'Ubah Masa Sewa')

@section('content')
    <div class="mb-4"><a class="small text-decoration-none" href="{{ route('leases.index') }}">← Kembali ke masa sewa</a><h1 class="h2 page-heading mt-2 mb-1">Ubah masa sewa</h1><p class="text-secondary mb-0">{{ $lease->tenant->name }} · Kamar {{ $lease->room->room_number }}</p></div>
    <section class="card"><div class="card-body p-4">
        @include('leases._form', ['action' => route('leases.update', $lease), 'method' => 'PUT'])
    </div></section>
@endsection