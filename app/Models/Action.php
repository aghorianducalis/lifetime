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
class Action extends Model
{
    use HasFactory;

    protected $table = 'actions';

    protected $fillable = [
        'title',
        'description',
        'started_at',
        'ended_at',
    ];
}
