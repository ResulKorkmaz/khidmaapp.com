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
        Schema::create('profile_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->enum('verification_type', ['basic', 'premium'])->default('basic');
            $table->decimal('price', 10, 2)->default(50.00); // $50/year
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('trial_expires_at')->nullable(); // 6 months free trial
            $table->boolean('is_trial')->default(true);
            $table->boolean('is_active')->default(false);
            $table->json('documents')->nullable(); // verification documents
            $table->text('admin_notes')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('payment_received_at')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['expires_at', 'is_active']);
            $table->index(['trial_expires_at', 'is_trial']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_verifications');
    }
};

