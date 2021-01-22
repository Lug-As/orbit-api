<?php

namespace App\Models;

use App\Traits\CanFormatImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImageAccount
 *
 * @property int $id
 * @property string $src
 * @property int $account_id
 * @property-read Account $account
 * @method static Builder|ImageAccount newModelQuery()
 * @method static Builder|ImageAccount newQuery()
 * @method static Builder|ImageAccount query()
 * @method static Builder|ImageAccount whereAccountId($value)
 * @method static Builder|ImageAccount whereId($value)
 * @method static Builder|ImageAccount whereSrc($value)
 * @mixin \Eloquent
 */
class ImageAccount extends Model
{
    use CanFormatImage;

    protected $fillable = [
        'src', 'account_id',
    ];

    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getSrcAttribute($data)
    {
        return $data ? $this->formatImage($data) : null;
    }
}
