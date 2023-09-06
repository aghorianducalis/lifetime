<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property double $amount
 * @property string $resource_type_id
 * @property $started_at
 * @property $ended_at
 * @property-read Collection|Event[]|array $events
 * @property-read ResourceType $resourceType
 * @property-read Collection|User[]|array $users
 */
class Resource extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'resources';

    protected $fillable = [
        'amount',
        'resource_type_id',
    ];

    /**
     * Interact with the resource's amount.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => round($value, 4),
            set: fn (string $value) => round($value, 4),
        );
    }

    /**
     * The events that are related to the resource.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_resource', 'resource_id', 'event_id');
    }

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
     * The users that are related to the resource.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'resource_user', 'resource_id', 'user_id');
    }
}
