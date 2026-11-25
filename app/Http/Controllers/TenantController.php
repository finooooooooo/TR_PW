<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ], [], ['search' => 'pencarian']);
        $search = $filters['search'] ?? '';

        $tenants = Tenant::with('activeLease.room')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('id_card_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('tenants.index', compact('tenants', 'search'));
    }

    public function create(): View
    {
        return view('tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'id_card_number' => ['required', 'regex:/^[0-9]{16}$/', 'unique:tenants,id_card_number'],
            'phone' => ['required', 'string', 'max:20'],
            'origin_address' => ['nullable', 'string', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:100'],
        ], [
            'name.required' => 'Nama penghuni wajib diisi.',
            'id_card_number.required' => 'NIK / Nomor KTP wajib diisi.',
            'id_card_number.regex' => 'NIK harus terdiri dari 16 digit angka.',
            'id_card_number.unique' => 'NIK / Nomor KTP sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
        ], [
            'name' => 'nama penghuni',
            'id_card_number' => 'NIK / Nomor KTP',
            'phone' => 'nomor telepon',
            'origin_address' => 'alamat asal',
            'emergency_contact' => 'kontak darurat',
        ]);

        Tenant::create($validated);

        return redirect()->route('tenants.index')->with('success', 'Data penghuni berhasil ditambahkan.');
    }

    public function edit(Tenant $tenant): View
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'id_card_number' => ['required', 'regex:/^[0-9]{16}$/', 'unique:tenants,id_card_number,'.$tenant->id],
            'phone' => ['required', 'string', 'max:20'],
            'origin_address' => ['nullable', 'string', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:100'],
        ], [
            'name.required' => 'Nama penghuni wajib diisi.',
            'id_card_number.required' => 'NIK / Nomor KTP wajib diisi.',
            'id_card_number.regex' => 'NIK harus terdiri dari 16 digit angka.',
            'id_card_number.unique' => 'NIK / Nomor KTP sudah digunakan penghuni lain.',
            'phone.required' => 'Nomor telepon wajib diisi.',
        ], [
            'name' => 'nama penghuni',
            'id_card_number' => 'NIK / Nomor KTP',
            'phone' => 'nomor telepon',
            'origin_address' => 'alamat asal',
            'emergency_contact' => 'kontak darurat',
        ]);

        $tenant->update($validated);

        return redirect()->route('tenants.index')->with('success', 'Data penghuni berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        if ($tenant->leases()->exists()) {
            return back()->with('error', 'Penghuni tidak dapat dihapus karena memiliki riwayat masa sewa.');
        }

        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Data penghuni berhasil dihapus.');
    }
}
