<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Uploaded video tours, alongside the existing YouTube/Vimeo link. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('video_url');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('images');
            $table->string('video_path')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('properties', fn (Blueprint $table) => $table->dropColumn('video_path'));
        Schema::table('submissions', fn (Blueprint $table) => $table->dropColumn(['video_url', 'video_path']));
    }
};
