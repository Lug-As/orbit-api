<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImageAccount
 *
 * @property int $id
 * @property string $src
 * @property int $account_id
 * @property-read \App\Models\Account $account
 * @method static \Illuminate\Database\Eloquent\Builder|ImageAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageAccount whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageAccount whereSrc($value)
 * @mixin \Eloquent
 */
class ImageAccount extends Model
{
    protected $fillable = [
        'src', 'account_id',
    ];

    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
