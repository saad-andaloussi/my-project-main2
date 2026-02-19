<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('role_id')->nullable()->constrained('roles')->after('id');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            // 1. Drop the foreign key constraint first
        $table->dropForeign(['role_id']); 
        // 2. Then drop the column
        $table->dropColumn('role_id');
        });
    }
};
