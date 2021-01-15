<?php

namespace Ms\Dobrozhil\Menu;

use Ms\Dobrozhil\General\Collection;

class MenuGroup extends Collection
{
	/** @var string */
	protected $key = null;

	/** @var string */
	protected $name = null;

	/** @var string */
	protected $code = null;

	/** @var string */
	protected $icon = null;

	/** @var array  */
	protected $valuesSorted = [];

	public function __construct ($key = null)
	{
		parent::__construct();

		if (!is_null($key))
		{
			$this->setKey($key);
		}

		return $this;
	}

	//<editor-fold defaultstate="collapse" desc=">>> Getters and Setters">
	/**
	 * Возвращает значение ключа группы меню
	 *
	 * @return string
	 */
	public function getKey (): string
	{
		return $this->key;
	}

	/**
	 * Устанавливает значение ключа группы меню
	 *
	 * @param string $key
	 *
	 * @return MenuGroup
	 */
	public function setKey (string $key)
	{
		$this->key = $key;

		return $this;
	}

	/**
	 * Возвращает имя группы меню
	 *
	 * @return string
	 */
	public function getName (): string
	{
		return $this->name;
	}

	/**
	 * Устанавливает имя группы меню
	 *
	 * @param string $name
	 *
	 * @return MenuGroup
	 */
	public function setName (string $name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Возвращает код группы меню
	 *
	 * @return string
	 */
	public function getCode (): string
	{
		return $this->code;
	}

	/**
	 * Устанавливает код группы меню
	 *
	 * @param string $code
	 *
	 * @return MenuGroup
	 */
	public function setCode (string $code)
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * Возвращает ссылку на файл иконки группы меню
	 *
	 * @return string
	 */
	public function getIcon (): string
	{
		return $this->icon;
	}

	/**
	 * Устанавливает ссылку на файл иконки группы меню
	 *
	 * @param string $icon
	 *
	 * @return MenuGroup
	 */
	public function setIcon (string $icon)
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * Возвращает массив отсортированный по заданной сортировке
	 *
	 * @return array
	 */
	public function getValuesSorted (): array
	{
		return $this->valuesSorted;
	}
	//</editor-fold>

	/**
	 * Добавляет элемент меню в группу
	 *
	 * @param MenuElement $menuElement  Объект, описывающий элемент меню
	 *
	 * @return $this
	 */
	public function addElement (MenuElement $menuElement)
	{
		$elementId = $menuElement->getElementID();
		parent::add($elementId, $menuElement);

		return $this;
	}

	public function getElement ($elementId)
	{
		return parent::get($elementId);
	}

	public function sortElements ($by = 'sort', $order='asc')
	{
		$order = strtolower($order);
		/** @var MenuElement[] $arElements */
		$arElements = $this->toArray();
		$arSort = [];
		if (!empty($arElements))
		{
			foreach ($arElements as $key=>$value)
			{
				$arSort[$value->getProperty($by)][] = $value->getElementID();
			}

			if ($order == 'asc')
			{
				ksort($arSort);
			}
			else
			{
				krsort($arSort);
			}
			$this->valuesSorted = $arSort;
		}
	}

}