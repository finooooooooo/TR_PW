@extends('layouts.app')

@section('title', 'Tambah Kamar')

@section('content')
    <div class="mb-4"><a class="small text-decoration-none" href="{{ route('rooms.index') }}">← Kembali ke kamar</a><h1 class="h2 page-heading mt-2 mb-1">Tambah kamar</h1><p class="text-secondary mb-0">Masukkan informasi kamar kos.</p></div>
    <section class="card"><div class="card-body p-4">
        @include('rooms._form', ['room' => null, 'action' => route('rooms.store'), 'method' => 'POST'])
    </div></section>
@endsection