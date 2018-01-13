<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //
    /**
     * The attributes that should be hidden in arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function summary()
    {
        if (empty($this->title) || empty($this->branch))
            return self::htmlInvalidDataWarning();
    	return "Bank: " . $this->title . "\nBranch: " . $this->branch;
    }

    public function htmlSummary()
    {
        return nl2br($this->summary());
    }

    public static function invalidDataWarning()
    {
        return 'Invalid account';
    }

    public static function htmlInvalidDataWarning()
    {
        return '<strong style="color:red">' . self::invalidDataWarning() . '</strong>';
    }
}
