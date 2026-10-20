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
        Schema::create('blogs', function (Blueprint $table) {
        $table->id();
        $table->string('title');       
        $table->string('slug')->unique();
        $table->string('author')->default('Admin');
        $table->string('excerpt')->nullable();
        $table->text('content');
        $table->string('cover_image')->nullable();
        $table->string('tag')->nullable();
        $table->string('read_time')->nullable();
        $table->enum('status', ['published', 'draft'])->default('published');
        $table->timestamp('published_at')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
