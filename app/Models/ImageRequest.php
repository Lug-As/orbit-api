<?php

namespace App\Models;

use App\Traits\CanFormatImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImageRequest
 *
 * @property int $id
 * @property string $src
 * @property int $request_id
 * @property-read Request $request
 * @method static Builder|ImageRequest newModelQuery()
 * @method static Builder|ImageRequest newQuery()
 * @method static Builder|ImageRequest query()
 * @method static Builder|ImageRequest whereId($value)
 * @method static Builder|ImageRequest whereRequestId($value)
 * @method static Builder|ImageRequest whereSrc($value)
 * @mixin \Eloquent
 */
class ImageRequest extends Model
{
    use CanFormatImage;

    protected $fillable = [
        'src', 'request_id',
    ];

    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function getSrcAttribute($data)
    {
        return $data ? $this->formatImage($data) : null;
    }
}
