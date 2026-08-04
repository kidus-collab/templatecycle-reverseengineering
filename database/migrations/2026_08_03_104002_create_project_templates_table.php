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
        Schema::create('project_templates', function (Blueprint $table) {
            $table->id();
     
          // Foregin key constraints 
          $table->foreignId('projects_id')->constrained()->cascadeOnDelete();

            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('trackCount')->default(0);
            $table->string('milestonesCount')->default(0);
            $table->string('subMilestonesCount')->default(0);
             $table->boolean('isActive')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_templates');
    }
};
