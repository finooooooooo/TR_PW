@extends('layouts.app')

@section('title', 'Ubah Penghuni')

@section('content')
    <div class="mb-4"><a class="small text-decoration-none" href="{{ route('tenants.index') }}">← Kembali ke penghuni</a><h1 class="h2 page-heading mt-2 mb-1">Ubah data penghuni</h1><p class="text-secondary mb-0">{{ $tenant->name }}</p></div>
    <section class="card"><div class="card-body p-4">
        @include('tenants._form', ['action' => route('tenants.update', $tenant), 'method' => 'PUT'])
    </div></section>
@endsection