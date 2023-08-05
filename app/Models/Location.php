<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property $created_at
 * @property $updated_at
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
     * The coordinates that are related to the location.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coordinates(): HasMany
    {
        return $this->hasMany(Coordinate::class);
    }
}
