<?php

namespace App\Events;

use App\Models\FootballMatch;
use App\Team\PublicMatchPresenter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicMatchUpdated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $match;

    public function __construct(FootballMatch $match)
    {
        $this->match = app(PublicMatchPresenter::class)->present($match->fresh(), true);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('public-matches'),
            new Channel('public-match.'.$this->match['id']),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PublicMatchUpdated';
    }
}
