<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('reference')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            $table->string('offer_type', 20)->index();      // sale | rent | lease | short_stay
            $table->string('status', 20)->default('draft')->index(); // draft | published
            $table->string('availability', 20)->default('available'); // available | under_offer | sold | rented
            $table->decimal('price', 14, 2)->default(0);
            $table->string('currency', 3)->default('GHS');
            $table->string('price_period', 10)->nullable(); // night | day | month | year (null = total price)
            $table->unsignedTinyInteger('bedrooms')->default(0);
            $table->unsignedTinyInteger('bathrooms')->default(0);
            $table->unsignedInteger('area')->nullable(); // square metres
            $table->unsignedTinyInteger('parking')->default(0);
            $table->boolean('furnished')->default(false);
            $table->string('location')->index(); // neighbourhood, e.g. Adenta
            $table->string('city')->default('Accra');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('summary', 300)->nullable();
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('amenity_property', function (Blueprint $table) {
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->primary(['amenity_id', 'property_id']);
        });

        // Every message from the public site: property enquiries, viewing requests and the contact form.
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20)->default('enquiry'); // enquiry | viewing | contact
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time', 20)->nullable();
            $table->string('status', 20)->default('new'); // new | contacted | scheduled | closed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // "List your property" requests from owners, reviewed in the dashboard.
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('offer_type', 20);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location');
            $table->decimal('price', 14, 2)->nullable();
            $table->string('currency', 3)->default('GHS');
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->string('status', 20)->default('pending'); // pending | approved | rejected
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 300)->nullable();
            $table->text('body');
            $table->string('cover_path')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('quote');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['testimonials', 'posts', 'submissions', 'enquiries', 'amenity_property', 'property_images', 'properties', 'agents', 'amenities', 'categories', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
