<?php

namespace Database\Seeders;

use App\Models\Lease;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@simkos.test'],
            [
                'name' => 'Admin Kos',
                'password' => Hash::make('password'),
            ]
        );

        $rooms = [
            ['room_number' => 'A-01', 'type' => 'Standar', 'monthly_price' => 650000, 'status' => 'occupied', 'facilities' => 'Kasur, Lemari, Meja, WiFi'],
            ['room_number' => 'A-02', 'type' => 'Standar', 'monthly_price' => 650000, 'status' => 'occupied', 'facilities' => 'Kasur, Lemari, Meja, WiFi'],
            ['room_number' => 'A-03', 'type' => 'Standar', 'monthly_price' => 650000, 'status' => 'available', 'facilities' => 'Kasur, Lemari, Meja, WiFi'],
            ['room_number' => 'B-01', 'type' => 'AC + KM Dalam', 'monthly_price' => 1100000, 'status' => 'occupied', 'facilities' => 'AC, KM Dalam, Kasur, Lemari, Meja, WiFi'],
            ['room_number' => 'B-02', 'type' => 'AC + KM Dalam', 'monthly_price' => 1100000, 'status' => 'available', 'facilities' => 'AC, KM Dalam, Kasur, Lemari, Meja, WiFi'],
            ['room_number' => 'B-03', 'type' => 'VIP', 'monthly_price' => 1450000, 'status' => 'available', 'facilities' => 'AC, KM Dalam, Water Heater, TV, WiFi'],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['room_number' => $room['room_number']], $room);
        }

        $tenants = [
            ['name' => 'Budi Santoso', 'id_card_number' => '3373011201020001', 'phone' => '081234567801', 'origin_address' => 'Semarang', 'emergency_contact' => '081234567891 (Ibu Budi)'],
            ['name' => 'Siti Aminah', 'id_card_number' => '3373012504030002', 'phone' => '081234567802', 'origin_address' => 'Solo', 'emergency_contact' => '081234567892 (Ayah Siti)'],
            ['name' => 'Rizky Pratama', 'id_card_number' => '3373011008010003', 'phone' => '081234567803', 'origin_address' => 'Yogyakarta', 'emergency_contact' => '081234567893 (Kakak Rizky)'],
            ['name' => 'Dewi Lestari', 'id_card_number' => '3373011809020004', 'phone' => '081234567804', 'origin_address' => 'Magelang', 'emergency_contact' => '081234567894 (Ibu Dewi)'],
        ];

        foreach ($tenants as $tenant) {
            Tenant::updateOrCreate(['id_card_number' => $tenant['id_card_number']], $tenant);
        }

        $lease1 = Lease::updateOrCreate([
            'room_id' => Room::where('room_number', 'A-01')->value('id'),
            'tenant_id' => Tenant::where('id_card_number', '3373011201020001')->value('id'),
            'start_date' => '2026-09-01',
        ], [
            'end_date' => '2027-02-28',
            'monthly_rent' => 650000,
            'status' => 'active',
            'notes' => 'Sewa 6 bulan',
        ]);

        $lease2 = Lease::updateOrCreate([
            'room_id' => Room::where('room_number', 'A-02')->value('id'),
            'tenant_id' => Tenant::where('id_card_number', '3373012504030002')->value('id'),
            'start_date' => '2026-10-01',
        ], [
            'end_date' => '2027-03-31',
            'monthly_rent' => 650000,
            'status' => 'active',
            'notes' => 'Sewa 6 bulan',
        ]);

        $lease3 = Lease::updateOrCreate([
            'room_id' => Room::where('room_number', 'B-01')->value('id'),
            'tenant_id' => Tenant::where('id_card_number', '3373011008010003')->value('id'),
            'start_date' => '2026-08-01',
        ], [
            'end_date' => '2027-01-31',
            'monthly_rent' => 1100000,
            'status' => 'active',
            'notes' => 'Kamar AC lantai 2',
        ]);

        $lease4 = Lease::updateOrCreate([
            'room_id' => Room::where('room_number', 'A-03')->value('id'),
            'tenant_id' => Tenant::where('id_card_number', '3373011809020004')->value('id'),
            'start_date' => '2026-05-01',
        ], [
            'end_date' => '2026-10-31',
            'monthly_rent' => 650000,
            'status' => 'ended',
            'notes' => 'Sudah selesai masa sewa',
        ]);

        Payment::updateOrCreate([
            'lease_id' => $lease1->id,
            'period_month' => '2026-10',
        ], [
            'payment_date' => '2026-10-02',
            'amount' => 650000,
            'method' => 'tunai',
            'status' => 'paid',
            'notes' => 'Lunas Oktober',
        ]);

        Payment::updateOrCreate([
            'lease_id' => $lease1->id,
            'period_month' => '2026-11',
        ], [
            'payment_date' => '2026-11-03',
            'amount' => 650000,
            'method' => 'lainnya',
            'status' => 'paid',
            'notes' => 'Lunas November',
        ]);

        Payment::updateOrCreate([
            'lease_id' => $lease2->id,
            'period_month' => '2026-11',
        ], [
            'payment_date' => '2026-11-05',
            'amount' => 650000,
            'method' => 'tunai',
            'status' => 'paid',
            'notes' => 'Lunas November',
        ]);

        Payment::updateOrCreate([
            'lease_id' => $lease3->id,
            'period_month' => '2026-11',
        ], [
            'payment_date' => '2026-11-01',
            'amount' => 1100000,
            'method' => 'lainnya',
            'status' => 'pending',
            'notes' => 'Menunggu konfirmasi bukti manual',
        ]);
    }
}
