<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinnedItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_id',
        'type',
        'name',
        'url',
        'description',
        'stars',
    ];

    protected $casts = [
        'hero_id' => 'integer',
        'stars' => 'integer',
    ];

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }
}
