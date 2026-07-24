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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('opponent');
            $table->date('match_date');
            $table->time('match_time')->nullable();
            $table->string('venue');
            $table->string('maps_url')->nullable();
            $table->decimal('ticket_price', 8, 2)->nullable();
            $table->string('dress_code')->nullable();
            $table->text('facilities')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_label')->nullable();
            $table->decimal('payment_amount', 8, 2)->nullable();
            $table->timestamp('payment_due_at')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->text('whatsapp_announcement')->nullable();
            $table->string('status')->default('scheduled');
            $table->unsignedInteger('zeitlos_score')->nullable();
            $table->unsignedInteger('opponent_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
