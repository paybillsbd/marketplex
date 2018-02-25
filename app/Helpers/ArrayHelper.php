<?php

namespace MarketPlex\Helpers;

class ArrayHelper
{
	// replaces the old keys with the new keys keeping the same values and returns new items
	public static function replaceKeysSeq(array $items, array $newKeys)
	{			
		foreach ($newKeys as $oldkey => $newKey) {
	        $items[$newKey] = $items[$oldkey];
	        array_forget($items, $oldkey);
		}
		return $items;
	}
}