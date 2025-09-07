<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 商品
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        // 商品バリエーション
        Schema::create('product_variants', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('sku')->unique();
            $t->unsignedBigInteger('price_amount'); // 最小通貨単位（円なら1=¥1）
            $t->string('currency', 3)->default('JPY');
            $t->integer('stock')->default(0);
            $t->timestamps();
        });

        // 顧客
        Schema::create('customers', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->string('email')->unique();
            $t->timestamp('email_verified_at')->nullable();
            $t->string('password');
            $t->rememberToken();
            $t->timestamps();
        });

        // カート
        Schema::create('carts', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $t->string('currency',3)->default('JPY');
            $t->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $t) {
            $t->id();
            $t->uuid('cart_id');
            $t->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            $t->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('qty');
            $t->unsignedBigInteger('unit_price'); // 追加時点の価格
            $t->timestamps();
            $t->unique(['cart_id','product_variant_id']);
        });

        // 注文
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('number')->unique();
            $t->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $t->string('status', 32)->default('pending'); // pending|paid|cancelled|refunded
            $t->unsignedBigInteger('subtotal_amount');
            $t->unsignedBigInteger('tax_amount')->default(0);
            $t->unsignedBigInteger('shipping_amount')->default(0);
            $t->unsignedBigInteger('discount_amount')->default(0);
            $t->unsignedBigInteger('total_amount');
            $t->string('currency',3)->default('JPY');
            $t->timestamps();
        });

        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_variant_id')->constrained()->restrictOnDelete();
            $t->string('name');
            $t->string('sku');
            $t->unsignedInteger('qty');
            $t->unsignedBigInteger('unit_price');
            $t->unsignedBigInteger('tax_amount')->default(0);
            $t->unsignedBigInteger('discount_amount')->default(0);
            $t->unsignedBigInteger('line_total');
            $t->timestamps();
        });

        // 決済
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->string('provider'); // stripe 等
            $t->string('status', 32)->default('requires_capture');
            $t->unsignedBigInteger('amount');
            $t->string('currency',3)->default('JPY');
            $t->string('transaction_id')->nullable();
            $t->json('payload')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};
