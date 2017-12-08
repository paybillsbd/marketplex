<?php

namespace MarketPlex;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Log;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->token = str_random(30);
        });
    }
    
    /**
     * Confirm the user.
     *
     * @return bool
     */
    public function confirmEmail()
    {
        $this->verified = true;
        $this->token = null;

        return $this->save();
    }

    /**
     * Remove the user and confirms.
     *
     * @return bool
     */
    public function remove()
    {
        return $this->delete();
    }

    public function saveConfirmedProfile($name, $email, $phone, $password = null, $address = null)
    {
        if($this->id != Auth::user()->id)
        {
            return $errors['auth_mismatch'] = 'The information you are going to update to your profile is not yours!';
        }
        $this->name = $name;
        $this->email = $email;
        $this->address = str_replace('_', '/', $address);
        $this->phone_number = $phone;
        // dd($user);
        if($password)
            $this->password = str_replace('_', '/', $password);
        if(!$this->save())
            return $errors['failed_update'] = 'Failed to update your profile.';
        return [];
    }

    public function isAdmin()
    {
        return $this->email == config('mail.admin.address');
    }

    public function isCustomer()
    {
        return $this->stores()->count() == 0;
    }

    public function isVendor()
    {
        return $this->stores()->count() > 0;
    }

    public function isDeveloper()
    {
        if($this->hasEnvEmail('SECURITY_MAIL_DEV'))
        {
            Log::info( '[' . config('app.vendor') . ']' . 'Developer user action detected: ' . $this->email);
            return true;
        }
        return false;
    }

    public function isGuest()
    {
        if($this->hasEnvEmail('GUEST_MAIL_ADDRESSES'))
        {
            Log::info( '[' . config('app.vendor') . ']' . 'Guest user action detected: ' . $this->email);
            return true;
        }
        return false;
    }

    public static function getGuestEmails()
    {
        return self::getUserEmails('GUEST_MAIL_ADDRESSES');
    }

    private static function getUserEmails($env_var_key)
    {
        return preg_split("/[,]+/", env($env_var_key, 'demo.user@' . strtolower(config('app.vendor')) . '.com'));
    }

    private function hasEnvEmail($env_var_key)
    {
        foreach(self::getUserEmails($env_var_key) as $email)
        {
            if($email == $this->email)
            {
                return true;
            }
        }
        return false;
    }

    public static function guestUser()
    {
        if (Auth::guest() && env('GUEST_PROFILE_ALLOWED', false) === true)
        {
            foreach (self::getGuestEmails() as $mail)
            {
                $guestUser = self::whereEmail($mail)->first();
                if ($guestUser)
                {
                    return $guestUser;
                }
            }
        }
        return null;
    }

    public function hasNoProduct()
    {
        return $this->products->count() == 0;
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function stores()
    {        
        return $this->hasMany(Store::class);
    }
}
