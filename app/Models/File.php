<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'path',
        'mime_type',
        'disk',
        'size',
        'collection_name',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
