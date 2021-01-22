<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImageRequest
 *
 * @property int $id
 * @property string $src
 * @property int $request_id
 * @property-read \App\Models\Request $request
 * @method static \Illuminate\Database\Eloquent\Builder|ImageRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageRequest whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageRequest whereSrc($value)
 * @mixin \Eloquent
 */
class ImageRequest extends Model
{
    protected $fillable = [
        'src', 'request_id',
    ];

    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
