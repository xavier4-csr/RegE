<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('authority');
            $table->string('portal_url')->nullable();
            $table->integer('days_after_registration');
            $table->boolean('is_mandatory')->default(true);
            $table->json('applies_to_business_types')->nullable();
            $table->json('required_documents')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });



        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type',['registration_cert','tax_cert','permit','partnership_deed','memorandum','resolution','lease','other'])->default('other');
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->boolean('is_generated')->default(false);
            $table->date('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->string('reference')->unique();
            $table->string('mpesa_receipt')->nullable();
            $table->string('mpesa_checkout_id')->nullable();
            $table->decimal('amount',10,2);
            $table->string('currency',5)->default('KES');
            $table->enum('type',['registration_fee','premium_listing','document_fee','subscription']);
            $table->enum('status',['pending','completed','failed','refunded'])->default('pending');
            $table->string('phone_number',20)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_verified_customer')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table->unique(['company_id','user_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->enum('type',['compliance_reminder','payment_confirmed','document_ready','listing_approved','review_received','system'])->default('system');
            $table->string('action_url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('agent',['name_suggester','compliance_guide','document_reviewer','description_writer']);
            $table->text('prompt_summary');
            $table->text('response_summary');
            $table->string('model_used');
            $table->unsignedInteger('input_tokens')->default(0);
            $table->unsignedInteger('output_tokens')->default(0);
            $table->unsignedInteger('duration_ms')->default(0);
            $table->boolean('was_successful')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_interactions');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('compliance_steps');
    }
};