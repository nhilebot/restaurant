<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('reservation_date')->nullable(); // Để nullable vì lúc mới click chọn bàn có thể chưa chọn ngày
            $table->time('reservation_time')->nullable();
            $table->integer('table_id')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->json('cart_data')->nullable(); // Lưu món ăn dạng JSON
            $table->string('status')->default('pending'); // Chỉ cần 1 cột status (pending, confirmed, cancelled)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};