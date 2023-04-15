<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
//        'coordinates', // todo
    ];

    /**
     * The events that are related to the location.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
