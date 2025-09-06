<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('merchants')) {
      Schema::create('merchants', function (Blueprint $t) {
        $t->id();
        $t->string('name');
        $t->string('slug')->unique();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
      });
    }

    if (!Schema::hasTable('merchant_user')) {
      Schema::create('merchant_user', function (Blueprint $t) {
        $t->id();
        $t->foreignId('merchant_id')->constrained()->cascadeOnDelete();
        $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $t->unique(['merchant_id','user_id']);
      });
    }

    // 既存のECテーブルに merchant_id を付与（あればスキップ）
    foreach (['products','carts','orders'] as $table) {
      if (Schema::hasTable($table) && !Schema::hasColumn($table,'merchant_id')) {
        Schema::table($table, function (Blueprint $t) {
          $t->foreignId('merchant_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
      }
    }
  }

  public function down(): void {
    foreach (['products','carts','orders'] as $table) {
      if (Schema::hasTable($table) && Schema::hasColumn($table,'merchant_id')) {
        Schema::table($table, fn(Blueprint $t)=> $t->dropConstrainedForeignId('merchant_id'));
      }
    }
    Schema::dropIfExists('merchant_user');
    Schema::dropIfExists('merchants');
  }
};
