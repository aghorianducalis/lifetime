<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
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
    use HasUuids;

    protected $table = 'locations';

    protected $fillable = [
        'title',
        'description',
        'coordinate_id',
    ];

    /**
     * The coordinate that are related to the location.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coordinate(): BelongsTo
    {
        return $this->belongsTo(Coordinate::class);
    }
}
