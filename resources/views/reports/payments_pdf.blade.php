<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #172033; }
        h1 { margin: 0 0 5px; font-size: 19px; }
        p { margin: 0 0 16px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dbe2ea; padding: 7px; text-align: left; vertical-align: top; }
        th { background: #edf2f7; }
        .number { width: 24px; text-align: center; }
        .amount { text-align: right; white-space: nowrap; }
    </style>
</head>
<body>
    <h1>Laporan Pembayaran Rumah Kos</h1>
    <p>Periode {{ $periodMonth }} · Dicetak {{ $generatedAt }} · Total lunas Rp{{ number_format($totalPaid, 0, ',', '.') }}</p>
    <table>
        <thead><tr><th class="number">No.</th><th>Tanggal</th><th>Penghuni</th><th>Kamar</th><th>Periode sewa</th><th>Metode</th><th>Status</th><th class="amount">Nominal</th></tr></thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td class="number">{{ $loop->iteration }}</td><td>{{ $payment->payment_date->format('d/m/Y') }}</td><td>{{ $payment->lease?->tenant?->name ?? '—' }}</td><td>{{ $payment->lease?->room?->room_number ?? '—' }}</td><td>{{ $payment->period_month }}</td><td>{{ $payment->method === 'tunai' ? 'Tunai' : 'Lainnya (manual)' }}</td><td>{{ $payment->status === 'paid' ? 'Lunas' : 'Menunggu' }}</td><td class="amount">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="8">Tidak ada catatan pembayaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>