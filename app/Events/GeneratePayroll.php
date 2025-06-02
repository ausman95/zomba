<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneratePayroll
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $monthId;

    public function __construct($monthId)
    {
        $this->monthId = $monthId;
    }
}
