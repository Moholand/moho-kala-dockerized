<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
}
