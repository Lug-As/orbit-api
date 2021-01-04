<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @method static \Illuminate\Database\Eloquent\Builder|AdType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdType extends Model
{
    use HasFactory;

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function requests()
    {
        return $this->belongsToMany(Request::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
