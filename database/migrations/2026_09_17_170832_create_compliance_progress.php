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
         Schema::create('compliance_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id_id')->constrained()->onDelete('cascade');
            $table->foreignId('compliance_step')->constrained()->onDelete('cascade');
            $table->enum('status',['pending','in_progress','completed','skipped'])->default('pending');
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['company_id','compliance_step_id']);
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_progress');
    }
};
