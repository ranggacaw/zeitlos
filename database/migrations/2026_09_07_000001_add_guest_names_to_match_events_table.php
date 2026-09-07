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
        Schema::table('match_events', function (Blueprint $table) {
            $table->string('guest_scorer_name')->nullable()->after('scorer_id');
            $table->string('guest_assist_name')->nullable()->after('assist_player_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            $table->dropColumn(['guest_scorer_name', 'guest_assist_name']);
        });
    }
};
