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
        Schema::create('academician_research_grant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_grant_id')->constrained('research_grants')->onDelete('cascade');
            $table->foreignId('academician_id')->constrained('academicians')->onDelete('cascade');
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academician_research_grant');
    }
};
