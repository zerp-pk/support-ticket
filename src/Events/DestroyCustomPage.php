<?php

namespace Zerp\SupportTicket\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\SupportTicket\Models\SupportTicketCustomPage;

class DestroyCustomPage
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public SupportTicketCustomPage $customPage
    ) {}
}