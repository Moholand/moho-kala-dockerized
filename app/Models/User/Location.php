<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    const TYPE_CITY = 'city';
    const TYPE_PROVINCE = 'province';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id'
    ];
}
