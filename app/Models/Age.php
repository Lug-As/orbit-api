<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Age
 *
 * @property-read Collection|Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read Collection|Request[] $requests
 * @property-read int|null $requests_count
 * @method static Builder|Age newModelQuery()
 * @method static Builder|Age newQuery()
 * @method static Builder|Age query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $range
 * @method static Builder|Age whereId($value)
 * @method static Builder|Age whereRange($value)
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
