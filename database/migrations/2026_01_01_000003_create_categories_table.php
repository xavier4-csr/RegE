<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });

        DB::table('categories')->insert([
            ['name'=>'Retail & Trade','slug'=>'retail-trade','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Food & Beverage','slug'=>'food-beverage','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Professional Services','slug'=>'professional-services','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Technology & ICT','slug'=>'technology-ict','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Construction & Real Estate','slug'=>'construction-real-estate','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Agriculture & Farming','slug'=>'agriculture-farming','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Healthcare','slug'=>'healthcare','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Education & Training','slug'=>'education-training','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Transport & Logistics','slug'=>'transport-logistics','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Media & Creative Arts','slug'=>'media-creative-arts','parent_id'=>null,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};