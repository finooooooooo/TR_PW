<?php

namespace Tests\Feature;

use App\Models\Lease;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimkosWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_and_user_can_sign_in(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => 'secret-password',
        ]);

        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk();
        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect(route('dashboard'));

        $this->post(route('logout'))->assertRedirect(route('login'));
    }

    public function test_room_tenant_lease_and_manual_payment_workflow(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('rooms.store'), [
            'room_number' => 'C-01',
            'type' => 'Standar',
            'monthly_price' => 750000,
            'facilities' => 'Kasur, lemari',
        ])->assertRedirect(route('rooms.index'));
        $room = Room::where('room_number', 'C-01')->firstOrFail();
        $this->assertSame('available', $room->status);

        $this->from(route('tenants.create'))->post(route('tenants.store'), [
            'name' => 'NIK Tidak Valid',
            'id_card_number' => '123456789012345',
            'phone' => '081234567890',
        ])->assertRedirect(route('tenants.create'))->assertSessionHasErrors('id_card_number');

        $this->post(route('tenants.store'), [
            'name' => 'Contoh Penghuni',
            'id_card_number' => '1234567890123456',
            'phone' => '081234567890',
            'origin_address' => 'Kota Contoh',
        ])->assertRedirect(route('tenants.index'));
        $tenant = Tenant::where('id_card_number', '1234567890123456')->firstOrFail();

        $this->post(route('leases.store'), [
            'room_id' => $room->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-11-01',
            'end_date' => '2027-10-31',
            'monthly_rent' => 750000,
            'notes' => 'Sewa tahunan',
        ])->assertRedirect(route('leases.index'));

        $lease = Lease::where('room_id', $room->id)->firstOrFail();
        $this->assertSame('active', $lease->status);
        $this->assertSame('occupied', $room->fresh()->status);

        $this->post(route('payments.store'), [
            'lease_id' => $lease->id,
            'payment_date' => '2026-11-05',
            'period_month' => '2026-11',
            'amount' => 750000,
            'method' => 'tunai',
            'status' => 'paid',
            'notes' => 'Pembayaran dicatat manual',
        ])->assertRedirect(route('payments.index'));

        $this->assertDatabaseHas('payments', [
            'lease_id' => $lease->id,
            'period_month' => '2026-11',
            'amount' => 750000,
            'status' => 'paid',
        ]);

        $this->get(route('dashboard'))->assertOk();
        $this->get(route('rooms.index'))->assertOk();
        $this->get(route('rooms.create'))->assertOk();
        $this->get(route('rooms.edit', $room))->assertOk();
        $this->get(route('tenants.index'))->assertOk();
        $this->get(route('tenants.index', ['search' => 'Contoh']))->assertOk()->assertSee('Contoh Penghuni');
        $this->get(route('tenants.index', ['search' => '1234567890123456']))->assertOk()->assertSee('Contoh Penghuni');
        $this->get(route('tenants.create'))->assertOk();
        $this->get(route('tenants.edit', $tenant))->assertOk();
        $this->get(route('leases.index'))->assertOk();
        $this->get(route('leases.create'))->assertOk();
        $this->get(route('leases.edit', $lease))->assertOk();
        $this->get(route('payments.index'))->assertOk();
        $this->get(route('payments.create'))->assertOk();
        $payment = Payment::firstOrFail();
        $this->get(route('payments.edit', $payment))->assertOk();
        $this->get(route('reports.index', ['period_month' => '2026-11']))->assertOk();
        $tenantPdf = $this->get(route('reports.tenants.pdf'));
        $tenantPdf->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $tenantPdf->getContent());

        $paymentPdf = $this->get(route('reports.payments.pdf', ['period_month' => '2026-11']));
        $paymentPdf->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $paymentPdf->getContent());

        $this->put(route('leases.update', $lease), [
            'room_id' => $room->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-11-01',
            'end_date' => '2027-10-31',
            'monthly_rent' => 750000,
            'status' => 'ended',
        ])->assertRedirect(route('leases.index'));
        $this->assertSame('available', $room->fresh()->status);
        $this->assertDatabaseHas('payments', ['lease_id' => $lease->id, 'period_month' => '2026-11']);
        $this->from(route('leases.index'))->delete(route('leases.destroy', $lease))
            ->assertRedirect(route('leases.index'))
            ->assertSessionHas('error');
        $this->from(route('payments.create'))->post(route('payments.store'), [
            'lease_id' => $lease->id,
            'payment_date' => '2026-11-06',
            'period_month' => '2026-12',
            'amount' => 750000,
            'method' => 'tunai',
            'status' => 'paid',
        ])->assertRedirect(route('payments.create'))->assertSessionHasErrors('lease_id');
    }

    public function test_duplicate_active_occupancy_and_deleting_room_with_history_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $room = Room::create([
            'room_number' => 'D-01',
            'type' => 'Standar',
            'monthly_price' => 600000,
            'status' => 'available',
        ]);
        $tenant = Tenant::create([
            'name' => 'Penghuni Uji',
            'id_card_number' => '9876543210987654',
            'phone' => '081234567891',
        ]);
        $anotherTenant = Tenant::create([
            'name' => 'Penghuni Kedua',
            'id_card_number' => '9876543210987655',
            'phone' => '081234567892',
        ]);

        $lease = Lease::create([
            'room_id' => $room->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-11-01',
            'end_date' => '2027-10-31',
            'monthly_rent' => 600000,
            'status' => 'active',
        ]);
        $room->update(['status' => 'occupied']);

        $anotherRoom = Room::create([
            'room_number' => 'D-02',
            'type' => 'Standar',
            'monthly_price' => 600000,
            'status' => 'available',
        ]);
        $this->from(route('leases.create'))->post(route('leases.store'), [
            'room_id' => $anotherRoom->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-11-15',
            'end_date' => '2027-11-14',
            'monthly_rent' => 600000,
        ])->assertRedirect(route('leases.create'))->assertSessionHasErrors('tenant_id');

        $this->from(route('leases.create'))->post(route('leases.store'), [
            'room_id' => $room->id,
            'tenant_id' => $anotherTenant->id,
            'start_date' => '2026-11-15',
            'end_date' => '2027-11-14',
            'monthly_rent' => 600000,
        ])->assertRedirect(route('leases.create'))->assertSessionHasErrors('room_id');

        $this->from(route('rooms.index'))->delete(route('rooms.destroy', $room))
            ->assertRedirect(route('rooms.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
        $this->assertDatabaseHas('leases', ['id' => $lease->id]);

        $this->post(route('payments.store'), [
            'lease_id' => $lease->id,
            'payment_date' => '2026-11-05',
            'period_month' => '2026-13',
            'amount' => 600000,
            'method' => 'transfer_manual',
            'status' => 'paid',
        ])->assertSessionHasErrors(['period_month', 'method']);

        $this->assertSame(0, Payment::count());
    }
}
