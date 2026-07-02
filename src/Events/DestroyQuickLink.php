<?php

namespace Zerp\SupportTicket\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\SupportTicket\Models\QuickLink;

class DestroyQuickLink
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public QuickLink $quickLink
    ) {}
}