<?php

namespace Ms\Dobrozhil\Lib;

class Handlers
{
	public static function onBuildAdminMainMenuHandler (&$arMenu)
	{
		/* GENERAL */

		//Классы и объекты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Объекты',       // текст пункта меню
			"url"         => "objects/",  // ссылка на пункте меню
			"title"       => 'Управление классами и объектами системы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/objects_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/objects_big_icon.png", // большая иконка
			"favicon"     => 'fa fa-th', // фавиконка
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			"items_id"    => "objects"  // идентификатор ветви
		);
		//Меню управления
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'Меню управления',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Настройка меню управления', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/menu_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/menu_big_icon.png", // большая иконка
			"favicon"     => 'fa fa-tasks', // фавиконка
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			"items_id"    => "menu"  // идентификатор ветви
		);
		//Шаблоны поведения
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'Шаблоны поведения',       // текст пункта меню
			"url"         => "patterns/",  // ссылка на пункте меню
			"title"       => 'Создание шаблонов поведения', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/patterns_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/patterns_big_icon.png", // большая иконка
			"favicon"     => 'fa fa-commenting-o', // фавиконка
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			"items_id"    => "patterns"  // идентификатор ветви
		);
		//Интерфейсы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Интерфейсы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Управление интерфейсами', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/scene_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/scene_big_icon.png", // большая иконка
			"favicon"     => 'fa fa-laptop', // фавиконка
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			"items_id"    => "interface"  // идентификатор ветви
		);
		//Скрипты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'Скрипты',       // текст пункта меню
			"url"         => "scripts/",  // ссылка на пункте меню
			"title"       => 'Создание и управление всеми скриптами', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/scripts_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/scripts_big_icon.png", // большая иконка
			"favicon"     => 'fa fa-edit', // фавиконка
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			"items_id"    => "scripts"  // идентификатор ветви
		);
		//Веб-переменные
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 600,                    // сортировка пункта меню
			"text"        => 'Веб-переменные',       // текст пункта меню
			"url"         => "webvars/",  // ссылка на пункте меню
			"title"       => 'Создание и управление веб-переменными', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/webvars_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/webvars_big_icon.png", // большая иконка
			"favicon"     => 'fa fa-share-alt', // фавиконка
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			"items_id"    => "webvars"  // идентификатор ветви
		);
        //Переменные
        $arMenu[] = array(
            "parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
            "sort"        => 700,                    // сортировка пункта меню
            "text"        => 'Переменные',       // текст пункта меню
            "url"         => "vars/",  // ссылка на пункте меню
            "title"       => 'Создание и изменение переменных', // текст всплывающей подсказки
            "icon"        => "/ms/images/ms_dobrozhil/webvars_small_icon.png", // малая иконка
            "page_icon"   => "/ms/images/ms_dobrozhil/webvars_big_icon.png", // большая иконка
            "favicon"     => 'fa fa-share-alt', // фавиконка
            "show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
            "items_id"    => "vars"  // идентификатор ветви
        );
        //События
        $arMenu[] = array(
            "parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
            "sort"        => 800,                    // сортировка пункта меню
            "text"        => 'События',       // текст пункта меню
            "url"         => "events/",  // ссылка на пункте меню
            "title"       => 'Управление событиями, создание обработчиков', // текст всплывающей подсказки
            "icon"        => "/ms/images/ms_dobrozhil/webvars_small_icon.png", // малая иконка
            "page_icon"   => "/ms/images/ms_dobrozhil/webvars_big_icon.png", // большая иконка
            "favicon"     => 'fa fa-share-alt', // фавиконка
            "show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
            "items_id"    => "events"  // идентификатор ветви
        );

		/* DEVICES */

		//Управление устройствами
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Настройки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Добавление и управление различными устройствами', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/devices_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/devices_big_icon.png", // большая иконка
			"favicon"     => 'fa fa-rocket', // фавиконка
			"items_id"    => "devices",  // идентификатор ветви
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			'children'    => array(
				//Bluetooth-устройства
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 100,                    // сортировка пункта меню
					"text"        => 'Bluetooth-устройства',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Добавление и управление bluetooth-устройствами', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/bluetooth_small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/bluetooth_big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "bluetooth"  // идентификатор ветви
				),
				//ModBus
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 200,                    // сортировка пункта меню
					"text"        => 'ModBus',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'ModBus', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "modbus"  // идентификатор ветви
				),
				//MQTT
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 300,                    // сортировка пункта меню
					"text"        => 'MQTT',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'MQTT', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "mqtt"  // идентификатор ветви
				),
				//1-Wire
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 400,                    // сортировка пункта меню
					"text"        => '1-Wire',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => '1-Wire', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "1wire"  // идентификатор ветви
				),
				//Устройства Online
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 50,                    // сортировка пункта меню
					"text"        => 'Устройства Online',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Устройства Online', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "device_online"  // идентификатор ветви
				),
				//SNMP
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 500,                    // сортировка пункта меню
					"text"        => 'SNMP',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'SNMP', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "snmp"  // идентификатор ветви
				),
				//USB-устройства
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 70,                    // сортировка пункта меню
					"text"        => 'USB-устройства',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'USB-устройства', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "usb_devices"  // идентификатор ветви
				),
				//Папки
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 85,                    // сортировка пункта меню
					"text"        => 'Папки',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Папки', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "folders"  // идентификатор ветви
				),
				//Z-Wave
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 600,                    // сортировка пункта меню
					"text"        => 'Z-Wave',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Z-Wave', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "zwave"  // идентификатор ветви
				),
				//KNX
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 700,                    // сортировка пункта меню
					"text"        => 'KNX',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'KNX', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "knx"  // идентификатор ветви
				),
				//MegaD
				$arMenu[] = array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 800,                    // сортировка пункта меню
					"text"        => 'MegaD',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'MegaD', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "megad"  // идентификатор ветви
				),
				//Noolite
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 900,                    // сортировка пункта меню
					"text"        => 'Noolite',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Noolite', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "noolite"  // идентификатор ветви
				),
				//Orvibo
				array(
					"parent_menu"   => "devices", // поместим в раздел "Устройства"
					"sort"        => 1000,                    // сортировка пункта меню
					"text"        => 'Orvibo',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Orvibo', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "orvibo"  // идентификатор ветви
				)
			)
		);


		/* APPS */

		//Календарь
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Календарь',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Календарь', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => 'fa fa-calendar', // фавиконка
			"items_id"    => "calender"  // идентификатор ветви
		);
		//GPS-трекер
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'GPS-трекер',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'GPS-трекер', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "gps_tracker"  // идентификатор ветви
		);
		//Медиа
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'Медиа',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Медиа', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "media"  // идентификатор ветви
		);
		//Плеер
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Плеер',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Плеер', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "pleer"  // идентификатор ветви
		);
		//Продукты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'Продукты',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Продукты', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "shop"  // идентификатор ветви
		);
		//Цитаты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 600,                    // сортировка пункта меню
			"text"        => 'Цитаты',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Цитаты', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "says"  // идентификатор ветви
		);
		//Ссылки
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 700,                    // сортировка пункта меню
			"text"        => 'Ссылки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Ссылки', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "links"  // идентификатор ветви
		);
		//Блокноты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 800,                    // сортировка пункта меню
			"text"        => 'Блокноты',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Блокноты', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "notes"  // идентификатор ветви
		);
		//Каналы RSS
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 900,                    // сортировка пункта меню
			"text"        => 'Каналы RSS',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Каналы RSS', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "rss_canals"  // идентификатор ветви
		);
		//Radio 101.Ru
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 1000,                    // сортировка пункта меню
			"text"        => 'Radio 101.Ru',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Radio 101.Ru', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "radio_101ru"  // идентификатор ветви
		);
		//Telegram
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 1100,                    // сортировка пункта меню
			"text"        => 'Telegram',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Telegram', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "telegram"  // идентификатор ветви
		);
		//Погода от OpenWeatherMap
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 1300,                    // сортировка пункта меню
			"text"        => 'Погода от OpenWeatherMap',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Погода от OpenWeatherMap', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "open_weather_map"  // идентификатор ветви
		);

		/* SETUP */

		//Домашние страницы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Домашние страницы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Домашние страницы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => 'fa fa-columns', // фавиконка
			"items_id"    => "home_page"  // идентификатор ветви
		);
