<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firebase_uid')->nullable()->unique()->after('remember_token');
            $table->string('phone', 50)->nullable()->after('email');
            $table->string('avatar', 500)->nullable()->after('phone');
            // Firebase users (phone / Google) have no local password.
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['firebase_uid']);
            $table->dropColumn(['firebase_uid', 'phone', 'avatar']);
            // NOTE: intentionally does not revert password nullability.
        });
    }
};
