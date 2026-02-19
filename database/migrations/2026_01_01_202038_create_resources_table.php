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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('resource_category_id')->constrained('resource_categories')->cascadeOnDelete();
            $table->string('serial_number')->nullable()->unique();
            $table->integer('cpu_cores')->nullable();
            $table->integer('ram_gb')->nullable();
            $table->integer('storage_gb')->nullable();
            $table->decimal('purchase_price', 10, 2);
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            $table->decimal('price_per_hour', 8, 2);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('bandwidth_gbps', 5, 2)->nullable();
            $table->string('storage_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