/*		//Расположение
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'Расположение',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Расположение', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "place"  // идентификатор ветви
		);
		//Мои блоки
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'Мои блоки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Мои блоки', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "blocks"  // идентификатор ветви
		);*/
		//Правила безопасности
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Правила безопасности',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Правила безопасности', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => 'fa fa-video-camera camera', // фавиконка
			"items_id"    => "security"  // идентификатор ветви
		);
		//Общие настройки
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'Общие настройки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Общие настройки', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => 'fa fa-cog', // фавиконка
			"items_id"    => "general"  // идентификатор ветви
		);
		//Звуковые файлы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 600,                    // сортировка пункта меню
			"text"        => 'Звуковые файлы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Звуковые файлы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "sounds"  // идентификатор ветви
		);
		//Терминалы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 700,                    // сортировка пункта меню
			"text"        => 'Терминалы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Терминалы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => 'fa fa-address-card', // фавиконка
			"items_id"    => "terminals"  // идентификатор ветви
		);
		//Текстовые файлы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 800,                    // сортировка пункта меню
			"text"        => 'Текстовые файлы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Текстовые файлы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "text_files"  // идентификатор ветви
		);
		//Пользователи
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 900,                    // сортировка пункта меню
			"text"        => 'Пользователи',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Пользователи', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "users"  // идентификатор ветви
		);

		/* SYSTEM */

		//Настройки модулей
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Настройки модулей',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Настройки модулей', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "modules_settings",  // идентификатор ветви
			'children'    => array(
				array(
					"parent_menu"   => "modules_settings", // поместим в раздел "Система"
					"sort"        => 100,                    // сортировка пункта меню
					"text"        => 'Даты',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Даты', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "ms.dates",  // идентификатор ветви
				),
				array(
					"parent_menu"   => "modules_settings", // поместим в раздел "Система"
					"sort"        => 200,                    // сортировка пункта меню
					"text"        => 'Демоны',       // текст пункта меню
					"url"         => "#",  // ссылка на пункте меню
					"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
					"title"       => 'Демоны', // текст всплывающей подсказки
					"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
					"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
					"favicon"     => '', // фавиконка
					"items_id"    => "ms.daemons",  // идентификатор ветви
				)
			)
		);
		//Установка/удаление модулей
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 150,                    // сортировка пункта меню
			"text"        => 'Установка/удаление модулей',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Установка/удаление модулей', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "install_uninstall_modules"  // идентификатор ветви
		);
		//Резервное копирование
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'Резервное копирование',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Резервное копирование', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "backup"  // идентификатор ветви
		);
		//Ошибки системы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'Ошибки системы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Ошибки системы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "errors"  // идентификатор ветви
		);
		//Журнал действий
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Журнал действий',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Журнал действий', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => 'fa fa-ticket', // фавиконка
			"items_id"    => "logs"  // идентификатор ветви
		);
		//Отладка
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'Отладка',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"add_links"   => array(), //дополнительные ссылки, при которых пункт меню активен
			"title"       => 'Отладка', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"favicon"     => '', // фавиконка
			"items_id"    => "debug"  // идентификатор ветви
		);
	}

}