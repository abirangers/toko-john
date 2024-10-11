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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('address', 255)->nullable();
            $table->string('province_name', 100)->nullable();
            $table->string('regency_name', 100)->nullable();
            $table->string('district_name', 100)->nullable();
            $table->string('village_name', 100)->nullable();

            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('regency_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('province_name');
            $table->dropColumn('regency_name');
            $table->dropColumn('district_name');
            $table->dropColumn('village_name');
            $table->dropColumn('province_id');
            $table->dropColumn('regency_id');
            $table->dropColumn('district_id');
            $table->dropColumn('village_id');
        });
    }
};
