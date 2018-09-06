<?php

namespace Ms\Dobrozhil\Lib;

class Handlers
{
	public static function onBuildAdminMainMenuHandler (&$arMenu)
	{
		/* GENERAL */

		//Меню управления
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Меню управления',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Настройка меню управления', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/menu_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/menu_big_icon.png", // большая иконка
			"show"        => "\Ms\Dobrozhil\Lib\Main::checkShowMenu", // метод проверки возможности отображения меню
			"items_id"    => "menu"  // идентификатор ветви
		);
		//Классы и объекты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'Классы и объекты',       // текст пункта меню
			"url"         => "objects/",  // ссылка на пункте меню
			"title"       => 'Управление классами и объектами системы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/objects_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/objects_big_icon.png", // большая иконка
			"items_id"    => "objects"  // идентификатор ветви
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
			"items_id"    => "patterns"  // идентификатор ветви
		);
		//Сцены
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_general", // поместим в раздел "Основное"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Сцены',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Управление сценами', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/scene_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/scene_big_icon.png", // большая иконка
			"items_id"    => "scene"  // идентификатор ветви
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
			"items_id"    => "webvars"  // идентификатор ветви
		);

		/* DEVICES */

		//Bluetooth-устройства
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Bluetooth-устройства',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Добавление и управление bluetooth-устройствами', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/bluetooth_small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/bluetooth_big_icon.png", // большая иконка
			"items_id"    => "bluetooth"  // идентификатор ветви
		);
		//ModBus
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'ModBus',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'ModBus', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "modbus"  // идентификатор ветви
		);
		//MQTT
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'MQTT',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'MQTT', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "mqtt"  // идентификатор ветви
		);
		//1-Wire
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => '1-Wire',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => '1-Wire', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "1wire"  // идентификатор ветви
		);
		//Устройства Online
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 50,                    // сортировка пункта меню
			"text"        => 'Устройства Online',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Устройства Online', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "device_online"  // идентификатор ветви
		);
		//SNMP
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'SNMP',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'SNMP', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "snmp"  // идентификатор ветви
		);
		//USB-устройства
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 70,                    // сортировка пункта меню
			"text"        => 'USB-устройства',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'USB-устройства', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "usb_devices"  // идентификатор ветви
		);
		//Папки
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 85,                    // сортировка пункта меню
			"text"        => 'Папки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Папки', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "folders"  // идентификатор ветви
		);
		//Z-Wave
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 600,                    // сортировка пункта меню
			"text"        => 'Z-Wave',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Z-Wave', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "zwave"  // идентификатор ветви
		);
		//KNX
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 700,                    // сортировка пункта меню
			"text"        => 'KNX',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'KNX', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "knx"  // идентификатор ветви
		);
		//MegaD
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 800,                    // сортировка пункта меню
			"text"        => 'MegaD',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'MegaD', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "megad"  // идентификатор ветви
		);
		//Noolite
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 900,                    // сортировка пункта меню
			"text"        => 'Noolite',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Noolite', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "noolite"  // идентификатор ветви
		);
		//Orvibo
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_devices", // поместим в раздел "Устройства"
			"sort"        => 1000,                    // сортировка пункта меню
			"text"        => 'Orvibo',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Orvibo', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "orvibo"  // идентификатор ветви
		);

		/* APPS */

		//Календарь
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Календарь',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Календарь', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "calender"  // идентификатор ветви
		);
		//GPS-трекер
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'GPS-трекер',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'GPS-трекер', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "gps_tracker"  // идентификатор ветви
		);
		//Медиа
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'Медиа',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Медиа', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "media"  // идентификатор ветви
		);
		//Плеер
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Плеер',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Плеер', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "pleer"  // идентификатор ветви
		);
		//Продукты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'Продукты',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Продукты', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "shop"  // идентификатор ветви
		);
		//Цитаты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 600,                    // сортировка пункта меню
			"text"        => 'Цитаты',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Цитаты', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "says"  // идентификатор ветви
		);
		//Ссылки
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 700,                    // сортировка пункта меню
			"text"        => 'Ссылки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Ссылки', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "links"  // идентификатор ветви
		);
		//Блокноты
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 800,                    // сортировка пункта меню
			"text"        => 'Блокноты',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Блокноты', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "notes"  // идентификатор ветви
		);
		//Каналы RSS
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 900,                    // сортировка пункта меню
			"text"        => 'Каналы RSS',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Каналы RSS', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "rss_canals"  // идентификатор ветви
		);
		//Radio 101.Ru
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 1000,                    // сортировка пункта меню
			"text"        => 'Radio 101.Ru',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Radio 101.Ru', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "radio_101ru"  // идентификатор ветви
		);
		//Telegram
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 1100,                    // сортировка пункта меню
			"text"        => 'Telegram',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Telegram', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "telegram"  // идентификатор ветви
		);
		//Погода от OpenWeatherMap
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_apps", // поместим в раздел "Приложения"
			"sort"        => 1300,                    // сортировка пункта меню
			"text"        => 'Погода от OpenWeatherMap',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Погода от OpenWeatherMap', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "open_weather_map"  // идентификатор ветви
		);

		/* SETUP */

		//Домашние страницы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Домашние страницы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Домашние страницы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "home_page"  // идентификатор ветви
		);
		//Расположение
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'Расположение',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Расположение', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "place"  // идентификатор ветви
		);
		//Мои блоки
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'Мои блоки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Мои блоки', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "blocks"  // идентификатор ветви
		);
		//Правила безопасности
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Правила безопасности',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Правила безопасности', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "security"  // идентификатор ветви
		);
		//Общие настройки
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'Общие настройки',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Общие настройки', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "general"  // идентификатор ветви
		);
		//Звуковые файлы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 600,                    // сортировка пункта меню
			"text"        => 'Звуковые файлы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Звуковые файлы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "sounds"  // идентификатор ветви
		);
		//Терминалы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 700,                    // сортировка пункта меню
			"text"        => 'Терминалы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Терминалы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "terminals"  // идентификатор ветви
		);
		//Текстовые файлы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 800,                    // сортировка пункта меню
			"text"        => 'Текстовые файлы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Текстовые файлы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "text_files"  // идентификатор ветви
		);
		//Пользователи
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_setup", // поместим в раздел "Установки"
			"sort"        => 900,                    // сортировка пункта меню
			"text"        => 'Пользователи',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Пользователи', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "users"  // идентификатор ветви
		);

		/* SYSTEM */

		//Модули
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 100,                    // сортировка пункта меню
			"text"        => 'Модули',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Модули', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "modules"  // идентификатор ветви
		);
		//Резервное копирование
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 200,                    // сортировка пункта меню
			"text"        => 'Резервное копирование',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Резервное копирование', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "backup"  // идентификатор ветви
		);
		//Ошибки системы
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 300,                    // сортировка пункта меню
			"text"        => 'Ошибки системы',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Ошибки системы', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "errors"  // идентификатор ветви
		);
		//Журнал действий
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 400,                    // сортировка пункта меню
			"text"        => 'Журнал действий',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'Журнал действий', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "logs"  // идентификатор ветви
		);
		//X-Ray
		$arMenu[] = array(
			"parent_menu"   => "admin_main_menu_system", // поместим в раздел "Система"
			"sort"        => 500,                    // сортировка пункта меню
			"text"        => 'X-Ray',       // текст пункта меню
			"url"         => "#",  // ссылка на пункте меню
			"title"       => 'X-Ray', // текст всплывающей подсказки
			"icon"        => "/ms/images/ms_dobrozhil/small_icon.png", // малая иконка
			"page_icon"   => "/ms/images/ms_dobrozhil/big_icon.png", // большая иконка
			"items_id"    => "xray"  // идентификатор ветви
		);
	}

}