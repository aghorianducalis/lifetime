<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $title
 * @property string $description
 * @property $created_at
 * @property $updated_at
 * @property-read Collection|Resource[]|array $resources
 * @property-read Collection|User[]|array $users
 */
class ResourceType extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'resource_types';

    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * The resources of that type.
     * Items or bag or a bunch of some counted resources.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * The users that belong to the resource types.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'resource_type_user', 'resource_type_id', 'user_id');
    }
}
