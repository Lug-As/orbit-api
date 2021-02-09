<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property int $budget
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|AdType[] $ad_types
 * @property-read int|null $ad_types_count
 * @property-read Collection|Response[] $responses
 * @property-read int|null $responses_count
 * @property-read User $user
 * @method static Builder|Project newModelQuery()
 * @method static Builder|Project newQuery()
 * @method static Builder|Project query()
 * @method static Builder|Project whereBudget($value)
 * @method static Builder|Project whereCreatedAt($value)
 * @method static Builder|Project whereId($value)
 * @method static Builder|Project whereName($value)
 * @method static Builder|Project whereText($value)
 * @method static Builder|Project whereUpdatedAt($value)
 * @method static Builder|Project whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $followers_from
 * @property int|null $followers_to
 * @method static Builder|Project whereFollowersFrom($value)
 * @method static Builder|Project whereFollowersTo($value)
 * @property int|null $region_id
 * @property-read \App\Models\Region|null $region
 * @method static Builder|Project whereRegionId($value)
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'text', 'budget', 'user_id', 'followers_from', 'followers_to', 'region_id',
    ];

    public function ad_types()
    {
        return $this->belongsToMany(AdType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class)
            ->with('country');
    }
}
