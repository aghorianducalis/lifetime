<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $coordinate_id
 * @property string $title
 * @property string $description
 * @property $created_at
 * @property $updated_at
 * @property-read Coordinate $coordinate
 */
class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * The coordinate that are related to the location.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coordinate(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
