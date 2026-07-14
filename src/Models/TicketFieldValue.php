<?php

namespace Zerp\SupportTicket\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketFieldValue extends Model
{
    use TenantScoped;

    /** No created_by column; the parent ticket carries the tenant boundary. */
    public string $tenantParent = 'ticket';

    protected $fillable = [
        'record_id',
        'field_id',
        'value'
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(TicketField::class, 'field_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'record_id');
    }
}