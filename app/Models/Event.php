<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $title
 * @property string $description
 * @property-read Collection|Coordinate[]|array $coordinates
 * @property-read Collection|Resource[]|array $resources
 * @property-read Collection|User[]|array $users
 */
class Event extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * The coordinates that are related to the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coordinates(): BelongsToMany
    {
        return $this->belongsToMany(Coordinate::class, 'coordinate_event', 'event_id', 'coordinate_id');
    }

    /**
     * The resources that are related to the event.
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'event_resource', 'event_id', 'resource_id');
    }

    /**
     * The users that are related to the event.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id');
    }
}
