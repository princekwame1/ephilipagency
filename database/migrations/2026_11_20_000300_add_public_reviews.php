<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Reviews sent from the public /review page wait for approval; the phone is for checking, never shown. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('phone', 40)->nullable()->after('role');
            $table->string('source', 20)->default('admin')->after('sort'); // admin | website
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', fn (Blueprint $table) => $table->dropColumn(['phone', 'source']));
    }
};
