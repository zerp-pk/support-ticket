<?php

namespace Zerp\SupportTicket\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'name',
        'email',
        'user_id',
        'account_type',
        'category',
        'subject',
        'status',
        'description',
        'attachments',
        'note',
        'creator_id',
        'created_by'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function conversions(): HasMany
    {
        return $this->hasMany(Conversion::class, 'ticket_id')->orderBy('id');
    }

    public function tcategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category');
    }

    /**
     * Link each {name, path} attachment element to a real Media row
     * (resolving one from a prior MediaPicker upload, or backfilling one
     * for a file written via the upload_file() helper), adding a media_id
     * key to each element. Shared by both Ticket and Conversion attachments.
     */
    public static function linkAttachmentsMedia(array $attachments, string $ownerModel, int $ownerId, string $collection, string $directory, ?int $creatorId, ?int $createdBy): array
    {
        if (empty($attachments)) {
            return $attachments;
        }

        $directoryId = \App\Services\MediaAttachmentService::ensureDirectory($directory, $createdBy, $creatorId);

        foreach ($attachments as &$attachment) {
            if (empty($attachment['path']) || !empty($attachment['media_id'])) {
                continue;
            }
            $media = \App\Services\MediaAttachmentService::resolveOrBackfill(
                $attachment['path'],
                $ownerModel,
                $ownerId,
                $collection,
                $creatorId,
                $createdBy,
                $directoryId
            );
            if ($media) {
                $attachment['media_id'] = $media->id;
            }
        }

        return $attachments;
    }

    /**
     * Clean up every attachment's underlying file: routes through the
     * linked Media row when present (disk-aware), else falls back to the
     * legacy direct-disk delete for pre-migration attachments.
     */
    public static function deleteAttachmentsMedia(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            if (!empty($attachment['media_id'])) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($attachment['media_id']);
                if ($media) {
                    \App\Services\MediaAttachmentService::deleteMedia($media);
                    continue;
                }
            }
            if (!empty($attachment['path'])) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('media/' . ltrim($attachment['path'], '/'));
            }
        }
    }
}