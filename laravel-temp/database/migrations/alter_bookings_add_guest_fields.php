<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Tambah kolom baru yang dibutuhkan
            $table->string('booking_code')->unique()->nullable()->after('id');
            $table->string('type')->default('trip')->after('booking_code');
            $table->string('name')->nullable()->after('type');
            $table->string('phone')->nullable()->after('name');
            $table->string('email')->nullable()->after('phone');
            $table->unsignedBigInteger('restaurant_id')->nullable()->after('trip_id');
            $table->text('notes')->nullable()->after('total_price');
            $table->timestamp('confirmed_at')->nullable()->after('status');

            // Ubah trip_id menjadi nullable (sebelumnya NOT NULL)
            $table->unsignedBigInteger('trip_id')->nullable()->change();

            // Ubah user_id nullable (sudah nullable, tapi pastikan)
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Ubah booking_date menjadi date nullable
            $table->date('booking_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_code', 'type', 'name', 'phone', 'email', 'restaurant_id', 'notes', 'confirmed_at']);
        });
    }
};
