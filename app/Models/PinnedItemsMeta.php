<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinnedItemsMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_id',
        'count',
        'star_count',
    ];

    protected $casts = [
        'hero_id' => 'integer',
        'count' => 'integer',
        'star_count' => 'integer',
    ];

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }
}
