<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Room::with('activeLease.tenant')->orderBy('room_number');

        if ($request->filled('status') && in_array($request->status, ['available', 'occupied'])) {
            $query->where('status', $request->status);
        }

        $rooms = $query->get();

        return view('rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        return view('rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:20', 'unique:rooms,room_number'],
            'type' => ['required', 'string', 'max:50'],
            'monthly_price' => ['required', 'integer', 'min:0'],
            'facilities' => ['nullable', 'string'],
        ], [
            'room_number.required' => 'Nomor kamar wajib diisi.',
            'room_number.unique' => 'Nomor kamar sudah terdaftar.',
            'type.required' => 'Tipe kamar wajib diisi.',
            'monthly_price.required' => 'Harga sewa bulanan wajib diisi.',
            'monthly_price.integer' => 'Harga sewa harus berupa angka.',
        ], [
            'room_number' => 'nomor kamar',
            'type' => 'tipe kamar',
            'monthly_price' => 'harga sewa per bulan',
            'facilities' => 'fasilitas',
        ]);

        Room::create($validated + ['status' => 'available']);

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:20', 'unique:rooms,room_number,'.$room->id],
            'type' => ['required', 'string', 'max:50'],
            'monthly_price' => ['required', 'integer', 'min:0'],
            'facilities' => ['nullable', 'string'],
        ], [
            'room_number.required' => 'Nomor kamar wajib diisi.',
            'room_number.unique' => 'Nomor kamar sudah digunakan kamar lain.',
            'type.required' => 'Tipe kamar wajib diisi.',
            'monthly_price.required' => 'Harga sewa bulanan wajib diisi.',
        ], [
            'room_number' => 'nomor kamar',
            'type' => 'tipe kamar',
            'monthly_price' => 'harga sewa per bulan',
            'facilities' => 'fasilitas',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->leases()->exists()) {
            return back()->with('error', 'Kamar tidak dapat dihapus karena memiliki riwayat masa sewa.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil dihapus.');
    }
}
