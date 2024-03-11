<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContributionsLastYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_id',
        'total_commit_contributions',
        'total_issue_contributions',
        'total_pull_request_contributions',
        'total_pull_request_review_contributions',
        'total_repository_contributions',
    ];

    protected $casts = [
        'hero_id' => 'integer',
        'total_commit_contributions' => 'integer',
        'total_issue_contributions' => 'integer',
        'total_pull_request_contributions' => 'integer',
        'total_pull_request_review_contributions' => 'integer',
        'total_repository_contributions' => 'integer',
    ];

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }
}
