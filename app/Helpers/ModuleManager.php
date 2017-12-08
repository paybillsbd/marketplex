<?php

namespace MarketPlex\Helpers;

class ModuleManager
{
	const MODULES = [
		[ 'icon_label' => 'home', 'name' => 'Home', 'route' => 'user::home' ],
		[ 'icon_label' => 'widgets', 'name' => 'Products', 'route' => 'user::products' ],
		[ 'icon_label' => 'store', 'name' => 'Stores', 'route' => 'user::stores' ],
		[ 'icon_label' => 'category', 'name' => 'Categories', 'route' => 'user::categories' ],
		[ 'icon_label' => 'monetization_on', 'name' => 'Sales', 'route' => 'user::sales.index' ],
	];

	public static function getModules()
	{
		return json_decode(collect(self::MODULES)->toJson());
	}
}