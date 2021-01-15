<?php

namespace Ms\Dobrozhil\General;

use Ms\Core\Api\ApiAdapter;
use Ms\Core\Entity\Events\EventController;
use Ms\Core\Entity\System\Application;
use Ms\Core\Entity\System\Multiton;
use Ms\Core\Entity\User\User;
use Ms\Core\Lib\IO\Files;
use Ms\Dobrozhil\General\Options;
use Ms\Dobrozhil\Access\Access;
use Ms\Dobrozhil\Menu\MenuCollection;
use Ms\Dobrozhil\Menu\MenuElement;
use Ms\Dobrozhil\Menu\MenuGroup;

class Main extends Multiton
{
	/** @var MenuCollection */
	protected $menuCollection = null;

	protected function __construct ()
    {
        $this->menuCollection = new MenuCollection();
    }

    public function getAdminMenuCollection ()
	{
		if (is_null($this->menuCollection))
		{
			$this->menuCollection = new MenuCollection();
		}

        $this->createBaseAdminMenuCollection($this->menuCollection);

		$menuGroup = $this->menuCollection->getGroup('admin_main_menu_apps');

		ApiAdapter::getInstance()->getEventsApi()->runEvents(
			'ms.dobrozhil',
			'OnBuildAdminMainMenuApps',
			[&$menuGroup]
		);

		$menuGroup = $this->menuCollection->getGroup('admin_main_menu_devices');

        ApiAdapter::getInstance()->getEventsApi()->runEvents(
			'ms.dobrozhil',
			'OnBuildAdminMainMenuDevices',
			[&$menuGroup]
		);

        ApiAdapter::getInstance()->getEventsApi()->runEvents(
			'ms.dobrozhil',
			'OnBuildAdminMainMenu',
			[$this->menuCollection]
		);

		return $this->menuCollection;
	}

	protected function createBaseAdminMenuCollection (MenuCollection $menuCollection)
	{
		$menuCollection
			->addGroup(
				(new MenuGroup('admin_main_menu_general'))
					->setName('Главное')
					->setCode('general')
					->setIcon('/ms/images/ms_dobrozhil/general_icon.png')
					->addElement(
						(new MenuElement('objects'))
							->setSort(100)
							->setText('Объекты')
							->setUrl('objects/')
							->setTitle('Управление классами и объектами системы')
							->setIcon('/ms/images/ms_dobrozhil/objects_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/objects_big_icon.png')
							->setFavicon('fa fa-th')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('menu'))
							->setSort(200)
							->setText('Меню управления')
							->setUrl('#')
							->setTitle('Настройка меню управления')
							->setIcon('/ms/images/ms_dobrozhil/menu_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/menu_big_icon.png')
							->setFavicon('fa fa-tasks')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('patterns'))
							->setSort(300)
							->setText('Шаблоны поведения')
							->setUrl('patterns/')
							->setTitle('Создание шаблонов поведения')
							->setIcon('/ms/images/ms_dobrozhil/patterns_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/patterns_big_icon.png')
							->setFavicon('fa fa-commenting-o')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('interface'))
							->setSort(400)
							->setText('Интерфейсы')
							->setUrl('#')
							->setTitle('Управление интерфейсами')
							->setIcon('/ms/images/ms_dobrozhil/scene_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/scene_big_icon.png')
							->setFavicon('fa fa-laptop')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('scripts'))
							->setSort(500)
							->setText('Скрипты')
							->setUrl('scripts/')
							->setTitle('Создание и управление всеми скриптами')
							->setIcon('/ms/images/ms_dobrozhil/scripts_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/scripts_big_icon.png')
							->setFavicon('fa fa-edit')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
