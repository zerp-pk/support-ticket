<?php

namespace Zerp\SupportTicket\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Zerp\SupportTicket\Models\SupportTicketCustomPage;

class UpdateCustomPage
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Request $request,
        public SupportTicketCustomPage $supportTicketCustomPage
    ) {}
}