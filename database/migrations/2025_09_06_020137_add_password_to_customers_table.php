<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('customers', function (Blueprint $t) {
      if (!Schema::hasColumn('customers','password')) {
        $t->string('password')->nullable()->after('email');
      }
      if (!Schema::hasColumn('customers','remember_token')) {
        $t->rememberToken();
      }
    });
  }
  public function down(): void {
    Schema::table('customers', function (Blueprint $t) {
      if (Schema::hasColumn('customers','remember_token')) $t->dropColumn('remember_token');
      if (Schema::hasColumn('customers','password')) $t->dropColumn('password');
    });
  }
};
