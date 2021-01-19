<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Region
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $country_name
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereName($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Request[] $requests
 * @property-read int|null $requests_count
 */
class Region extends Model
{
    protected $fillable = [
        'name', 'country_name',
    ];

    public $timestamps = false;

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
