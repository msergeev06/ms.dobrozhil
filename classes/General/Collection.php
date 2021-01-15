<?php

namespace Ms\Dobrozhil\General;

use Ms\Core\Entity\System\Dictionary;

/**
 * Класс Ms\Dobrozhil\Entity\Collection
 * Коллекция
 */
class Collection extends Dictionary
{
	public function __construct (array $values = null)
	{
		parent::__construct($values);
	}
}