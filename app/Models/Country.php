<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $name
 * @property-read Collection|Region[] $regions
 * @property-read int|null $regions_count
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country query()
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereName($value)
 * @mixin \Eloquent
 */
class Country extends Model
{
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
