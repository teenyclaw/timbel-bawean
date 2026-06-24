<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('is_enabled')->default(true);
            /** Rp spent per earn block, e.g. 1000 = every Rp 1000 */
            $table->unsignedInteger('earn_amount_basis')->default(1000);
            /** Points earned per earn block */
            $table->unsignedInteger('earn_points')->default(1);
            /** Rp discount per point redeemed */
            $table->unsignedInteger('redeem_rp_per_point')->default(100);
            $table->unsignedInteger('min_redeem_points')->default(50);
            /** Max % of payment subtotal redeemable with points */
            $table->unsignedTinyInteger('max_redeem_percent')->default(50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_settings');
    }
};
