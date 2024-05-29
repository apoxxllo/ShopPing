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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('orderNumber')->unsigned()->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('address');
            $table->string('details');
            $table->string('payment');
            $table->date('estimateDate');
            $table->string('status')->default('pending');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 1000');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
