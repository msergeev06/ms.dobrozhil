<?php
/**
 * Класс для работы с классами
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Modules;
use Ms\Dobrozhil\Entity\Script;
use Ms\Dobrozhil\Tables\ClassesTable;
use Ms\Dobrozhil\Tables\ClassMethodsTable;
use Ms\Dobrozhil\Tables\ClassPropertiesTable;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

/**
 * Class Classes
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 */
class Classes
{
	/**
	 * Именем класса может быть любое слово, которое начинается с буквы или
	 * символа подчеркивания и за которым следует любое количество букв, цифр
	 * или символов подчеркивания.
	 */
//	const NAME_REGULAR = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
	const NAME_REGULAR = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';

	/**
	 * @var null|ErrorCollection
	 */
	private static $errorCollection = null;

	/**
	 * @var array
	 */
	private static $arClassExists = array();

	/**
	 * @var array
	 */
	private static $arClassMethodsExists = array ();

	//<editor-fold defaultstate="collapsed" desc="Check & isset methods">
	/* CHECKS */

	/**
	 * Проверяет правильность имени класса
	 *
	 * @param string $className
	 *
	 * @return bool
	 */
	public static function checkName ($className)
	{
		if (!isset($className) || is_null($className)) return false;

		return (!!preg_match(self::NAME_REGULAR,$className));
	}

