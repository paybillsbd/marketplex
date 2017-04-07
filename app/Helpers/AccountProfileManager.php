<?php

namespace MarketPlex\Helpers;

class AccountProfileManager
{
	const ICONS = [
		'user' => '/vendor/images/user.jpg'
	];
	const ACTIVITIES = [
		[ 'route' => '/logout', 'icon_label' => 'exit_to_app', 'title' => 'Logout' ]
	];

	public static function getActivities()
	{
		return json_decode(collect(self::ACTIVITIES)->toJson());
	}
}