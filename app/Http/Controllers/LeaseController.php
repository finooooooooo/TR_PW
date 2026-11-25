<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LeaseController extends Controller
{
    public function index(): View
    {
        $leases = Lease::with(['room', 'tenant'])->latest()->get();

        return view('leases.index', compact('leases'));
    }

    public function create(): View
    {
        $rooms = Room::where('status', 'available')->orderBy('room_number')->get();
        $tenants = Tenant::orderBy('name')->get();

        return view('leases.create', compact('rooms', 'tenants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'monthly_rent' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ], [
            'room_id.required' => 'Kamar wajib dipilih.',
            'tenant_id.required' => 'Penghuni wajib dipilih.',
            'start_date.required' => 'Tanggal mulai sewa wajib diisi.',
            'end_date.required' => 'Tanggal selesai sewa wajib diisi.',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'monthly_rent.required' => 'Nominal sewa per bulan wajib diisi.',
        ], [
            'room_id' => 'kamar',
            'tenant_id' => 'penghuni',
            'start_date' => 'tanggal mulai sewa',
            'end_date' => 'tanggal selesai sewa',
            'monthly_rent' => 'nominal sewa per bulan',
            'notes' => 'catatan',
        ]);

        DB::transaction(function () use ($validated) {
            $room = Room::query()->lockForUpdate()->findOrFail($validated['room_id']);
            Tenant::query()->lockForUpdate()->findOrFail($validated['tenant_id']);

            if ($room->status === 'occupied' || Lease::where('room_id', $room->id)->where('status', 'active')->exists()) {
                throw ValidationException::withMessages([
                    'room_id' => 'Kamar yang dipilih sedang terisi.',
                ]);
            }

            if (Lease::where('tenant_id', $validated['tenant_id'])->where('status', 'active')->exists()) {
                throw ValidationException::withMessages([
                    'tenant_id' => 'Penghuni masih memiliki masa sewa aktif.',
                ]);
            }

            $lease = Lease::create($validated + ['status' => 'active']);
            $this->syncRoomStatus($lease->room_id);
        });

        return redirect()->route('leases.index')->with('success', 'Data masa sewa berhasil ditambahkan.');
    }

    public function edit(Lease $lease): View
    {
        $rooms = Room::where('status', 'available')
            ->orWhere('id', $lease->room_id)
            ->orderBy('room_number')
            ->get();
        $tenants = Tenant::orderBy('name')->get();

        return view('leases.edit', compact('lease', 'rooms', 'tenants'));
    }

    public function update(Request $request, Lease $lease): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'monthly_rent' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,ended'],
            'notes' => ['nullable', 'string'],
        ], [
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ], [
            'room_id' => 'kamar',
            'tenant_id' => 'penghuni',
            'start_date' => 'tanggal mulai sewa',
            'end_date' => 'tanggal selesai sewa',
            'monthly_rent' => 'nominal sewa per bulan',
            'status' => 'status masa sewa',
            'notes' => 'catatan',
        ]);

        DB::transaction(function () use ($validated, $lease) {
            $lease = Lease::query()->lockForUpdate()->findOrFail($lease->id);
            $room = Room::query()->lockForUpdate()->findOrFail($validated['room_id']);
            Tenant::query()->lockForUpdate()->findOrFail($validated['tenant_id']);

            if ($validated['status'] === 'active') {
                $hasOtherActiveLease = Lease::where('room_id', $room->id)
                    ->where('status', 'active')
                    ->where('id', '!=', $lease->id)
                    ->exists();
                if ($hasOtherActiveLease) {
                    throw ValidationException::withMessages([
                        'room_id' => 'Kamar sudah digunakan oleh masa sewa aktif lain.',
                    ]);
                }

                $hasOtherTenantLease = Lease::where('tenant_id', $validated['tenant_id'])
                    ->where('status', 'active')
                    ->where('id', '!=', $lease->id)
                    ->exists();
                if ($hasOtherTenantLease) {
                    throw ValidationException::withMessages([
                        'tenant_id' => 'Penghuni masih memiliki masa sewa aktif lain.',
                    ]);
                }
            }

            $oldRoomId = $lease->room_id;
            $lease->update($validated);

            $this->syncRoomStatus($oldRoomId);
            $this->syncRoomStatus($lease->room_id);
        });

        return redirect()->route('leases.index')->with('success', 'Data masa sewa berhasil diperbarui.');
    }

    public function destroy(Lease $lease): RedirectResponse
    {
        $hasPayments = DB::transaction(function () use ($lease) {
            $lease = Lease::query()->lockForUpdate()->findOrFail($lease->id);
            if ($lease->payments()->exists()) {
                return true;
            }

            $roomId = $lease->room_id;
            $lease->delete();
            $this->syncRoomStatus($roomId);

            return false;
        });

        if ($hasPayments) {
            return back()->with('error', 'Masa sewa tidak dapat dihapus karena memiliki catatan pembayaran. Ubah statusnya menjadi selesai.');
        }

        return redirect()->route('leases.index')->with('success', 'Data masa sewa berhasil dihapus.');
    }

    private function syncRoomStatus(int $roomId): void
    {
        Room::whereKey($roomId)->update([
            'status' => Lease::where('room_id', $roomId)->where('status', 'active')->exists()
                ? 'occupied'
                : 'available',
        ]);
    }
}
