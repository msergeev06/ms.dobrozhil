<?php

namespace Ms\Dobrozhil\Menu;

use Ms\Core\Entity\System\Application;
use Ms\Dobrozhil\General\Collection;

class MenuElement extends Collection
{
	/** @var string */
	protected $elementID = null;

	/** @var int */
	protected $sort = null;

	/** @var string */
	protected $text = null;

	/** @var string */
	protected $url = null;

	/** @var string */
	protected $title = null;

	/** @var string */
	protected $icon = null;

	/** @var string */
	protected $page_icon = null;

	/** @var string */
	protected $favicon = null;

	/** @var string */
	protected $show = null;

	/** @var array */
	protected $add_links = null;

	protected $is_show = null;

	protected $is_selected = null;

	protected $valuesSorted = null;

	protected $is_children_selected = null;

	public function __construct (string $elementID)
	{
		parent::__construct();

		$this->elementID = $elementID;
		$this->sort = 500;
		$this->text = $elementID;
		$this->title = '';
		$this->url = '#';
		$this->show = false;
		$this->add_links = [];

		return $this;
	}

	//<editor-fold defaultstate="collapse" desc=">>> Getters and Setters">
	/**
	 * Возвращает ID элемента меню
	 *
	 * @return string
	 */
	public function getElementID (): string
	{
		return $this->elementID;
	}

	/**
	 * Устанавливает ID элемента меню
	 *
	 * @param string $elementID
	 *
	 * @return MenuElement
	 */
	public function setElementID (string $elementID)
	{
		$this->elementID = $elementID;

		return $this;
	}

	/**
	 * Возвращает текущее значение сортировки элемента меню
	 *
	 * @return int
	 */
	public function getSort (): int
	{
		return $this->sort;
	}

	/**
	 * Устанавливает значение сортировки элемента меню
	 *
	 * @param int $sort
	 *
	 * @return MenuElement
	 */
	public function setSort (int $sort)
	{
		$this->sort = $sort;

		return $this;
	}

	/**
	 * Возвращает текст элемента меню
	 *
	 * @return string
	 */
	public function getText (): string
	{
		return $this->text;
	}

	/**
	 * Устанавливает текст элемента меню
	 *
	 * @param string $text
	 *
	 * @return MenuElement
	 */
	public function setText (string $text)
	{
		$this->text = $text;

		return $this;
	}

	/**
	 * Возвращает ссылку элемента меню
	 *
	 * @return string
	 */
	public function getUrl (): string
	{
		return $this->url;
	}

