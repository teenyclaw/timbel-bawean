<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->string('status', 20)->default('paid')->after('order_id');
            $table->string('midtrans_order_id')->nullable()->unique()->after('status');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->text('qris_url')->nullable()->after('midtrans_transaction_id');
            $table->timestamp('expires_at')->nullable()->after('qris_url');
            $table->timestamp('paid_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'qris_url',
                'expires_at',
            ]);
        });
    }
};
