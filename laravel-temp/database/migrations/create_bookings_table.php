<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->string('type')->default('trip'); // trip | kuliner
            $table->foreignId('trip_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->integer('participants_count')->default(1);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->date('booking_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending | paid | confirmed | cancelled
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
