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
class ResourceType extends Model
{
    use HasFactory;

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
}
