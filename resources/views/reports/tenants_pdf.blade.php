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
    </style>
</head>
<body>
    <h1>Laporan Penghuni Rumah Kos</h1>
    <p>Dicetak {{ $generatedAt }} · Total {{ $tenants->count() }} penghuni</p>
    <table>
        <thead><tr><th class="number">No.</th><th>Nama</th><th>NIK / No. KTP</th><th>Telepon</th><th>Alamat asal</th><th>Kamar aktif</th><th>Masa sewa</th></tr></thead>
        <tbody>
            @forelse ($tenants as $tenant)
                <tr>
                    <td class="number">{{ $loop->iteration }}</td><td>{{ $tenant->name }}</td><td>{{ $tenant->id_card_number }}</td><td>{{ $tenant->phone }}</td><td>{{ $tenant->origin_address ?: '—' }}</td>
                    @if ($tenant->activeLease)
                        <td>{{ $tenant->activeLease->room->room_number }}</td><td>{{ $tenant->activeLease->start_date->format('d/m/Y') }} – {{ $tenant->activeLease->end_date->format('d/m/Y') }}</td>
                    @else
                        <td>—</td><td>—</td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="7">Belum ada data penghuni.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>