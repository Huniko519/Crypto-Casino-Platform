<?php

namespace App\Models;

use App\Models\Formatters\Formatter;
use App\Notifications\MailResetPasswordToken;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Formatter;

    use Notifiable {
        notify as protected _notify;
    }

    const ROLE_BOT   = 'BOT';
    const ROLE_USER  = 'USER';
    const ROLE_ADMIN = 'ADMIN';

    const STATUS_ACTIVE     = 0;
    const STATUS_BLOCKED    = 1;

    protected $formats = [
        'total_games'   => 'integer',
        'total_bet'     => 'decimal',
        'max_win'       => 'decimal',
        'total_win'     => 'decimal',
        'total_net_win' => 'decimal',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_login_from', 'role', 'status', 'last_login_at', 'email_verified_at', 'referrer_id'
    ];

    /**
     * The attributes that should be hidden from JSON output.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'status', 'role', 'last_login_from', 'referrer_id', 'referrer', 'totp_secret'
    ];


    /**
     * Auto-cast the following columns to Carbon
     *
     * @var array
     */
    protected $dates = ['last_login_at', 'email_verified_at'];

    /**
     * Send password reset link to user (overridden)
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }

    /**
     * Determine if the user has verified their email address.
     * Override Illuminate\Auth\MustVerifyEmail@hasVerifiedEmail()
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at) || $this->hasRole(self::ROLE_BOT);
    }

    /**
     * User account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * Social profiles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(SocialProfile::class);
    }


    /**
     * User, who referred current user (referrer)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Users, referred by current user (referees)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referees()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    /**
     * Chat messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * User games
     *
     * @return $this
     */
    public function games()
    {
        return $this->hasManyThrough(Game::class, Account::class)->where('status', Game::STATUS_COMPLETED);
    }

    /**
     * Override original model's delete() method to delete (cascade) polymorphic relationships
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        // delete all user games first to ensure it triggers deletion of related polymorphic models
        $this->hasManyThrough(Game::class, Account::class)
            ->get()
            // it's important to load each model one by one,
            // otherwise model's delete() method would not be fired and polymorphic relations would not be deleted.
            ->each(function ($game) {
                $game->delete();
            });

        return parent::delete();
    }

    /**
     * Check if user has given role
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return isset($this->role) && $this->role == $role;
    }

    public static function roles()
    {
        return [self::ROLE_BOT, self::ROLE_USER, self::ROLE_ADMIN];
    }


    /**
     * check if user is admin
     */
    public function admin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public static function statuses()
    {
        return [self::STATUS_ACTIVE, self::STATUS_BLOCKED];
    }

    // automatically decrypt TOTP secret when it's requested
    public function getTotpSecretAttribute($value)
    {
        return $value ? decrypt($value) : NULL;
    }

    // encrypt TOTP secret in the database
    public function setTotpSecretAttribute($value)
    {
        $this->attributes['totp_secret'] = encrypt($value);
    }

    // if referral bonuses are overridden for a given user
    public function getHasIndividualReferralBonusesAttribute()
    {
        return $this->referee_sign_up_credits
            || $this->referrer_sign_up_credits
            || $this->referrer_deposit_pct
            || $this->referrer_game_loss_pct
            || $this->referrer_game_win_pct;
    }
}
