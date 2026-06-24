<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('is_enabled')->default(false);
            $table->boolean('notify_new_order')->default(true);
            $table->boolean('notify_order_ready')->default(false);
            $table->boolean('notify_customer_ready')->default(false);
            $table->string('telegram_bot_token')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->string('whatsapp_provider', 20)->default('webhook');
            $table->string('whatsapp_webhook_url')->nullable();
            $table->string('whatsapp_webhook_secret')->nullable();
            $table->string('whatsapp_fonnte_token')->nullable();
            $table->string('whatsapp_target')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
