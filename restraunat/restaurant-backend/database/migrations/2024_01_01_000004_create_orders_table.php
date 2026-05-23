<?php
// 2024_01_01_000004_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('address');
            $table->enum('payment_method', ['card', 'cash'])->default('cash');
            $table->text('comment')->nullable();
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending','confirmed','cooking','delivering','done','cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
            $table->unique(['user_id','product_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
