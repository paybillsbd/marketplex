<?php

namespace MarketPlex\Helpers;

class ContactProfileManager
{
    const TIDY_ADDRESS_DEVIDER = ', ';	
    const ADDRESS_DELIMITER = "<address>";

    public static function areaCodes()
    {
        return [ '+88' ];
    }

    public static function decodePhoneNumber($phone_number)
    {
        $keywords = preg_split("/[-]+/", $phone_number);
        if(count($keywords) == 1)
        {
            return [ 4, $keywords[0]];
        }
        $phone_number = $keywords[1];
        foreach(self::areaCodes() as $key => $value)
        {
            if($value == $keywords[0])
            {
                return [ $key, $phone_number ];
            }
        }
        return [5, ''];
    }

    public static function decodeAddress($address)
    {        
        $keywords = preg_split("/<address>/", $address);
        if(count($keywords) == 1)
            return [ 'DEFAULT' => '', 'HOUSE' => '', 'STREET' => '', 'LANDMARK' => '', 'TOWN' => '', 'POSTCODE' => '', 'STATE' => '' ];
        $addressDecoded = array();
        $sections = [ 'DEFAULT', 'HOUSE', 'STREET', 'LANDMARK', 'TOWN', 'POSTCODE', 'STATE' ];
        $index = 0;
        foreach($keywords as $keyword)
        {
            $addressDecoded[$sections[$index]] = $keyword;
            ++$index;
        }
        return $addressDecoded;
    }

    public static function tidyAddress($address)
    {
        $tidyAddress = '';
        $decodedAddress = self::decodeAddress($address);

        foreach($decodedAddress as $key => $value)
        {
            if(!$value)
            {
                $tidyAddress = rtrim($tidyAddress, self::TIDY_ADDRESS_DEVIDER);
                continue;
            }
            if($key == 'POSTCODE' || $key == 'STATE')
                continue;
            $tidyAddress .= $value . ($key == 'TOWN' ? '' : self::TIDY_ADDRESS_DEVIDER);
        }
        return $tidyAddress;
    }

    public static function encodeAddress(array $inputs)
    {
        $address = '';
        foreach($inputs as $key => $value)
            $address .= $value . self::ADDRESS_DELIMITER;
        return rtrim($address, self::ADDRESS_DELIMITER);
    }
}