<?php

namespace MarketPlex\Helpers;

class AccountProfileManager
{
	const ICONS = [
		'user' => '/vendor/images/user.jpg'
	];
	const ACTIVITIES = [
		[ 'route' => 'store-front', 'icon_label' => 'store', 'title' => 'Store Front' ]
	];

	public static function getActivities()
	{
		return json_decode(collect(self::ACTIVITIES)->toJson());
	}
}