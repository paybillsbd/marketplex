<?php

namespace MarketPlex\Helpers;

class ModuleManager
{
	const MODULES = [
		[ 'icon_label' => 'home', 'name' => 'Home', 'route' => 'user::home' ],
		[ 'icon_label' => 'widgets', 'name' => 'Products', 'route' => 'user::products' ],
		[ 'icon_label' => 'store', 'name' => 'Stores', 'route' => 'user::stores' ],
		[ 'icon_label' => 'category', 'name' => 'Categories', 'route' => 'user::categories' ],
	];

	public static function getModules()
	{
		return json_decode(collect(self::MODULES)->toJson());
	}
}