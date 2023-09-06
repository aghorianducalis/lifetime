<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property double $amount
 * @property string $resource_type_id
 * @property string $event_id
 * @property $started_at
 * @property $ended_at
 * @property-read ResourceType $resourceType
 * @property-read Event $event
 */
class Resource extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'resources';

    protected $fillable = [
        'amount',
    ];

    /**
     * The resource entity of that resource item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class);
    }

    /**
     * The event that are related to the resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
