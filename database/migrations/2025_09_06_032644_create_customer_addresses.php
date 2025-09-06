<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
  Schema::create('customer_addresses', function (Blueprint $t) {
    $t->id();
    $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
    $t->string('name')->nullable();
    $t->string('postal')->nullable();
    $t->string('pref')->nullable();
    $t->string('city')->nullable();
    $t->string('line1')->nullable();
    $t->string('line2')->nullable();
    $t->string('tel')->nullable();
    $t->timestamps();
  });
}
public function down(): void { Schema::dropIfExists('customer_addresses'); }

};
