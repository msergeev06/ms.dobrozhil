<?php

namespace Ms\Dobrozhil\Menu;

use Ms\Dobrozhil\General\Collection;

class MenuCollection extends Collection
{
	/**
	 * MenuCollection constructor.
	 *
	 * @return MenuCollection
	 */
	public function __construct ()
	{
		parent::__construct();

		return $this;
	}

	/**
	 * Добавляет группу в меню
	 *
	 * @param MenuGroup $menuGroup
	 *
	 * @return MenuCollection
	 */
	public function addGroup (MenuGroup $menuGroup)
	{
		$key = $menuGroup->getKey();
		parent::add($key, $menuGroup);

		return $this;
	}

	/**
	 * Возвращает объект группы меню по ключу
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function getGroup (string $key)
	{
		return parent::get($key);
	}

}