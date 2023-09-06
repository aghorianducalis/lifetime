<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $title
 * @property string $description
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coordinates(): HasMany
    {
        return $this->hasMany(Coordinate::class);
    }

    /**
     * The resources that are related to the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
