<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'phone_country_id',
    ];

    public function user()
    {
        $this->belongsTo('User');
    }
}
