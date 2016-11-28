<?php

namespace Ipunkt\Laravel\EmailVerificationInterception\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Email
 *
 * @property integer $id
 * @property string $email
 * @property integer $user_id
 * @property string $status
 * @property string $token
 * @property Carbon $verified_at
 * @property Carbon $blacklisted_at
 * @property-read \App\User|\Illuminate\Database\Eloquent\Model $user
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereBlacklistedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\EmailVerificationInterception\Models\Email whereUpdatedAt($value)
 */
class Email extends Model
{
    /**
     * EmailStatus instance
     *
     * @var EmailStatus
     */
    protected $emailStatus = null;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'verified_at',
        'blacklisted_at',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::creating(function (Email $email) {
            $email->status = EmailStatus::UNVERIFIED;
            $email->status()->refreshToken();
        });

        parent::boot();
    }

    /**
     * returns related user
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * returns status
     *
     * @return EmailStatus
     */
    public function status() : EmailStatus
    {
        if ($this->emailStatus === null) {
            $this->emailStatus = new EmailStatus($this);
        }

        return $this->emailStatus;
    }

    /**
     * email attribute mutator
     *
     * @param string $value
     */
    public function setEmailAttribute($value) {
        $this->attributes['email'] = mb_strtolower(trim($value));
    }
}