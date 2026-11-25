<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('rooms', 'room_number')) {
                $table->string('room_number', 20)->unique();
            }
            if (! Schema::hasColumn('rooms', 'type')) {
                $table->string('type', 50);
            }
            if (! Schema::hasColumn('rooms', 'monthly_price')) {
                $table->unsignedInteger('monthly_price')->default(0);
            }
            if (! Schema::hasColumn('rooms', 'status')) {
                $table->enum('status', ['available', 'occupied'])->default('available');
            }
            if (! Schema::hasColumn('rooms', 'facilities')) {
                $table->text('facilities')->nullable();
            }
        });

        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'name')) {
                $table->string('name', 100);
            }
            if (! Schema::hasColumn('tenants', 'id_card_number')) {
                $table->string('id_card_number', 30)->unique();
            }
            if (! Schema::hasColumn('tenants', 'phone')) {
                $table->string('phone', 20);
            }
            if (! Schema::hasColumn('tenants', 'origin_address')) {
                $table->string('origin_address', 255)->nullable();
            }
            if (! Schema::hasColumn('tenants', 'emergency_contact')) {
                $table->string('emergency_contact', 100)->nullable();
            }
        });

        Schema::table('leases', function (Blueprint $table) {
            if (! Schema::hasColumn('leases', 'room_id')) {
                $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            }
            if (! Schema::hasColumn('leases', 'tenant_id')) {
                $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            }
            if (! Schema::hasColumn('leases', 'start_date')) {
                $table->date('start_date');
            }
            if (! Schema::hasColumn('leases', 'end_date')) {
                $table->date('end_date');
            }
            if (! Schema::hasColumn('leases', 'monthly_rent')) {
                $table->unsignedInteger('monthly_rent')->default(0);
            }
            if (! Schema::hasColumn('leases', 'status')) {
                $table->enum('status', ['active', 'ended'])->default('ended');
            }
            if (! Schema::hasColumn('leases', 'notes')) {
                $table->text('notes')->nullable();
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'lease_id')) {
                $table->foreignId('lease_id')->constrained('leases')->restrictOnDelete();
            }
            if (! Schema::hasColumn('payments', 'payment_date')) {
                $table->date('payment_date');
            }
            if (! Schema::hasColumn('payments', 'period_month')) {
                $table->string('period_month', 7);
            }
            if (! Schema::hasColumn('payments', 'amount')) {
                $table->unsignedInteger('amount')->default(0);
            }
            if (! Schema::hasColumn('payments', 'method')) {
                $table->enum('method', ['tunai', 'lainnya'])->default('tunai');
            }
            if (! Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['paid', 'pending'])->default('pending');
            }
            if (! Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        // This compatibility migration preserves columns from deployments that ran earlier schema versions.
    }
};
