<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();   // en, ur, es ...
            $table->string('name', 100);           // English
            $table->string('native_name', 100)->nullable(); // اردو
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_rtl')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('language_code', 10)->index();
            $table->string('group', 50)->index();  // shop, admin, auth ...
            $table->string('key', 150);
            $table->text('value');
            $table->timestamps();

            $table->unique(['language_code', 'group', 'key']);
            $table->foreign('language_code')->references('code')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
        Schema::dropIfExists('languages');
    }
};
