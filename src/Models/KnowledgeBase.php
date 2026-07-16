<?php

namespace Zerp\SupportTicket\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeBase extends Model
{
    use TenantScoped;

    protected $table = 'support_ticket_knowledge_bases';
    
    protected $fillable = [
        'title',
        'description',
        'content',
        'category',
        'creator_id',
        'created_by'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(KnowledgeBaseCategory::class, 'category');
    }
}