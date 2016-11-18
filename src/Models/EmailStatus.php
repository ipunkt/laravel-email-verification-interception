<?php

namespace Ipunkt\Laravel\EmailVerificationInterception\Models;

use Carbon\Carbon;
use DB;
use Ipunkt\Laravel\EmailVerificationInterception\Exceptions\TokenNotMatchingException;

class EmailStatus
{
    /** constants */
    const UNVERIFIED = 'unverified';
    const VERIFIED = 'verified';
    const BLACKLISTED = 'blacklisted';

    /**
     * @var Email
     */
    private $email;

    /**
     * EmailStatus constructor.
     * @param Email $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * returns valid values
     *
     * @return array
     */
    public static function validValues() : array
    {
        return [
            static::UNVERIFIED,
            static::VERIFIED,
            static::BLACKLISTED,
        ];
    }

    /**
     * is unverified
     *
     * @return bool
     */
    public function isUnverified() : bool
    {
        return $this->email->status === static::UNVERIFIED;
    }

    /**
     * is verified
     *
     * @return bool
     */
    public function isVerified() : bool
    {
        return $this->email->status === static::VERIFIED;
    }

    /**
     * is blacklisted
     *
     * @return bool
     */
    public function isBlacklisted() : bool
    {
        return $this->email->status === static::BLACKLISTED;
    }

    /**
     * can we send emails?
     *
     * @return bool
     */
    public function canSend() : bool
    {
        return $this->isVerified();
    }

    /**
     * can we send welcome mails?
     *
     * @return bool
     */
    public function canSendActionMails() : bool
    {
        return $this->isVerified() || $this->isUnverified();
    }

    /**
     * sets the email being verified
     *
     * @param string $token
     * @return Email
     * @throws TokenNotMatchingException when given token does not match stored token
     * @throws \Exception
     */
    public function verify(string $token) : Email
    {
        if (empty($token) || $token !== $this->email->token) {
            throw new TokenNotMatchingException('Token does not match');
        }

        if ($this->email->token === $token && !$this->isVerified()) {
            try {
                DB::beginTransaction();
                $this->email->token = null;
                $this->email->status = static::VERIFIED;
                $this->email->verified_at = Carbon::now();
                $this->email->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return $this->email;
    }

    /**
     * does a blacklisting
     *
     * @return Email
     * @throws \Exception
     */
    public function blacklist() : Email
    {
        if ($this->isBlacklisted()) {
            return $this->email;
        }

        try {
            DB::beginTransaction();
            $this->email->token = null;
            $this->email->status = static::BLACKLISTED;
            $this->email->blacklisted_at = Carbon::now();
            $this->email->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->email;
    }

    /**
     * refreshes token
     *
     * @return EmailStatus
     */
    public function refreshToken() : self
    {
        $this->email->token = str_random(24);

        return $this;
    }
}