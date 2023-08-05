<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $x
 * @property float $y
 * @property float $z
 * @property Carbon $t
 * @property int $location_id
 * @property int $event_id
 * @property $created_at
 * @property $updated_at
 * @property-read Location $location
 * @property-read Event $event
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
     * The location that are related to the coordinate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * The event that are related to the coordinate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