/*					->addElement(
						(new MenuElement('webvars'))
							->setSort(600)
							->setText('Веб-переменные')
							->setUrl('webvars/')
							->setTitle('Создание и управление веб-переменными')
							->setIcon('/ms/images/ms_dobrozhil/webvars_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/webvars_big_icon.png')
							->setFavicon('fa fa-share-alt')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)*/
					->addElement(
						(new MenuElement('vars'))
							->setSort(700)
							->setText('Переменные')
							->setUrl('vars/')
							->setTitle('Создание и изменение переменных')
							->setIcon('/ms/images/ms_dobrozhil/webvars_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/webvars_big_icon.png')
							->setFavicon('fa fa-share-alt')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('events'))
							->setSort(800)
							->setText('События')
							->setUrl('events/')
							->setTitle('Управление событиями, создание обработчиков')
							->setIcon('/ms/images/ms_dobrozhil/webvars_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/webvars_big_icon.png')
							->setFavicon('fa fa-bell')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
			)
			->addGroup(
				(new MenuGroup('admin_main_menu_apps'))
					->setName('Приложения')
					->setCode('apps')
					->setIcon('/ms/images/ms_dobrozhil/apps_icon.png')
/*					->addElement(
						(new MenuElement('calendar'))
							->setSort(100)
							->setText('Календарь')
							->setUrl('apps/calendar/')
							->setTitle('Календарь')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-calendar')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)*/
/*					->addElement(
						(new MenuElement('gps_tracker'))
							->setSort(200)
							->setText('GPS-трекер')
							->setUrl('#')
							->setTitle('GPS-трекер')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('media'))
							->setSort(300)
							->setText('Медиа')
							->setUrl('#')
							->setTitle('Медиа')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('pleer'))
							->setSort(400)
							->setText('Плеер')
							->setUrl('#')
							->setTitle('Плеер')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('shop'))
							->setSort(500)
							->setText('Продукты')
							->setUrl('#')
							->setTitle('Продукты')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('says'))
							->setSort(600)
							->setText('Цитаты')
							->setUrl('#')
							->setTitle('Цитаты')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('links'))
							->setSort(700)
							->setText('Ссылки')
							->setUrl('#')
							->setTitle('Ссылки')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('notes'))
							->setSort(800)
							->setText('Блокноты')
							->setUrl('#')
							->setTitle('Блокноты')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('rss_canals'))
							->setSort(900)
							->setText('Каналы RSS')
							->setUrl('#')
							->setTitle('Каналы RSS')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('radio_101ru'))
							->setSort(1000)
							->setText('Radio 101.Ru')
							->setUrl('#')
							->setTitle('Radio 101.Ru')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('telegram'))
							->setSort(1100)
							->setText('Telegram')
							->setUrl('#')
							->setTitle('Telegram')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('open_weather_map'))
							->setSort(1200)
							->setText('Погода от OpenWeatherMap')
							->setUrl('#')
							->setTitle('Погода от OpenWeatherMap')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)*/
			)
			->addGroup(
				(new MenuGroup('admin_main_menu_devices'))
					->setName('Устройства')
					->setCode('devices')
					->setIcon('/ms/images/ms_dobrozhil/devices_icon.png')
					->addElement(
						(new MenuElement('dev_online'))
							->setText('Устройства Online')
							->setUrl('dev/online/')
							->setTitle('Устройства Online')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-rocket')
					)
/*					->addElement(
						(new MenuElement('usb_dev'))
							->setText('USB-устройства')
							->setUrl('dev/usb/')
							->setTitle('USB-устройства')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-usb')
					)*/


			/*					->addElement(
						(new MenuElement('devices_setup'))
							->setSort(100)
							->setText('Настройки')
							->setUrl('#')
							->setTitle('Добавление и управление различными устройствами')
							->setIcon('/ms/images/ms_dobrozhil/devices_small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/devices_big_icon.png')
							->setFavicon('')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
							->addChildren(
								(new MenuElement('device_online'))
									->setSort(50)
									->setText('Устройства Online')
									->setUrl('#')
									->setTitle('Устройства Online')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
/*							->addChildren(
								(new MenuElement('usb_devices'))
									->setSort(70)
									->setText('USB-устройства')
									->setUrl('#')
									->setTitle('USB-устройства')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)* /

/*							->addChildren(
								(new MenuElement('modbus'))
									->setSort(200)
									->setText('ModBus')
									->setUrl('#')
									->setTitle('ModBus')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('mqtt'))
									->setSort(300)
									->setText('MQTT')
									->setUrl('#')
									->setTitle('MQTT')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('1wire'))
									->setSort(400)
									->setText('1-Wire')
									->setUrl('#')
									->setTitle('1-Wire')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('snmp'))
									->setSort(500)
									->setText('SNMP')
									->setUrl('#')
									->setTitle('SNMP')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('zwave'))
									->setSort(600)
									->setText('Z-Wave')
									->setUrl('#')
									->setTitle('Z-Wave')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('knx'))
									->setSort(700)
									->setText('KNX')
									->setUrl('#')
									->setTitle('KNX')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('megad'))
									->setSort(800)
									->setText('MegaD')
									->setUrl('#')
									->setTitle('MegaD')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('noolite'))
									->setSort(900)
									->setText('Noolite')
									->setUrl('#')
									->setTitle('Noolite')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)
							->addChildren(
								(new MenuElement('orvibo'))
									->setSort(1000)
									->setText('Orvibo')
									->setUrl('#')
									->setTitle('Orvibo')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
							)* /
					)*/
			)
			->addGroup(
				(new MenuGroup('admin_main_menu_setup'))
					->setName('Установки')
					->setCode('setup')
					->setIcon('/ms/images/ms_dobrozhil/setup_icon.png')
					->addElement(
						(new MenuElement('folders'))
							->setSort(85)
							->setText('Папки')
							->setUrl('#')
							->setTitle('Папки')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-folder-open')
					)
					->addElement(
						(new MenuElement('home_page'))
							->setSort(100)
							->setText('Домашние страницы')
							->setUrl('#')
							->setTitle('Домашние страницы')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-columns')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('place'))
							->setSort(200)
							->setText('Расположение')
							->setUrl('#')
							->setTitle('Расположение')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-home')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('blocks'))
							->setSort(300)
							->setText('Мои блоки')
							->setUrl('#')
							->setTitle('Мои блоки')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-cubes')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('security'))
							->setSort(400)
							->setText('Правила безопасности')
							->setUrl('#')
							->setTitle('Правила безопасности')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-video-camera camera')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('general'))
							->setSort(500)
							->setText('Общие настройки')
							->setUrl('#')
							->setTitle('Общие настройки')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-cog')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('sounds'))
							->setSort(600)
							->setText('Звуковые файлы')
							->setUrl('#')
							->setTitle('Звуковые файлы')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-microphone')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('terminals'))
							->setSort(700)
							->setText('Терминалы')
							->setUrl('#')
							->setTitle('Терминалы')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-address-card')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('text_files'))
							->setSort(800)
							->setText('Текстовые файлы')
							->setUrl('#')
							->setTitle('Текстовые файлы')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-clipboard')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('users'))
							->setSort(900)
							->setText('Пользователи')
							->setUrl('#')
							->setTitle('Пользователи')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-user')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
			)
			->addGroup(
				(new MenuGroup('admin_main_menu_system'))
					->setName('Система')
					->setCode('system')
					->setIcon('/ms/images/ms_dobrozhil/system_icon.png')
					->addElement(
						(new MenuElement('modules_settings'))
							->setSort(100)
							->setText('Настройки модулей')
							->setUrl('#')
							->setTitle('Настройки модулей')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-balance-scale')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
							->addChildren(
								(new MenuElement('ms.dates'))
									->setSort(100)
									->setText('Даты')
									->setUrl('#')
									->setTitle('Даты')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
									->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
							)
							->addChildren(
								(new MenuElement('ms.daemons'))
									->setSort(200)
									->setText('Демоны')
									->setUrl('#')
									->setTitle('Демоны')
									->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
									->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
									->setFavicon('')
									->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
							)
					)
					->addElement(
						(new MenuElement('install_uninstall_modules'))
							->setSort(150)
							->setText('Установка/удаление модулей')
							->setUrl('#')
							->setTitle('Установка/удаление модулей')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-soundcloud')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('backup'))
							->setSort(200)
							->setText('Резервное копирование')
							->setUrl('#')
							->setTitle('Резервное копирование')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-download')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('errors'))
							->setSort(300)
							->setText('Ошибки системы')
							->setUrl('#')
							->setTitle('Ошибки системы')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-recycle')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('logs'))
							->setSort(400)
							->setText('Журнал действий')
							->setUrl('#')
							->setTitle('Журнал действий')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-ticket')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
					->addElement(
						(new MenuElement('debug'))
							->setSort(500)
							->setText('Отладка')
							->setUrl('#')
							->setTitle('Отладка')
							->setIcon('/ms/images/ms_dobrozhil/small_icon.png')
							->setPageIcon('/ms/images/ms_dobrozhil/big_icon.png')
							->setFavicon('fa fa-stethoscope')
							->setShow('\Ms\Dobrozhil\Lib\Main::checkShowMenu')
					)
			)
		;

	}

	/**
	 * Возвращает массив с меню административной части, либо пустой массив
	 *
	 * @return array
	 */
	public function getAdminMenuArray ()
	{
		$arMenu = $arReturn = array();
        EventController::getInstance()->runEvents(
			'ms.dobrozhil',
			'OnBuildAdminMainMenu',
			array (&$arMenu)
		);
		if (!empty($arMenu))
		{
			foreach ($arMenu as $ar_menu)
			{
				if (self::checkSectionMenu($ar_menu['parent_menu']))
				{
					$arReturn['SORT'][$ar_menu['parent_menu']][(int)$ar_menu['sort']] = $ar_menu['items_id'];
					$arReturn['LIST'][$ar_menu['parent_menu']][$ar_menu['items_id']] = $ar_menu;
				}
			}
		}
		unset($arMenu);
		if (isset($arReturn['SORT']) && !empty($arReturn['SORT']))
		{
			foreach ($arReturn['SORT'] as $group=>&$arSort)
			{
				ksort($arSort);
			}
			unset($arSort);
		}

		return $arReturn;
	}

	/**
	 * Возвращает массив с параметрами основных разделов меню
	 *
	 * @return array
	 */
	public function getGeneralAdminMenuArray()
	{
		return array (
			'admin_main_menu_general' => array('NAME'=>'Главное','CODE'=>'general','ICON'=>'/ms/images/ms_dobrozhil/general_icon.png'),
			'admin_main_menu_apps' => array('NAME'=>'Приложения','CODE'=>'apps','ICON'=>'/ms/images/ms_dobrozhil/apps_icon.png'),
			'admin_main_menu_devices' => array('NAME'=>'Устройства','CODE'=>'devices','ICON'=>'/ms/images/ms_dobrozhil/devices_icon.png'),
			'admin_main_menu_setup' => array('NAME'=>'Установки','CODE'=>'setup','ICON'=>'/ms/images/ms_dobrozhil/setup_icon.png'),
			'admin_main_menu_system' => array('NAME'=>'Система','CODE'=>'system','ICON'=>'/ms/images/ms_dobrozhil/system_icon.png')
		);
	}

	/**
	 * Проверяет правильность указанной группы меню
	 *
	 * @param string $section Код группы меню
	 *
	 * @return bool
	 */
	public function checkSectionMenu ($section)
	{
		if (
			strtolower($section)=='admin_main_menu_general'
			|| strtolower($section)=='admin_main_menu_devices'
			|| strtolower($section)=='admin_main_menu_apps'
			|| strtolower($section)=='admin_main_menu_setup'
			|| strtolower($section)=='admin_main_menu_system'
		) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Проверяет, можно ли показывать указанному пользователю тот или иной пункт меню
	 *
	 * @param MenuGroup   $menuGroup   Объект группы меню
	 * @param MenuElement $menuElement Объект элемента меню
	 * @param User|NULL   $user        Объект пользователя
	 *
	 * @return bool
	 */
	public static function checkShowMenu (MenuGroup $menuGroup, MenuElement $menuElement, User $user=null)
	{
		if (is_null($user))
		{
			$user = Application::getInstance()->getUser();
		}

		if ($user->isAdmin())
		{
			return true;
		}
		elseif ($menuGroup->getKey()=='admin_main_menu_setup' && $menuElement->getElementID()=='users')
		{
			return true;
		}
		else
		{
			if ($menuGroup == 'admin_main_menu_general')
			{
				switch ($menuElement)
				{
					case 'objects':
						return (
						    Access::getInstance()->canView('CLASSES', $user->getID())
                            || Access::getInstance()->canViewOwn('CLASSES', $user->getID())
                        );
					case 'menu':
						return Access::getInstance()->canView('MENU_MENU', $user->getID());
					case 'patterns':
						return Access::getInstance()->canView('MENU_PATTERNS', $user->getID());
					case 'interface':
						return Access::getInstance()->canView('MENU_INTERFACE', $user->getID());
					case 'scripts':
						return Access::getInstance()->canView('MENU_SCRIPTS', $user->getID());
					case 'webvars':
						return Access::getInstance()->canView('MENU_WEBVARS', $user->getID());
					case 'vars':
						return Access::getInstance()->canView('MENU_VARS', $user->getID());
					case 'events':
						return Access::getInstance()->canView('MENU_EVENTS', $user->getID());
				}
			}
			elseif ($menuGroup == 'admin_main_menu_devices')
			{
				return true;
			}
			elseif ($menuGroup == 'admin_main_menu_apps')
			{

			}
			elseif ($menuGroup == 'admin_main_menu_setup')
			{

			}
			elseif ($menuGroup == 'admin_main_menu_system')
			{

			}
		}

		return false;
	}

	/**
	 * Возвращает строку, содержащую системный серийный номер оборудования
	 *
	 * @return bool|string
	 */
	public function getSystemSerial ()
	{
		$sysSerial = Options::getInstance()->getOptionString('system_serial','');
		if ($sysSerial == '')
		{
			$data = trim(exec("cat /proc/cpuinfo | grep Serial | cut -d '':'' -f 2"));
			if ($data == '') {
				$data = trim(exec("sudo cat /proc/cpuinfo | grep Serial | cut -d '':'' -f 2"));
			}
			$sysSerial = ltrim($data, '0');
			if (!$sysSerial) {
				$sysSerial = uniqid('dobrozhil');
			}
			Options::getInstance()->setOption('system_serial', $sysSerial);
		}

		return $sysSerial;
	}

	/**
	 * Возвращает ключ системы для работы с облаком
	 *
	 * @return bool|string
	 */
	public static function getSystemKey ()
	{
		$sHomeKey = Options::getInstance()->getOptionString
        (
            'ms_dobrozhil_home_key',
            ''
        )
        ;
		if ($sHomeKey == '')
		{
			$arPost = [
				'action' => 'get_key',
				'system_serial' => Main::getInstance()->getSystemSerial()
			];
			$res = Main::curlPost('https://cloud.dobrozhil.ru/rep/',$arPost);
			$arRes = json_decode($res,true);
			if (isset($arRes['status']) && $arRes['status'] == 'ok' && isset($arRes['sfh_key']))
			{
                Options::getInstance()->setOption('MS_DOBROZHIL_HOME_KEY',$arRes['sfh_key']);
				$sHomeKey = $arRes['sfh_key'];
			}
		}

		return $sHomeKey;
	}

	/**
	 * TODO: убедится в работоспособности
	 *
	 * @param        $url
	 * @param int    $cache
	 * @param string $username
	 * @param string $password
	 * @param bool   $bBackground
	 *
	 * @return bool|string
	 */
	public static function getURL($url, $cache = 0, $username = '', $password = '', $bBackground = false)
	{
		$app = Application::getInstance();
		$result = '';

		$filenamePart = preg_replace('/\W/is', '_', str_replace('http://', '', $url));
		if (strlen($filenamePart) > 200) {
			$filenamePart = substr($filenamePart, 0, 200) . md5($filenamePart);
		}

		$cachePath = $app->getSettings()->getMsRoot() . '/cached/urls';
		if (!file_exists($cachePath))
		{
			Files::createDir($cachePath);
		}
		$cacheFile = $cachePath . '/' . $filenamePart . '.html';
		if (!$cache || !is_file($cacheFile) || ((time() - filemtime($cacheFile)) > $cache))
		{
			try {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:32.0) Gecko/20100101 Firefox/32.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // connection timeout
				curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
				@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 45);  // operation timeout 45 seconds
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     // bad style, I know...
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				if ($bBackground) {
					curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
					curl_setopt($ch, CURLOPT_TIMEOUT_MS, 50);
				}
				if ($username != '' || $password != '') {
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
				}
				$arUrlParsed = parse_url($url);
				$host = $arUrlParsed['host'];
				$bUseProxy = Options::getInstance()->getOptionBool('use_proxy',0);
				$sProxyUrl = Options::getInstance()->getOptionString('proxy_url','');
				if ($host == '127.0.0.1' || $host == 'localhost') {
					$bUseProxy = false;
				}
				$sHomeNetwork = Options::getInstance()->getOptionString('home_network','');
				if ($bUseProxy && $sHomeNetwork != '') {
					$p = preg_quote($sHomeNetwork);
					$p = str_replace('\*', '\d+?', $p);
					$p = str_replace(',', ' ', $p);
					$p = str_replace('  ', ' ', $p);
					$p = str_replace(' ', '|', $p);
					if (preg_match('/' . $p . '/is', $host)) {
						$bUseProxy = false;
					}
				}
				if ($bUseProxy && $sProxyUrl != '') {
					curl_setopt($ch, CURLOPT_PROXY, $sProxyUrl);
					$bUseProxyAuth = Options::getInstance()->getOptionBool('use_proxy_auth',0);
					$sProxyUserPwd = Options::getInstance()->getOptionString('proxy_user_pwd','');
					if ($bUseProxyAuth && $sProxyUserPwd != '')
					{
						curl_setopt($ch, CURLOPT_PROXYUSERPWD, $sProxyUserPwd);
					}
				}
				$tmpfname = $app->getDocumentRoot() . 'ms/cached/cookie.txt';
				curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);

				$result = curl_exec($ch);

				if (!$bBackground && curl_errno($ch)) {
					$errorInfo = curl_error($ch);
					$info = curl_getinfo($ch);
					$backtrace = debug_backtrace();
					$callSource = $backtrace[1]['function'];
				}
				curl_close($ch);
			}
			catch (\Exception $e) {}
			if ($cache > 0)
			{
				Files::saveFile($cacheFile,$result);
			}
		}
		else {
			$result = Files::loadFile($cacheFile);
		}

		return $result;
	}

	/**
	 * Производит POST запрос по указанному URL и возвращает результат
	 *
	 * @param string $url       URL, куда будет отправлен запрос (включая протокол http или https)
	 * @param array  $arPost    Массив значений, которые будут переданы в POST-запросе
	 * @param string $username  Имя пользователя, необходимый для авторизации (если требуется)
	 * @param string $password  Пароль пользователя, необходимый для авторизации (если требуется)
	 *
	 * @return bool|string
	 */
	public static function curlPost ($url, $arPost, $username = '', $password = '')
	{
		$app = Application::getInstance();

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:32.0) Gecko/20100101 Firefox/32.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); // connection timeout
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);  // operation timeout 45 seconds
		curl_setopt($ch, CURLOPT_POSTFIELDS, $arPost);
		if (strpos($url, 'https')!==false)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);     // bad style, I know...
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
		if ($username != '' || $password != '') {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		}
		$tmpFileName = $app->getDocumentRoot() . 'ms/cached/cookie.txt';
		curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpFileName);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpFileName);

		// execute!
		$response = curl_exec($ch);

		// close the connection, release resources used
		curl_close($ch);

		return $response;
	}
}