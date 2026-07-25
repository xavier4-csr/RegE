<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('company_name')->unique();
            $table->string('slug')->unique();
            $table->string('owner_name');
            $table->enum('business_type', ['sole_proprietorship','partnership','llc','corporation','ngo','cooperative'])->default('sole_proprietorship');
            $table->string('street_address');
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('postal_code',20)->nullable();
            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('company_description')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('registration_number')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('annual_return_due')->nullable();
            $table->enum('status',['pending','active','suspended'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->unsignedInteger('profile_views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};