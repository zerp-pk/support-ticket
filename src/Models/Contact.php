<?php

namespace Zerp\SupportTicket\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use TenantScoped;

    protected $table = 'support_contacts';
    
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'subject',
        'message',
        'creator_id',
        'created_by'
    ];
}