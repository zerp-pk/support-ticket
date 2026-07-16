<?php

namespace Zerp\SupportTicket\Models;

use App\Models\User;
use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeBaseCategory extends Model
{
    use TenantScoped;

    protected $fillable = [
        'title',
        'creator_id',
        'created_by'
    ];

    public function knowledgeBases(): HasMany
    {
        return $this->hasMany(KnowledgeBase::class, 'category');
    }
}