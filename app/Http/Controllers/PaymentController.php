<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'period_month' => ['nullable', 'date_format:Y-m'],
            'status' => ['nullable', 'in:paid,pending'],
        ]);
        $query = Payment::with(['lease.room', 'lease.tenant'])->latest('payment_date');

        if (! empty($filters['period_month'])) {
            $query->where('period_month', $filters['period_month']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $payments = $query->get();

        return view('payments.index', compact('payments'));
    }

    public function create(): View
    {
        $leases = Lease::with(['room', 'tenant'])
            ->where('status', 'active')
            ->orderByDesc('id')
            ->get();

        return view('payments.create', compact('leases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lease_id' => ['required', 'exists:leases,id'],
            'payment_date' => ['required', 'date'],
            'period_month' => ['required', 'date_format:Y-m'],
            'amount' => ['required', 'integer', 'min:1'],
            'method' => ['required', 'in:tunai,lainnya'],
            'status' => ['required', 'in:paid,pending'],
            'notes' => ['nullable', 'string'],
        ], [
            'lease_id.required' => 'Data sewa wajib dipilih.',
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'period_month.required' => 'Periode bulan wajib diisi.',
            'period_month.date_format' => 'Format periode bulan harus YYYY-MM.',
            'amount.required' => 'Nominal pembayaran wajib diisi.',
        ], [
            'lease_id' => 'masa sewa',
            'payment_date' => 'tanggal pembayaran',
            'period_month' => 'periode sewa',
            'amount' => 'nominal pembayaran',
            'method' => 'metode pencatatan',
            'status' => 'status pembayaran',
            'notes' => 'catatan',
        ]);

        DB::transaction(function () use ($validated) {
            $lease = Lease::query()->lockForUpdate()
                ->whereKey($validated['lease_id'])
                ->where('status', 'active')
                ->first();

            if (! $lease) {
                throw ValidationException::withMessages([
                    'lease_id' => 'Pembayaran baru hanya dapat dicatat untuk masa sewa aktif.',
                ]);
            }

            Payment::create($validated);
        });

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function edit(Payment $payment): View
    {
        $leases = Lease::with(['room', 'tenant'])->orderByDesc('id')->get();

        return view('payments.edit', compact('payment', 'leases'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'lease_id' => ['required', 'exists:leases,id'],
            'payment_date' => ['required', 'date'],
            'period_month' => ['required', 'date_format:Y-m'],
            'amount' => ['required', 'integer', 'min:1'],
            'method' => ['required', 'in:tunai,lainnya'],
            'status' => ['required', 'in:paid,pending'],
            'notes' => ['nullable', 'string'],
        ], [], [
            'lease_id' => 'masa sewa',
            'payment_date' => 'tanggal pembayaran',
            'period_month' => 'periode sewa',
            'amount' => 'nominal pembayaran',
            'method' => 'metode pencatatan',
            'status' => 'status pembayaran',
            'notes' => 'catatan',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
