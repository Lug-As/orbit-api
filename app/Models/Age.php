<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Age
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Request[] $requests
 * @property-read int|null $requests_count
 * @method static \Illuminate\Database\Eloquent\Builder|Age newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Age newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Age query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $range
 * @method static \Illuminate\Database\Eloquent\Builder|Age whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Age whereRange($value)
 */
class Age extends Model
{
    protected $fillable = [
        'range',
    ];

    public $timestamps = false;

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function requests()
    {
        return $this->belongsToMany(Request::class);
    }
}
