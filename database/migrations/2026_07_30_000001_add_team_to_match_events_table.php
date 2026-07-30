<?php

use App\Models\MatchEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            $table->string('team')->default(MatchEvent::TEAM_ZEITLOS)->after('event_type');
            $table->foreignId('scorer_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            $table->dropColumn('team');
            $table->foreignId('scorer_id')->nullable(false)->change();
        });
    }
};