	/**
	 * Устанавливает ссылкку элемента меню
	 *
	 * @param string $url
	 *
	 * @return MenuElement
	 */
	public function setUrl (string $url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * Возвращает текст всплывающей подсказки элемента меню
	 *
	 * @return string
	 */
	public function getTitle (): string
	{
		return $this->title;
	}

	/**
	 * Устанавливает текст всплывающей подсказки элемента меню
	 *
	 * @param string $title
	 *
	 * @return MenuElement
	 */
	public function setTitle (string $title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Возвращает ссылку на малую иконку элемента меню
	 *
	 * @return string
	 */
	public function getIcon (): string
	{
		return $this->icon;
	}

	/**
	 * Устанавливает ссылку на малую иконку элемента меню
	 *
	 * @param string $icon
	 *
	 * @return MenuElement
	 */
	public function setIcon (string $icon)
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * Возвращает ссылку на большую иконку элемента меню
	 *
	 * @return string
	 */
	public function getPageIcon (): string
	{
		return $this->page_icon;
	}

	/**
	 * Устанавливает ссылку на большую иконку элемента меню
	 *
	 * @param string $page_icon
	 *
	 * @return MenuElement
	 */
	public function setPageIcon (string $page_icon)
	{
		$this->page_icon = $page_icon;

		return $this;
	}

	/**
	 * Возвращает класс фавиконки элемента меню
	 *
	 * @return string
	 */
	public function getFavicon (): string
	{
		return $this->favicon;
	}

	/**
	 * Устанавливает класс фавиконки элемента меню
	 *
	 * @param string $favicon
	 *
	 * @return MenuElement
	 */
	public function setFavicon (string $favicon)
	{
		$this->favicon = $favicon;

		return $this;
	}

	/**
	 * Возвращает имя метода проверки возможности отображению элемента меню с пространством имен
	 *
	 * @return string
	 */
	public function getShow (): string
	{
		return $this->show;
	}

	/**
	 * Устанавливает имя метода проверки возможности отображения элемента меню с пространством имен
	 *
	 * @param string $show
	 *
	 * @return MenuElement
	 */
	public function setShow (string $show)
	{
		$this->show = $show;

		return $this;
	}

	/**
	 * Возвращает массив дополнительных ссылок, при которых пункт меню будет активным
	 *
	 * @return array
	 */
	public function getAddLinks (): array
	{
		return $this->add_links;
	}

	/**
	 * Устанавливает массив дополнительных ссылок, при которых пункт меню будет активным
	 *
	 * @param array $add_links
	 *
	 * @return MenuElement
	 */
	public function setAddLinks (array $add_links)
	{
		$this->add_links = $add_links;

		return $this;
	}

	/**
	 * Добавляет ссылку в массив дополнительных ссылок, при которых пункт меню будет активным
	 *
	 * @param string $link
	 *
	 * @return $this
	 */
	public function addAddLink (string $link)
	{
		if (!in_array($link,$this->add_links))
		{
			$this->add_links[] = $link;
		}

		return $this;
	}

	/**
	 * Очищает массив дополнительных ссылок, при которых пункт меню будет активным
	 *
	 * @return $this
	 */
	public function clearAddLinks ()
	{
		$this->add_links = [];

		return $this;
	}

	/**
	 * Возвращает значение укащанного свойства объекта
	 *
	 * @param string $propertyName
	 *
	 * @return mixed
	 */
	public function getProperty ($propertyName)
	{
		if (isset ($this->$propertyName))
		{
			return $this->$propertyName;
		}

		return null;
	}
	//</editor-fold>

	public function addChildren (MenuElement $menuElement)
	{
		$elementID = $menuElement->getElementID();

		parent::add($elementID, $menuElement);

		return $this;
	}

	public function issetChildren ()
	{
		return !$this->isEmpty();
	}

	public function getChildren (string $elementID)
	{
		return parent::get($elementID);
	}

	public function getAllChildren ()
	{
		return $this->toArray();
	}

	public function isShow (MenuGroup $menuGroup)
	{
		$USER = Application::getInstance()->getUser();
		if (is_null($this->is_show) || !is_bool($this->is_show))
		{
			$this->is_show = true;
			if (is_callable($this->getShow()))
			{
				$this->is_show  = call_user_func($this->getShow(),$menuGroup,$this,$USER);
			}
		}

		return $this->is_show;
	}

	public function isSelected ()
	{
/*		if ($this->issetChildren() && $this->isChildrenSelected())
		{
			$this->is_selected = true;
		}*/
		if (is_null($this->is_selected))
		{
			$scriptName = Application::getInstance()->getServer()->getRequestUri();
			if (strpos($scriptName,$this->getUrl())===false)
			{
				$this->is_selected = false;
				if (is_array($this->getAddLinks()) && !empty($this->getAddLinks()))
				{
					foreach ($this->getAddLinks() as $add_link)
					{
						if (strpos($scriptName,$add_link)!==false)
						{
							$this->is_selected = true;
						}
					}
				}
			}
			else
			{
				$this->is_selected = true;
			}
		}

		return $this->is_selected;
	}

	public function isChildrenSelected ()
	{
		if (!is_null($this->is_children_selected))
		{
			return $this->is_children_selected;
		}
		if (!$this->issetChildren())
		{
			$this->is_children_selected = false;
			return false;
		}

		$this->is_children_selected = false;
		$arChild = $this->toArray();
		/**
		 * @var string $key
		 * @var MenuElement $ar_child
		 */
		foreach ($arChild as $key=>$ar_child)
		{
			if ($ar_child->isSelected())
			{
				$this->is_children_selected = true;
				break;
			}
		}

		return $this->is_children_selected;
	}

	public function sortChildren ($order = 'asc')
	{
		$order = strtolower($order);
		/** @var MenuElement[] $arElements */
		$arElements = $this->toArray();
		$arSort = [];
		if (!empty($arElements))
		{
			foreach ($arElements as $key=>$value)
			{
				$arSort[$this->getText()][] = $value->getElementID();
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