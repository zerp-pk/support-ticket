<?php

namespace Zerp\SupportTicket\Models;

use App\Models\User;
use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCategory extends Model
{
    use TenantScoped;

    protected $fillable = [
        'name',
        'color',
        'creator_id',
        'created_by'
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'category');
    }
}