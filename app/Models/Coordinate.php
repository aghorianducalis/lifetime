<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property float $x
 * @property float $y
 * @property float $z
 * @property Carbon $t
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Event[]|array $events
 * @property-read Location|null $location
 * @property-read Collection|User[]|array $users
 */
class Coordinate extends Model
{
    use HasFactory;

    protected $table = 'coordinates';

    protected $fillable = [
        'x',
        'y',
        'z',
        't',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        't' => 'datetime',
    ];

    /**
     * Interact with the coordinate's x.
     */
    protected function x(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => round($value, 6),
            set: fn (string $value) => round($value, 6),
        );
    }

    /**
     * Interact with the coordinate's y.
     */
    protected function y(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => round($value, 6),
            set: fn (string $value) => round($value, 6),
        );
    }

    /**
     * Interact with the coordinate's z.
     */
    protected function z(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => round($value, 6),
            set: fn (string $value) => round($value, 6),
        );
    }

    /**
     * The events that are related to the coordinate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * The location that are related to the coordinate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    /**
     * The users that are related to the coordinate.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coordinate_user', 'coordinate_id', 'user_id');
    }
}
