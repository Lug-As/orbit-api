<?php

namespace App\Models;

use App\Traits\CanFormatImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Account
 *
 * @property int $id
 * @property string $title
 * @property string|null $image
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|AdType[] $ad_types
 * @property-read int|null $ad_types_count
 * @property-read Collection|Response[] $responses
 * @property-read int|null $responses_count
 * @property-read Collection|Topic[] $topics
 * @property-read int|null $topics_count
 * @property-read User $user
 * @method static Builder|Account newModelQuery()
 * @method static Builder|Account newQuery()
 * @method static \Illuminate\Database\Query\Builder|Account onlyTrashed()
 * @method static Builder|Account query()
 * @method static Builder|Account whereCreatedAt($value)
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account whereImage($value)
 * @method static Builder|Account whereName($value)
 * @method static Builder|Account whereUpdatedAt($value)
 * @method static Builder|Account whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Account withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Account withoutTrashed()
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static Builder|Account whereDeletedAt($value)
 * @property int $followers
 * @property int $likes
 * @property-read Request|null $request
 * @method static Builder|Account whereFollowers($value)
 * @method static Builder|Account whereLikes($value)
 * @property string|null $telegram
 * @property string|null $email
 * @property string|null $phone
 * @method static Builder|Account whereEmail($value)
 * @method static Builder|Account wherePhone($value)
 * @method static Builder|Account whereTelegram($value)
 * @property string|null $about
 * @method static Builder|Account whereAbout($value)
 * @property int|null $region_id
 * @method static Builder|Account whereRegionId($value)
 * @property-read Region|null $region
 * @property-read Collection|Age[] $ages
 * @property-read int|null $ages_count
 * @property-read mixed $name
 * @method static Builder|Account whereTitle($value)
 * @property-read Collection|ImageAccount[] $images
 * @property-read int|null $images_count
 */
class Account extends Model
{
    use HasFactory, SoftDeletes, CanFormatImage;

    protected $fillable = [
        'title', 'image', 'about', 'user_id', 'region_id', 'telegram', 'email', 'phone',
    ];

    protected $attributes = [
        'followers' => 0,
        'likes' => 0,
    ];

    public function ad_types()
    {
        return $this->belongsToMany(AdType::class)
            ->withPivot('price');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function ages()
    {
        return $this->belongsToMany(Age::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function request()
    {
        return $this->hasOne(Request::class);
    }

    public function images()
    {
        return $this->hasMany(ImageAccount::class);
    }

    public function getRawImage()
    {
        return $this->getRaw('image');
    }

    public function getRaw($attr)
    {
        return $this->getAttributes()[$attr];
    }

    public function getImageAttribute($data)
    {
        return $data ? $this->formatImage($data) : null;
    }

    public function getTitleAttribute($data)
    {
        return '@' . $data;
    }
}
