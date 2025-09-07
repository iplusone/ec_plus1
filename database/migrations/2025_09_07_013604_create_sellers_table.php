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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // 会社名
            $table->string('name_kana');           // 会社名カナ
            $table->string('code', 20)->nullable();// コード（任意）
            $table->string('email', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('address', 255)->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('corporate_number', 20)->nullable();   // 法人番号
            $table->string('registration_number', 50)->nullable(); // 登録番号
            $table->boolean('is_active')->default(false); // ← 仮登録=0、本登録=1
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
