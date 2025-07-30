<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coach_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('navigating_career')->nullable(); // Dropdown value
            $table->string('session_help')->nullable();       // Dropdown value
            $table->string('current_role')->nullable();
            $table->string('career_goal')->nullable();
            $table->string('preferred_time')->nullable();     // Multiple options, store as string or JSON
            $table->longText('additional_info')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_matches');
    }
};
