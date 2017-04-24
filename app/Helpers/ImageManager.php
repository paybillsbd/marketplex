<?php

namespace MarketPlex\Helpers;

class ImageManager
{
    const API_PREFIX = '/api';
	const PUBLIC_TOKEN = '0KNao1NNUZt9oxjE7uERt6DxTRj6xz8CWXNfvdxMKQBs6zYqFOLx15zaHQPe';

    public static function getPublicRoute($version, $context)
    {
    	return self::getRoute($version, $context, self::PUBLIC_TOKEN);
    }

    public static function getRoute($version, $context, $api_token)
    {
    	return self::API_PREFIX . '/' . $version . '/' . $context . '?api_token=' . $api_token;
    }
}