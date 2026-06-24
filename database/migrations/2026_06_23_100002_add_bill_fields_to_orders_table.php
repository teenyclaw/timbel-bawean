<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('dining_table_id')->nullable()->after('outlet_id')->constrained('dining_tables')->nullOnDelete();
            $table->string('source')->default('customer')->after('dining_table_id');
            $table->foreignId('created_by_user_id')->nullable()->after('source')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['dining_table_id']);
            $table->dropForeign(['created_by_user_id']);
            $table->dropColumn(['dining_table_id', 'source', 'created_by_user_id']);
        });
    }
};
