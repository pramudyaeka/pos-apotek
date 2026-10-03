<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'description',
        'reference_type',
        'reference_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        ?User $user,
        string $module,
        string $action,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?array $metadata = null
    ): self {
        return static::create([
            'user_id' => $user?->id,
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'metadata' => $metadata,
        ]);
    }
}