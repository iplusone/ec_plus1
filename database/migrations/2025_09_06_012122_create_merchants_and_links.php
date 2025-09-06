<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    // merchants
    if (!Schema::hasTable('merchants')) {
        Schema::create('merchants', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    // // users（管理/販売ユーザ用）を新設
    // Schema::create('users', function (Blueprint $t) {
    //   $t->id();
    //   $t->string('name');
    //   $t->string('email')->unique();
    //   $t->timestamp('email_verified_at')->nullable();
    //   $t->string('password');
    //   $t->string('role', 20); // 'admin' | 'seller'
    //   $t->rememberToken();
    //   $t->timestamps();
    // });

    // seller の所属（複数商材社に所属できる設計）
    if (!Schema::hasTable('merchant_user')) {
        Schema::create('merchant_user', function (Blueprint $t) {
            $t->id();
            $t->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->unique(['merchant_id','user_id']);
        });
    }

    foreach (['products','carts','orders'] as $table) {
        if (Schema::hasTable($table) && !Schema::hasColumn($table,'merchant_id')) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('merchant_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }
    }
  }

  public function down(): void {
    Schema::table('orders', fn(Blueprint $t)=> $t->dropConstrainedForeignId('merchant_id'));
    Schema::table('carts', fn(Blueprint $t)=> $t->dropConstrainedForeignId('merchant_id'));
    Schema::table('products', fn(Blueprint $t)=> $t->dropConstrainedForeignId('merchant_id'));
    Schema::dropIfExists('merchant_user');
    Schema::dropIfExists('users');
    Schema::dropIfExists('merchants');
  }
};

