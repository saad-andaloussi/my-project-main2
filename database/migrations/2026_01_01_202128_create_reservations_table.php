<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->text('reason');
            $table->integer('quantity')->default(1);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->enum('payment', ['paid', 'unpaid', 'processing'])->default('unpaid');
            $table->enum('status', ['pending', 'approved', 'declined', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
