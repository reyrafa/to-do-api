<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasUuids;

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'due_date',
        'priority'
    ];

    protected $casts = [
        'uuid' => 'string'
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

}
