<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Hero extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'login',
        'name',
        'email',
        'bio_html',
        'location',
        'primary_language',
        'followers_count',
        'repositories_count',
        'avatar_url',
        'website_url',
        'pinned_items_count',
        'pinned_items_stars_count',
        'contributions_last_year',
        'repositories_contributed_count',
        'notes',
    ];

    protected $casts = [
        'followers_count' => 'integer',
        'repositories_count' => 'integer',
        'pinned_items_count' => 'integer',
        'pinned_items_stars_count' => 'integer',
        'contributions_last_year' => 'integer',
        'repositories_contributed_count' => 'integer',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function contributionsLastYear()
    {
        return $this->hasOne(ContributionsLastYear::class);
    }

    public function pinnedItemsMeta()
    {
        return $this->hasOne(PinnedItemsMeta::class);
    }

    public function pinnedItems()
    {
        return $this->hasMany(PinnedItems::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function githubProfileUrl()
    {
        return "https://github.com/{$this->login}";
    }

    public function primaryLanguage()
    {
        return $this->belongsToMany(Language::class, 'hero_language')
            ->wherePivot('is_primary', true)
            ->first();
    }

    protected static function booted()
    {
        static::addGlobalScope('currentTeam', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('team_id', Auth::user()->currentTeam->id);
            }
        });

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->team_id = Auth::user()->currentTeam->id;
            }
        });
    }
}
