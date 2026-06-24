<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlet_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['outlet_id', 'user_id']);
        });

        $outletId = \Illuminate\Support\Facades\DB::table('outlets')->orderBy('id')->value('id');
        if ($outletId) {
            $cashierIds = \Illuminate\Support\Facades\DB::table('users')->where('role', 'cashier')->pluck('id');
            $now = now();
            foreach ($cashierIds as $userId) {
                \Illuminate\Support\Facades\DB::table('outlet_user')->insert([
                    'outlet_id' => $outletId,
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('outlet_user');
    }
};
