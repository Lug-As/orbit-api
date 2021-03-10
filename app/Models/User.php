<?php

namespace App\Models;

use App\Traits\CanFormatImage;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as DBBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $telegram
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Offer[] $offers
 * @property-read int|null $offers_count
 * @property-read Collection|Project[] $projects
 * @property-read int|null $projects_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static DBBuilder|User onlyTrashed()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static DBBuilder|User withTrashed()
 * @method static DBBuilder|User withoutTrashed()
 * @mixin \Eloquent
 * @property int $is_admin
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereIsAdmin($value)
 * @property-read Collection|Request[] $requests
 * @property-read int|null $requests_count
 * @property-read Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 */
class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, CanFormatImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'telegram',
        'image',
    ];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'bool',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class)
            ->latest();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function getImageAttribute($data)
    {
        return $data ? $this->formatImage($data) : null;
    }

    public function getRawImage()
    {
        return $this->getRaw('image');
    }

    public function getRaw($attr)
    {
        return $this->getAttributes()[$attr];
    }

    public function getEmailForVerification()
    {
        return md5($this->email);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify((new VerifyEmail)->locale('ru'));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify((new ResetPassword($token))->locale('ru'));
    }
}