	/**
	 * Проверяет правильность имени свойства класса
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	public static function checkPropertyName ($sPropertyName)
	{
		return static::checkName($sPropertyName);
	}

	/**
	 * Проверяет правильность имени метода класса
	 *
	 * @param string $sMethodName Имя метода
	 *
	 * @return bool
	 */
	public static function checkMethodName ($sMethodName)
	{
		return static::checkName($sMethodName);
	}
	/**
	 * Проверяет, существует ли указанный класс
	 *
	 * @param string $sClassName Имя класса
	 * @param bool   $bUpdate    Флаг принудительного получения данных из БД
	 *
	 * @return bool
	 */
	public static function checkClassExists ($sClassName, $bUpdate=false)
	{
		//Проверяем имя класса
		if (!isset($sClassName))
		{
			//'Не указано название класса'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_name'
				),
				'NO_NAME'
			);
			return false;
		}
		elseif (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы',
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!isset(static::$arClassExists[$sClassName]) || $bUpdate)
		{
			$arRes = ClassesTable::getOne(
				array(
					'select' => 'NAME',
					'filter' => array('NAME'=>$sClassName)
				)
			);

			static::$arClassExists[$sClassName] = (!!$arRes);
		}

		return static::$arClassExists[$sClassName];
	}

	/**
	 * Проверяет существование свойства класса
	 *
	 * @param string $sClassName    Имя класса
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	public static function checkClassPropertyExists ($sClassName,$sPropertyName)
	{
		//Проверяем имя класса
		if (!isset($sClassName))
		{
			//'Не указано название класса'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_name'
				),
				'NO_NAME'
			);
			return false;
		}
		elseif (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы',
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем #CLASS_NAME# не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}
		elseif (!Classes::checkPropertyName($sPropertyName))
		{
			//'Имя свойства "#PROPERTY_NAME#" класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_name_wrong_symbols',
					array (
						'PROPERTY_NAME' => $sPropertyName,
						'CLASS_NAME' => $sClassName
					)
				),
				'PROPERTY_NAME_WRONG_SYMBOLS'
			);
			return false;
		}
		else
		{
			$arRes = ClassPropertiesTable::getOne(
				array(
					'select' => 'NAME',
					'filter' => array (
						'NAME' => $sClassName.'.'.$sPropertyName
					)
				)
			);

			return (!!$arRes);
		}
	}

	/**
	 * Проверяет существования метода указанного класса
	 *
	 * @param string $sClassName Имя класса
	 * @param string $sMethodName Имя метода
	 * @param bool   $bUpdate Флаг принудтельной проверки в БД
	 *
	 * @return bool Возвращает TRUE, если метод существует, FALSE в противном случае
	 */
	public static function issetClassMethod ($sClassName, $sMethodName, $bUpdate=false)
	{
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array ('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		if (!isset($sMethodName))
		{
			//'Имя метода не задано'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_method_no_name'
				),
				'METHOD_NO_NAME'
			);
			return false;
		}
		elseif (!static::checkName($sMethodName))
		{
			//'Имя метода "#METHOD_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_method_wrong_symbols',
					array ('METHOD_NAME'=>$sMethodName)
				),
				'METHOD_WRONG_SYMBOLS'
			);
			return false;
		}

		if (!isset(static::$arClassMethodsExists[$sClassName.'.'.$sMethodName]) || $bUpdate)
		{
			$arRes = ClassMethodsTable::getOne(
				array(
					'select' => 'NAME',
					'filter'=> array('NAME'=>$sClassName.'.'.$sMethodName)
				)
			);

			static::$arClassMethodsExists[$sClassName.'.'.$sMethodName] = (!!$arRes);
		}

		return static::$arClassMethodsExists[$sClassName.'.'.$sMethodName];
	}

	/**
	 * Проверяет существование заданного свойства в заданном классе
	 *
	 * @param string $sClassName    Имя класса
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	public static function issetClassProperty ($sClassName, $sPropertyName)
	{
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array ('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		if (!static::checkPropertyName($sPropertyName))
		{
			//'Имя свойства "#PROPERTY_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols',
					array ('PROPERTY_NAME'=>$sPropertyName)
				),
				'PROPERTY_WRONG_SYMBOLS'
			);
			return false;
		}

		$arRes = ClassPropertiesTable::getOne(array(
			'filter' => array('NAME'=>$sClassName.'.'.$sPropertyName)
		));

		return (!!$arRes);
	}

	/**
	 * Проверяет правильность заполнения поля с именем класса, для добавления класса
	 *
	 * @param string $sClassName Имя класса
	 *
	 * @return bool|string
	 */
	public static function checkClassAddNameField ($sClassName)
	{
		if (!static::checkName($sClassName))
		{
			return 'Имя класса содержит недопустимые символы';
		}
		elseif (static::checkClassExists($sClassName))
		{
			return 'Класс с данным именем уже существует';
		}
		else
		{
			return true;
		}
	}

	/**
	 * Проверяет правильность заполнения поля с именем класса, для редактирования класса
	 *
	 * @param string $sClassName Имя класса
	 *
	 * @return bool|string
	 */
	public static function checkClassEditNameField ($sClassName)
	{
		if (!static::checkName($sClassName))
		{
			return 'Имя класса содержит недопустимые символы';
		}
		else
		{
			return true;
		}
	}


	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Add methods">
	/* ADD */

	/**
	 * Добавляет новый класс
	 *
	 * @param array $arParams Массив параметров класса
	 *
	 * @return bool
	 */
	public static function addNewClass (array $arParams)
	{
		//TODO: Переделать с массива на параметры
		$arAdd = array();

		//Проверяем имя класса
		if (!isset($arParams['NAME']))
		{
			//'Не указано название класса'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_name'
				),
				'NO_NAME'
			);
			return false;
		}
		elseif (!static::checkName($arParams['NAME']))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы',
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$arParams['NAME'])
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		else
		{
			if (static::checkClassExists($arParams['NAME']))
			{
				//'Класс с именем "#CLASS_NAME#" уже существует'
				static::addError(
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_class_exists',
						array ('CLASS_NAME'=>$arParams['NAME'])
					),
					'CLASS_EXISTS'
				);
				return false;
			}
			else
			{
				$arAdd['NAME'] = $arParams['NAME'];
			}
		}

		//Сохраняем сортировку
		if (isset($arParams['SORT']))
		{
			$arAdd['SORT'] = (int)$arParams['SORT'];
		}

		//Сохраняем описание
		if (isset($arParams['NOTE']))
		{
			$arAdd['NOTE'] = $arParams['NOTE'];
		}

		//Проверяем класс - родитель
		if (!isset($arParams['PARENT_CLASS']))
		{
			$arAdd['PARENT_CLASS'] = null;
		}
		elseif (!static::checkName($arParams['PARENT_CLASS']))
		{
			//'Имя родительского класса "#CLASS_NAME#" содержит недопустимые символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_parent_wring_symbols',
					array('CLASS_NAME'=>$arParams['PARENT_CLASS'])
				),
				'PARENT_WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($arParams['PARENT_CLASS']))
		{
			//'Родительского класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_parent_class_no_exists',
					array ('CLASS_NAME'=>$arParams['PARENT_CLASS'])
				),
				'PARENT_NO_EXISTS'
			);
			return false;
		}
		else
		{
			$arAdd['PARENT_CLASS'] = $arParams['PARENT_CLASS'];
			$arAdd['PARENT_LIST'] = self::getParentsList($arAdd['PARENT_CLASS']);
		}

		//Проверяем модуль
		if (isset($arParams['MODULE']))
		{
			if (!Modules::checkModuleName($arParams['MODULE']))
			{
				//'Неверное имя модуля "#MODULE_NAME#"'
				static::addError(
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_wrong_module_name',
						array('MODULE_NAME'=>$arParams['MODULE'])
					),
					'WRONG_MODULE_NAME'
				);
				return false;
			}
			elseif (!Loader::issetModule($arParams['MODULE']))
			{
				//'Модуль "#MODULE_NAME#" не установлен'
				static::addError(
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_module_not_install',
						array('MODULE_NAME'=>$arParams['MODULE'])
					),
					'MODULE_NOT_INSTALL'
				);
				return false;
			}
			else
			{
				$arAdd['MODULE'] = $arParams['MODULE'];
				if (!Loader::includeModule($arParams['MODULE']))
				{
					//'Ошибка подключения модуля "#MODULE_NAME#"'
					static::addError(
						Loc::getModuleMessage(
							'ms.dobrozhil',
							'error_module_not_include',
							array ('MODULE_NAME'=>$arParams['MODULE'])
						),
						'MODULE_NOT_INCLUDE'
					);
					return false;
				}

				//Проверяем класс модуля
				if (!isset($arParams['NAMESPACE']))
				{
					//'Не указано имя класса'
					static::addError(
						Loc::getModuleMessage(
							'ms.dobrozhil',
							'error_module_no_namespace'
						),
						'MODULE_NO_NAMESPACE'
					);
					return false;
				}
				elseif (!Loader::classExists($arParams['NAMESPACE']))
				{
					//'Класс "#CLASS_NAME#" не существует среди автозагружаемых классов модуля "#MODULE_NAME#"'
					static::addError(
						Loc::getModuleMessage(
							'ms.dobrozhil',
							'error_module_class_no_autoload',
							array(
								'CLASS_NAME'=>$arParams['NAMESPACE'],
								'MODULE_NAME'=>$arParams['MODULE']
							)
						),
						'MODULE_CLASS_NO_AUTOLOAD'
					);
					return false;
				}
				else
				{
					$arAdd['NAMESPACE'] = $arParams['NAMESPACE'];
					$arAdd['TYPE'] = 'P';
				}
			}
		}

		$res = ClassesTable::add($arAdd);
		if (!$res->getResult())
		{
			//'Ошибка добавления нового класса "#CLASS_NAME#"'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_add',
					array('CLASS_NAME'=>$arParams['NAME'])
				),
				'NO_ADD'
			);
			return false;
		}

		return true;
	}

	/**
	 * Добавляет новое свойство в указанный класс
	 *
	 * @param string $sClassName           Имя класса
	 * @param string $sName                Имя свойства
	 * @param string $sNote                Описание свойства
	 * @param string $sType                Тип свойства
	 * @param int    $iHistory             Сколько дней хранить историю (0 - не хранить)
	 * @param bool   $bSaveIdenticalValues Записывать одинаковые значения свойства
	 *
	 * @return bool
	 */
	public static function addNewProperty ($sClassName, $sName, $sNote=null, $sType=null, $iHistory=0,$bSaveIdenticalValues=false)
	{
		$arAdd = array();
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		if (!isset($sName))
		{
			//'Имя свойства не задано'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_no_name'
				),
				'PROPERTY_NO_NAME'
			);
			return false;
		}
		elseif (!static::checkPropertyName($sName))
		{
			//'Имя свойства "#PROPERTY_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols',
					array('PROPERTY_NAME'=>$sName)
				),
				'PROPERTY_WRONG_SYMBOLS'
			);
			return false;
		}
		else
		{
			$arAdd['NAME'] = $sClassName.'.'.$sName;
			$arAdd['PROPERTY_NAME'] = $sName;
			$arAdd['CLASS_NAME'] = $sClassName;
		}

		if (!is_null($sNote))
		{
			$arAdd['NOTE'] = $sNote;
		}

		if (!is_null($sType))
		{
			$arAdd['TYPE'] = strtoupper($sType);
		}

		if ((int)$iHistory>=0)
		{
			$arAdd['HISTORY'] = (int)$iHistory;
		}

		if ($bSaveIdenticalValues === true)
		{
			$arAdd['SAVE_IDENTICAL_VALUES'] = true;
		}

		$res = ClassPropertiesTable::add($arAdd);
		if (!$res->getResult())
		{
			//'Не удалось добавить новое свойство "#PROPERTY_NAME#"'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_not_add',
					array('PROPERTY_NAME'=>$sName)
				),
				'PROPERTY_NOT_ADD'
			);
			return false;
		}

		return true;
	}

	/**
	 * Добавляет новый метод класса
	 *
	 * @param string      $sClassName  Имя класса
	 * @param string      $sMethodName Имя метода
	 * @param null|string $sCode       Код метода
	 * @param null|string $sNote       Описание метода
	 *
	 * @return bool
	 */
	public static function addNewMethod ($sClassName, $sMethodName, $sCode=null, $sNote=null)
	{
		$arAdd = array();
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array ('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array ('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		if (!isset($sMethodName))
		{
			//'Имя метода не задано'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_method_no_name'
				),
				'METHOD_NO_NAME'
			);
			return false;
		}
		elseif (!static::checkName($sMethodName))
		{
			//'Имя метода "#METHOD_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_method_wrong_symbols',
					array('METHOD_NAME'=>$sMethodName)
				),
				'METHOD_WRONG_SYMBOLS'
			);
			return false;
		}
		else
		{
			$arAdd['NAME'] = $sClassName.'.'.$sMethodName;
			$arAdd['METHOD_NAME'] = $sMethodName;
			$arAdd['CLASS_NAME'] = $sClassName;
		}

		define ('MS_DOBROZHIL_SYSTEM_SET',true);
		Scripts::addScript($sClassName.'.'.$sMethodName,1);
		if (!is_null($sCode))
		{
			$script = new Script($sClassName.'.'.$sMethodName);
			if (!$script->isError())
			{
				$script->saveCode($sCode);
			}
		}

		if (!is_null($sNote))
		{
			$arAdd['NOTE'] = $sNote;
		}

		$res = ClassMethodsTable::add($arAdd);
		if (!$res->getResult())
		{
			if (Scripts::issetScript($sClassName.'.'.$sMethodName))
			{
				Scripts::deleteScript($sClassName.'.'.$sMethodName);
			}
			//Не удалось добавить новый метод "#METHOD_NAME#" класса "#CLASS_NAME#"
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_add_method',
					array ('METHOD_NAME'=>$sMethodName,'CLASS_NAME'=>$sClassName)
				),
				'METHOD_NOT_ADD'
			);
			return false;
		}
		else
		{
			return true;
		}
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Get methods">
	/* GETS */

	/* *
	 * @param int $parentID
	 *
	 * @return string
	 * /
	public static function getTreeView ($parentID=0)
	{

		//<editor-fold desc="Trash">
		global $USER;
		$adminDir = CoreLib\Loader::getSitePublic('kuzmahome').'admin/';
		$html = '';

		$arClasses = Tables\ClassesTable::getList(
			array(
				'select' => array('ID','TITLE','DESCRIPTION'),
				'filter' => array('PARENT_ID'=>intval($parentID)),
				'order' => array('TITLE'=>'ASC')
			)
		);
		//</editor-fold>
		if ($arClasses)
		{
			$html.='<table class="table"><tbody>';

			foreach ($arClasses as $arClass)
			{
				$bNoObjects = false;
				$arObjects = Tables\ObjectsTable::getList(
					array(
						'select' => array('ID','TITLE','DESCRIPTION'),
						'filter' => array('CLASS_ID'=>intval($arClass['ID'])),
						'order' => array('TITLE'=>'ASC')
					)
				);
				if (!$arObjects)
				{
					$bNoObjects = true;
				}
				//Начало описания класса
				$html.='<tr';
				$html.='><td valign="top">';

				$html.='<a href="#" id="link-'.$arClass['ID'].'"';
				if (
					$USER->issetUserCookie('classes-view-'.$arClass['ID'])===true
					&& intval($USER->getUserCookie('classes-view-'.$arClass['ID']))==1
				)
				{
					$html.=' data-comm="hide"';
				}
				else
				{
					$html.=' data-comm="show"';
				}
				$html.=' onclick="return showHideClasses('.$arClass['ID'].');"';
				$html.=' class="show-hide-link btn btn-default btn-sm expand">';
				if (
					$USER->issetUserCookie('classes-view-'.$arClass['ID'])===true
					&& intval($USER->getUserCookie('classes-view-'.$arClass['ID']))==1
				)
				{
					$html.='-';
				}
				else
				{
					$html.='+';
				}
				$html.='</a><b>'.$arClass['TITLE'].'</b>';

				if (strlen($arClass['DESCRIPTION'])>0)
				{
					$html.='<i>&nbsp;&nbsp;'.$arClass['DESCRIPTION'].'</i>';
				}

				$html.='</td><td valign="top" align="right">';

				//Кнопки редактирования класса
				$html.='<a href="'.$adminDir.'objects/class_edit.php?id='.$arClass['ID']
					.'" class="btn btn-default btn-sm" title="Редактировать"><i class="glyphicon glyphicon-pencil"></i></a>'
					.'<a href="'.$adminDir.'objects/class_properties_list.php?id='.$arClass['ID']
					.'" class="btn btn-default btn-sm" title="Свойства"><i class="glyphicon glyphicon-th"></i></a>'
					.'<a href="'.$adminDir.'objects/class_methods_list.php?id='.$arClass['ID']
					.'" class="btn btn-default btn-sm" title="Методы"><i class="glyphicon glyphicon-th-list"></i></a>'
					.'<a href="'.$adminDir.'objects/class_objects_list.php?id='.$arClass['ID']
					.'" class="btn btn-default btn-sm" title="Объекты"><i class="glyphicon glyphicon-th-large"></i></a>'
					.'<a href="'.$adminDir.'objects/class_add_child.php?id='.$arClass['ID'].'" class="btn btn-default btn-sm" title="Расширить"><i class=""></i>Расширить</a>';
				if($bNoObjects)
				{
					$html.='<a href="'.$adminDir.'objects/index.php?deleteClass='.$arClass['ID'] //.'&id='.$arClass['ID']
						.'" class="btn btn-default btn-sm" title="Удалить" onclick="'."return confirm('Вы действительно хотите удалить класс ".$arClass['TITLE']."?')"
						.'"><i class="glyphicon glyphicon-remove"></i></a>';
				}
				//---end Кнопки редактирования

				$html.='</td></tr>';

				//Объекты класса и подклассы
				if (!$bNoObjects)
				{
					$html.='<tr class="sublist-'.$arClass['ID'];
					if (
						$USER->issetUserCookie('classes-view-'.$arClass['ID'])===true
						&& intval($USER->getUserCookie('classes-view-'.$arClass['ID']))==1
					)
					{
						$html.=' show';
					}
					else
					{
						$html.=' hide';
					}
					$html.='">';

					//Объекты класса
					$html.='<td valign="top" colspan="2"><div><table border="0"><tbody>';

					foreach ($arObjects as $arObject)
					{
						$html.='<tr><td><a href="'.$adminDir.'objects/class_object_edit.php?classID='.$arClass['ID'].'&id='.$arObject['ID'].'">'.$arObject['TITLE'].'</a>';
						$html.='</td><td>&nbsp;';
						if (strlen($arObject['DESCRIPTION'])>0)
						{
							$html.=$arObject['DESCRIPTION'];
						}
						$html.='</td></tr>';


						$arMethods = Tables\MethodsTable::getList(
							array(
								'select' => array('ID','TITLE','DESCRIPTION'),
								'filter' => array('OBJECT_ID'=>intval($arObject['ID'])),
								'order' => array('TITLE'=>'ASC')
							)
						);
						if ($arMethods)
						{
							//Переопределенные методы объекта класса
							$html.='<tr><td>&nbsp;</td><td><small><ul>';

							foreach ($arMethods as $arMethod)
							{
								$html.='<li><a href="'.$adminDir.'objects/object_method_edit.php?classID='.$arClass['ID'].'&objectID='.$arObject['ID'].'&id='.$arMethod['ID'].'">'.$arMethod['TITLE'].'</a>';
								if (strlen($arMethod['DESCRIPTION'])>0)
								{
									$html.=' - '.$arMethod['DESCRIPTION'];
								}
								$html.='</li>';
							}

							$html.='</ul></small></td></tr>';
							//---end Переопределенные методы...
						}
					}

					$html.='</tbody></table></div></td>';
					//---end Объекты класса...

					$html.='</tr>';
				}
				//---end Объекты классов и...

				//Если есть подклассы
				$arChild = Tables\ClassesTable::getList(
					array(
						'select' => array('ID'),
						'filter' => array('PARENT_ID'=>intval($arClass['ID'])),
						'order' => array('TITLE'=>'ASC'),
						'limit' => 1
					)
				);
				if ($arChild && isset($arChild[0]))
				{
					$arChild = $arChild[0];
				}
				if ($arChild)
				{
					$html.='<tr class="sublist-'.$arClass['ID'];
					if (
						$USER->issetUserCookie('classes-view-'.$arClass['ID'])===true
						&& intval($USER->getUserCookie('classes-view-'.$arClass['ID']))==1
					)
					{
						$html.=' show';
					}
					else
					{
						$html.=' hide';
					}
					$html.='">';

					$html.='<td style="padding-left:40px" colspan="2">';

					$html.=static::getTreeView($arClass['ID']);

					$html.='</td></tr>';
				}

			}

			$html.='</tbody></table>';

		}


		return $html;
	}*/


	/**
	 * Возвращает список классов (и их объектов, если необходимо)
	 *
	 * @param bool $bFull Флаг, результат включает свойства, методы и объекты
	 * @param string $sParentClassName Имя родительского класса
	 *
	 * @return array
	 */
	public static function getList ($bFull=false, $sParentClassName=null)
	{
		$arReturn = array ();

		$USER = Application::getInstance()->getUser();

		$arClasses = ClassesTable::getList(array (
			'select' => array (
				'NAME',
				'NOTE',
				'PARENT_CLASS',
				//'PARENT_LIST',
				//'CHILDREN_LIST',
				'TYPE',
				'CREATED',
				'UPDATED'
			),
			'filter' => array ('PARENT_CLASS' => $sParentClassName),
			'order' => array ('NAME'=>'ASC')
		));

		if ($arClasses)
		{
			$i=0;
			foreach ($arClasses as $ar_class)
			{
				$arReturn[$i] = $ar_class;
				$arReturn[$i]['SHOW'] = (
					$USER->issetUserCookie('classes-view-'.strtolower($ar_class['NAME']))===true
					&& intval($USER->getUserCookie('classes-view-'.strtolower($ar_class['NAME'])))==1
				);
				if ($bFull)
				{
					$arReturn[$i]['PROPERTIES'] = Classes::getClassPropertiesList(
						$ar_class['NAME'],
						array (
							'NAME',
							'PROPERTY_NAME',
							'NOTE',
							'TYPE',
							'LINKED',
							'CREATED',
							'UPDATED'
						)
					);
					$arReturn[$i]['METHODS'] = Classes::getClassMethodList(
						$ar_class['NAME'],
						array (
							'NAME',
							'METHOD_NAME',
							'NOTE',
							'CREATED',
							'UPDATED'
						)
					);
					$arReturn[$i]['OBJECTS'] = Objects::getObjectsListByClassName($ar_class['NAME'],true);
				}
				$arReturn[$i]['CHILDREN'] = static::getList($bFull,$ar_class['NAME']);
				$i++;
			}
		}

		return $arReturn;
	}

	public static function getClassMethodList ($sClassName, $arFields=array('NAME','LAST_RUN','LAST_PARAMETERS'))
	{
		//TODO: Добавить проверки имени класса

		$arRes = ClassMethodsTable::getList(array (
			'select' => $arFields,
			'filter' => array ('CLASS_NAME'=>$sClassName),
			'order' => array ('METHOD_NAME'=>'ASC')
		));

		if (!$arRes)
		{
			return array ();
		}

		return $arRes;
	}

	/**
	 * Возвращает список родителей класса
	 *
	 * @param string $sClassName Имя класса
	 *
	 * @return array
	 */
	public static function getParentsList($sClassName)
	{
		$arParentsList = array($sClassName);

		while (true)
		{
			$arRes = ClassesTable::getOne(
				array(
					'select' => 'PARENT_CLASS',
					'filter' => array('NAME'=>$sClassName)
				)
			);
			if (!$arRes || is_null($arRes['PARENT_CLASS']))
			{
				break;
			}

			if (!in_array($arRes['PARENT_CLASS'],$arParentsList))
			{
				$arParentsList[] = $arRes['PARENT_CLASS'];
			}
			$sClassName = $arRes['PARENT_CLASS'];
		}

		return array_reverse($arParentsList);
	}

	/**
	 * Возвращает заданные параметры класса
	 *
	 * @param string       $sClassName Имя класса
	 * @param string|array $arParams   Имя или массив параметров
	 *
	 * @return array|bool|string
	 */
	public static function getClassParams ($sClassName, $arParams='*')
	{
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array ('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		$arRes = ClassesTable::getOne(
			array(
				'select' => $arParams,
				'filter' => array('NAME'=>$sClassName)
			)
		);
		if ($arRes)
		{
			if (!is_array($arParams) && $arParams != '*')
			{
				return $arRes[$arParams];
			}
		}

		return $arRes;
	}

	/**
	 * Возвращает список свойств текущего класса
	 *
	 * @param null|string имя класса, по умолчанию класс объекта
	 * @param null|array|string список получаемых полей, по умолчанию ('NAME','LINKED')
	 *
	 * @return array|bool
	 */
	public static function getClassPropertiesList ($sClassName, $arFields = array('NAME','LINKED'))
	{
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array ('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		$arRes = ClassPropertiesTable::getList(
			array(
				'select' => $arFields,
				'filter' => array('CLASS_NAME'=>$sClassName)
			)
		);

		return $arRes;
	}

	/**
	 * Возвращает требуемые параметры свойства класса
	 *
	 * @param string       $sClassName    Имя класса
	 * @param string       $sPropertyName Имя свойства
	 * @param string|array $arSelect      Названия параметра, либо массив параметров
	 *
	 * @return array|bool|string
	 */
	public static function getClassPropertiesParams($sClassName,$sPropertyName,$arSelect='*')
	{
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array ('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		if (!static::checkPropertyName($sPropertyName))
		{
			//'Имя свойства "#PROPERTY_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols',
					array ('PROPERTY_NAME'=>$sPropertyName)
				),
				'PROPERTY_WRONG_SYMBOLS'
			);
			return false;
		}

		$arRes = ClassPropertiesTable::getOne(
			array(
				'select' => $arSelect,
				'filter' => array('NAME'=>$sClassName.'.'.$sPropertyName)
			)
		);
		if (!is_array($arSelect) && $arSelect != '*')
		{
			return $arRes[$arSelect];
		}

		return $arRes;
	}

	/**
	 * Возвращает параметры указанного метода класса
	 *
	 * @param string       $sClassName  Имя класса
	 * @param string       $sMethodName Имя метода
	 * @param string|array $arParams    Имя или массив выбираемых полей
	 *
	 * @return array|bool|string
	 */
	public static function getClassMethod ($sClassName, $sMethodName, $arParams='*')
	{
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols',
					array('CLASS_NAME'=>$sClassName)
				),
				'WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с именем "#CLASS_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists',
					array ('CLASS_NAME'=>$sClassName)
				),
				'CLASS_NO_EXISTS'
			);
			return false;
		}

		if (!isset($sMethodName))
		{
			//'Имя метода не задано'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_method_no_name'
				),
				'METHOD_NO_NAME'
			);
			return false;
		}
		elseif (!static::checkMethodName($sMethodName))
		{
			//'Имя метода "#METHOD_NAME#" содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_method_wrong_symbols',
					array ('METHOD_NAME'=>$sMethodName)
				),
				'METHOD_WRONG_SYMBOLS'
			);
			return false;
		}

		$arRes = ClassMethodsTable::getOne(
			array(
				'select' => $arParams,
				'filter'=> array('NAME'=>$sClassName.'.'.$sMethodName)
			)
		);
		if (!$arRes)
		{
			//'Метод "#METHOD_NAME#" не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_method_no_exists',
					array ('METHOD_NAME'=>$sMethodName)
				),
				'METHOD_NO_EXISTS'
			);
			return false;
		}
		elseif (!is_array($arParams) && $arParams!='*')
		{
			$arRes = $arRes[$arParams];
			return $arRes;
		}
		else
		{
			return $arRes;
		}
	}

	/**
	 * Возвращает список ошибок, возникших в ходе работы методов класса
	 *
	 * @return ErrorCollection|null
	 */
	public static function getErrors ()
	{
		return static::$errorCollection;
	}
	//</editor-fold>

	public static function delete ($sClassName, $bConfirm=false)
	{
		//TODO: Сделать удаление классов
	}

	//<editor-fold defaultstate="collapsed" desc="Protected methods">
	/* PROTECTED */

	/**
	 * Добавляет новую ошибку в коллекцию
	 *
	 * @param string $sMessage Сообщение об ошибке
	 * @param string $sCode Код ошибки
	 */
	protected static function addError($sMessage, $sCode=null)
	{
		if (is_null(static::$errorCollection))
		{
			static::$errorCollection = new ErrorCollection();
		}
		static::$errorCollection->setError($sMessage,$sCode);
	}
	//</editor-fold>
}