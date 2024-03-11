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
        Schema::create('contributions_last_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_id')->constrained()->onDelete('cascade');
            $table->integer('total_commit_contributions')->default(0);
            $table->integer('total_issue_contributions')->default(0);
            $table->integer('total_pull_request_contributions')->default(0);
            $table->integer('total_pull_request_review_contributions')->default(0);
            $table->integer('total_repository_contributions')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions_last_years');
    }
};
