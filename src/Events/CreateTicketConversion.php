<?php

namespace Zerp\SupportTicket\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Zerp\SupportTicket\Models\Ticket;
use Zerp\SupportTicket\Models\Conversion;

class CreateTicketConversion
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Request $request,
        public Ticket $ticket,
        public Conversion $conversion,
    ) {}
}