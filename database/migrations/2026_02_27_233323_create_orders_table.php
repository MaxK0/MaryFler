<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('status')->default('Новое');
            $table->decimal('total_price', 10, 2);
            $table->string('delivery_type')->default('pickup'); // pickup или delivery
            $table->text('delivery_address')->nullable();
            $table->timestamp('estimated_completion')->default(now()->addHour());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
