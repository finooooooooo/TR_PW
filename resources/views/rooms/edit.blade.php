@extends('layouts.app')

@section('title', 'Ubah Kamar')

@section('content')
    <div class="mb-4"><a class="small text-decoration-none" href="{{ route('rooms.index') }}">← Kembali ke kamar</a><h1 class="h2 page-heading mt-2 mb-1">Ubah kamar {{ $room->room_number }}</h1><p class="text-secondary mb-0">Perbarui informasi kamar.</p></div>
    <section class="card"><div class="card-body p-4">
        @include('rooms._form', ['action' => route('rooms.update', $room), 'method' => 'PUT'])
    </div></section>
@endsection