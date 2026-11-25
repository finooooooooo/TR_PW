<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'period_month' => ['nullable', 'date_format:Y-m'],
        ], [], ['period_month' => 'periode laporan']);
        $periodMonth = $filters['period_month'] ?? now()->format('Y-m');

        $tenants = Tenant::with('activeLease.room')->orderBy('name')->get();
        $payments = Payment::with(['lease.room', 'lease.tenant'])
            ->when($periodMonth, fn ($q) => $q->where('period_month', $periodMonth))
            ->orderByDesc('payment_date')
            ->get();

        $totalPaid = $payments->where('status', 'paid')->sum('amount');

        return view('reports.index', compact('tenants', 'payments', 'periodMonth', 'totalPaid'));
    }

    public function tenantsPdf(): Response
    {
        $tenants = Tenant::with('activeLease.room')->orderBy('name')->get();

        $pdf = Pdf::loadView('reports.tenants_pdf', [
            'tenants' => $tenants,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-penghuni-kos.pdf');
    }

    public function paymentsPdf(Request $request): Response
    {
        $filters = $request->validate([
            'period_month' => ['nullable', 'date_format:Y-m'],
        ], [], ['period_month' => 'periode laporan']);
        $periodMonth = $filters['period_month'] ?? null;

        $payments = Payment::with(['lease.room', 'lease.tenant'])
            ->when($periodMonth, fn ($q) => $q->where('period_month', $periodMonth))
            ->orderByDesc('payment_date')
            ->get();

        $totalPaid = $payments->where('status', 'paid')->sum('amount');

        $pdf = Pdf::loadView('reports.payments_pdf', [
            'payments' => $payments,
            'periodMonth' => $periodMonth ?: 'Semua Periode',
            'totalPaid' => $totalPaid,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-pembayaran-kos.pdf');
    }
}
