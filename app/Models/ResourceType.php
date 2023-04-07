<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property $started_at
 * @property $ended_at
 */
class ResourceType extends Model
{
    use HasFactory;

    protected $table = 'resource_types';

    protected $fillable = [
        'title',
        'description',
    ];
}
