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
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');

            $table->string('login')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('bio_html')->nullable();
            $table->string('location')->nullable();
            $table->string('primary_language')->nullable();
            $table->integer('followers_count')->default(0);
            $table->integer('repositories_count')->default(0);
            $table->string('avatar_url')->nullable();
            $table->string('website_url')->nullable();
            $table->integer('pinned_items_count')->nullable();
            $table->integer('pinned_items_stars_count')->nullable();
            $table->integer('contributions_last_year')->nullable();
            $table->integer('repositories_contributed_count')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
