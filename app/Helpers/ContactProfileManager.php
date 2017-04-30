<?php

namespace MarketPlex\Helpers;

use Log;

class ContactProfileManager
{
    const TIDY_ADDRESS_DEVIDER = ', ';	
    const ADDRESS_DELIMITER = "<address>";
    const ADDRESS_SECTIONS = [ 'DEFAULT', 'HOUSE', 'STREET', 'LANDMARK', 'TOWN', 'POSTCODE', 'STATE' ];

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
        $index = 0;
        foreach($keywords as $keyword)
        {
            $addressDecoded[self::ADDRESS_SECTIONS[$index]] = $keyword;
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
            if($key == 'POSTCODE' || $key == 'STATE') // We do not show postcode and state as view
                continue;
            $tidyAddress .= $value . ($key == 'TOWN' ? '' : self::TIDY_ADDRESS_DEVIDER);
        }
        return $tidyAddress;
    }

    public static function encodeAddress(array $inputs)
    {
        $address = '';

        $endKey = 'address_town_city';
        if(array_has($inputs, 'state'))
            $endKey = 'state';
        else if(array_has($inputs, 'postcode'))
            $endKey = 'postcode';

        foreach($inputs as $key => $value)
            $address .= $value . ($key == $endKey ? '' : self::ADDRESS_DELIMITER);
        return $address;
    }
}