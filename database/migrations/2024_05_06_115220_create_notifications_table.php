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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fromUser_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('toUser_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('description');
            $table->integer('orderNumber')->nullable();
            $table->string('status')->default('UNREAD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
