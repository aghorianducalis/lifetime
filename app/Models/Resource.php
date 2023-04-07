<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property double $count
 * @property int $resource_type_id
 * @property $started_at
 * @property $ended_at
 * @property-read ResourceType $resourceType
 */
class Resource extends Model
{
    use HasFactory;

    protected $table = 'resources';

    protected $fillable = [
        'count',
        'resource_type_id',
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
}
