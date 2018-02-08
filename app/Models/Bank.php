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

    public function hiddenInputFields($row)
    {
        return '<input  type="hidden"
                                name="deposits[' . $row . '][bank_title]" value="' . $this->title . '" />
                <input  type="hidden"
                        name="deposits[' . $row . '][bank_branch]" value="' . $this->branch . '" />';
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
