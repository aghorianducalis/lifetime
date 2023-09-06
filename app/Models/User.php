<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property-read Collection|Coordinate[]|array $coordinates
 * @property-read Collection|Event[]|array $events
 * @property-read Collection|Resource[]|array $resources
 * @property-read Collection|ResourceType[]|array $resourceTypes
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The coordinates that belong to the user.
     */
    public function coordinates(): BelongsToMany
    {
        return $this->belongsToMany(Coordinate::class, 'coordinate_user', 'user_id', 'coordinate_id');
    }

    /**
     * The events that belong to the user.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user', 'user_id', 'event_id');
    }

    /**
     * The resources that are related to the event.
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'resource_user', 'user_id', 'resource_id');
    }

    /**
     * The resource types that belong to the user.
     */
    public function resourceTypes(): BelongsToMany
    {
        return $this->belongsToMany(ResourceType::class, 'resource_type_user', 'user_id', 'resource_type_id');
    }
}
