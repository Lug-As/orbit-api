<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Region
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @property-read Collection|Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read Country $country
 * @property-read Collection|Request[] $requests
 * @property-read int|null $requests_count
 * @method static Builder|Region newModelQuery()
 * @method static Builder|Region newQuery()
 * @method static Builder|Region query()
 * @method static Builder|Region whereCountryId($value)
 * @method static Builder|Region whereId($value)
 * @method static Builder|Region whereName($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $fillable = [
        'name', 'country_id',
    ];

    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
