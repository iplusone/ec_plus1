<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {

    // 商品/バリアントに税区分と価格モード（税抜/税込）
    if (Schema::hasTable('products')) {
      Schema::table('products', function (Blueprint $t) {
        if (!Schema::hasColumn('products','tax_class')) {
          $t->string('tax_class', 16)->default('standard'); // standard=10% / reduced=8%
        }
        if (!Schema::hasColumn('products','price_mode')) {
          $t->string('price_mode', 8)->default('tax_incl'); // tax_incl | tax_excl
        }
      });
    }
    if (Schema::hasTable('product_variants')) {
      Schema::table('product_variants', function (Blueprint $t) {
        if (!Schema::hasColumn('product_variants','tax_class')) {
          $t->string('tax_class', 16)->nullable(); // nullは親Product継承
        }
        if (!Schema::hasColumn('product_variants','price_mode')) {
          $t->string('price_mode', 8)->nullable(); // nullは親継承
        }
      });
    }

    // 注文と明細に税・送料・合計の内訳
    if (Schema::hasTable('orders')) {
      Schema::table('orders', function (Blueprint $t) {
        foreach ([
          'subtotal_excl_tax' => 'integer',
          'tax_10_amount'     => 'integer',
          'tax_8_amount'      => 'integer',
          'shipping_fee'      => 'integer',
          'discount_amount'   => 'integer',
          'grand_total'       => 'integer',
          'shipping_method'   => 'string',
          'shipping_status'   => 'string',
          'tracking_number'   => 'string',
        ] as $col => $type) {
          if (!Schema::hasColumn('orders', $col)) {
            $type === 'integer'
              ? $t->integer($col)->default(0)
              : $t->string($col)->nullable();
          }
        }
      });
    }

    if (Schema::hasTable('order_items')) {
      Schema::table('order_items', function (Blueprint $t) {
        if (!Schema::hasColumn('order_items','tax_rate'))   $t->unsignedTinyInteger('tax_rate')->default(10);
        if (!Schema::hasColumn('order_items','tax_amount')) $t->integer('tax_amount')->default(0);
        if (!Schema::hasColumn('order_items','price_mode')) $t->string('price_mode', 8)->default('tax_incl');
      });
    }

    // 配送設定テーブル（固定送料や閾値フリーなど）
    if (!Schema::hasTable('shipping_methods')) {
      Schema::create('shipping_methods', function (Blueprint $t) {
        $t->id();
        $t->string('code')->unique();       // 'flat', 'exp'など
        $t->string('name');
        $t->integer('base_fee')->default(0);
        $t->integer('free_threshold')->nullable(); // 合計がこれ以上なら送料無料
        $t->boolean('is_active')->default(true);
        $t->timestamps();
      });
    }
  }

  public function down(): void {
    if (Schema::hasTable('orders')) {
      Schema::table('orders', function (Blueprint $t) {
        foreach ([
          'subtotal_excl_tax','tax_10_amount','tax_8_amount',
          'shipping_fee','discount_amount','grand_total',
          'shipping_method','shipping_status','tracking_number'
        ] as $col) if (Schema::hasColumn('orders',$col)) $t->dropColumn($col);
      });
    }
    if (Schema::hasTable('order_items')) {
      Schema::table('order_items', function (Blueprint $t) {
        foreach (['tax_rate','tax_amount','price_mode'] as $col)
          if (Schema::hasColumn('order_items',$col)) $t->dropColumn($col);
      });
    }
    if (Schema::hasTable('shipping_methods')) Schema::drop('shipping_methods');

    if (Schema::hasTable('product_variants')) {
      Schema::table('product_variants', function (Blueprint $t) {
        foreach (['tax_class','price_mode'] as $c) if (Schema::hasColumn('product_variants',$c)) $t->dropColumn($c);
      });
    }
    if (Schema::hasTable('products')) {
      Schema::table('products', function (Blueprint $t) {
        foreach (['tax_class','price_mode'] as $c) if (Schema::hasColumn('products',$c)) $t->dropColumn($c);
      });
    }
  }
};
