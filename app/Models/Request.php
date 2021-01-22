<?php

namespace App\Models;

use App\Traits\CanFormatImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Request
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property int $user_id
 * @property int $checked
 * @property string|null $fail_msg
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|AdType[] $ad_types
 * @property-read int|null $ad_types_count
 * @property-read Collection|Topic[] $topics
 * @property-read int|null $topics_count
 * @property-read User $user
 * @method static Builder|Request newModelQuery()
 * @method static Builder|Request newQuery()
 * @method static Builder|Request query()
 * @method static Builder|Request whereChecked($value)
 * @method static Builder|Request whereCreatedAt($value)
 * @method static Builder|Request whereFailMsg($value)
 * @method static Builder|Request whereId($value)
 * @method static Builder|Request whereImage($value)
 * @method static Builder|Request whereName($value)
 * @method static Builder|Request whereUpdatedAt($value)
 * @method static Builder|Request whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $account_id
 * @method static Builder|Request whereAccountId($value)
 * @property int $followers
 * @property int $likes
 * @property-read Account|null $account
 * @method static Builder|Request whereFollowers($value)
 * @method static Builder|Request whereLikes($value)
 * @property string|null $telegram
 * @property string|null $email
 * @property string|null $phone
 * @method static Builder|Request whereEmail($value)
 * @method static Builder|Request wherePhone($value)
 * @method static Builder|Request whereTelegram($value)
 * @property string|null $about
 * @method static Builder|Request whereAbout($value)
 * @property int|null $region_id
 * @method static Builder|Request whereRegionId($value)
 * @property-read Region|null $region
 * @property-read Collection|Age[] $ages
 * @property-read int|null $ages_count
 * @property-read Collection|ImageRequest[] $images
 * @property-read int|null $images_count
 */
class Request extends Model
{
    use HasFactory, CanFormatImage;

    protected $fillable = [
        'name', 'image', 'about', 'user_id', 'region_id', 'telegram', 'email', 'phone',
    ];

    protected $casts = [
        'checked' => 'bool',
    ];

    protected $attributes = [
        'checked' => false,
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

    public function ages()
    {
        return $this->belongsToMany(Age::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function images()
    {
        return $this->hasMany(ImageRequest::class);
    }

    public function getNameAttribute($data)
    {
        return '@' . $data;
    }

    public function getImageAttribute($data)
    {
        return $data ? $this->formatImage($data) : null;
    }

    public function getRawName()
    {
        return $this->getRaw('name');
    }

    public function getRawImage()
    {
        return $this->getRaw('image');
    }

    public function getRaw(string $attr)
    {
        return $this->getAttributes()[$attr];
    }

    public function isApproved()
    {
        return $this->account_id !== null;
    }

    public function isNotApproved()
    {
        return $this->account_id === null;
    }

    public function isCanceled()
    {
        return $this->checked and $this->isNotApproved();
    }
}
