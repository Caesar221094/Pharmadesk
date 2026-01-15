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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained('pharmacies');
            $table->foreignId('customer_id')->nullable()->constrained('users');
            $table->foreignId('expert_id')->nullable()->constrained('users');
            $table->foreignId('tech_id')->nullable()->constrained('users');
            $table->foreignId('module_id')->constrained('ticket_modules');
            $table->foreignId('category_id')->constrained('ticket_categories');
            $table->string('title');
            $table->text('description');
            $table->string('app_version')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'for_review', 'closed'])->default('open');
            $table->enum('source', ['team_expert', 'internal'])->default('team_expert');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
