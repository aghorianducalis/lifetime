<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property-read Location|null $location
 * @property-read Event[]|Collection $events
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
     * The location that are related to the coordinate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
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
}
