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

use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Modules;
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
	const NAME_REGULAR = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

	/**
	 * @var null|ErrorCollection
	 */
	private static $errorCollection = null;

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
	 * Проверяет, существует ли указанный класс
	 *
	 * @param string $sClassName Имя класса
	 *
	 * @return bool
	 */
	public static function checkClassExists ($sClassName)
	{
		$arRes = ClassesTable::getOne(
			array(
				'select' => 'NAME',
				'filter' => array('NAME'=>$sClassName)
			)
		);

		return (!!$arRes);
	}

	/* ADD */

	/**
	 * Добавляет новый класс
	 *
	 * @param array $arParams Массив параметров класса
	 *
	 * @return bool
	 */
	public static function addNewClass(array $arParams)
	{
		$arAdd = array();

		//Проверяем имя класса
		if (!isset($arParams['NAME']))
		{
			//'Не указано название класса'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_name'),'NO_NAME');
			return false;
		}
		elseif (!static::checkName($arParams['NAME']))
		{
			//'Имя класса содержит запрещенные символы',
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOLS');
			return false;
		}
		else
		{
			if (static::checkClassExists($arParams['NAME']))
			{
				//'Класс с таким именем уже существует'
				static::addError(Loc::getModuleMessage('ms.dobrozhil','error_class_exists'),'CLASS_EXISTS');
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
		elseif (!preg_match(self::NAME_REGULAR,$arParams['PARENT_CLASS']))
		{
			//'Имя родительского класса содержит недопустимые символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_parent_wring_symbols'),'PARENT_WRONG_SYMBOLS');
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
				//'Неверное имя модуля'
				static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_module_name'),'WRONG_MODULE_NAME');
				return false;
			}
			elseif (!Loader::issetModule($arParams['MODULE']))
			{
				//'Указанный модуль не установлен'
				static::addError(Loc::getModuleMessage('ms.dobrozhil','error_module_not_install'),'MODULE_NOT_INSTALL');
				return false;
			}
			else
			{
				$arAdd['MODULE'] = $arParams['MODULE'];
				if (!Loader::includeModule($arParams['MODULE']))
				{
					//'Ошибка подключения модуля #MODULE_NAME#'
					static::addError(Loc::getModuleMessage('ms.dobrozhil','error_module_not_include',array ('MODULE_NAME'=>$arParams['MODULE'])),'MODULE_NOT_INCLUDE');
					return false;
				}

				//Проверяем класс модуля
				if (!isset($arParams['NAMESPACE']))
				{
					//'Не указано имя класса'
					static::addError(Loc::getModuleMessage('ms.dobrozhil','error_module_no_namespace'),'MODULE_NO_NAMESPACE');
					return false;
				}
				elseif (!Loader::classExists($arParams['NAMESPACE']))
				{
					//'Указанный класс не существует среди автозагружаемых классов модуля'
					static::addError(
						Loc::getModuleMessage('ms.dobrozhil','error_module_class_no_autoload'),
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
			//'Ошибка добавления нового класса'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_add'),'NO_ADD');
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
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOLS');
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с таким именем не существует'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_class_no_exists'),'CLASS_NO_EXISTS');
			return false;
		}

		if (!isset($sName))
		{
			//'Имя свойства не задано'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_no_name'),'PROPERTY_NO_NAME');
			return false;
		}
		elseif (!static::checkName($sName))
		{
			//'Имя свойства содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_wrong_symbols'),'PROPERTY_WRONG_SYMBOLS');
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
			//'Не удалось добавить новое свойство'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_not_add'),'PROPERTY_NOT_ADD');
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
	 * @param null|string $sScriptName Имя скрипта
	 * @param null|string $sNote       Описание метода
	 *
	 * @return bool
	 */
	public static function addNewMethod ($sClassName, $sMethodName, $sCode=null, $sScriptName=null, $sNote=null)
	{
		$arAdd = array();
		if (!static::checkName($sClassName))
		{
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOLS');
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с таким именем не существует'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_class_no_exists'),'CLASS_NO_EXISTS');
			return false;
		}

		if (!isset($sMethodName))
		{
			//'Имя метода не задано'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_method_no_name'),'METHOD_NO_NAME');
			return false;
		}
		elseif (!static::checkName($sMethodName))
		{
			//'Имя метода содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_method_wrong_symbols'),'METHOD_WRONG_SYMBOLS');
			return false;
		}
		else
		{
			$arAdd['NAME'] = $sClassName.'.'.$sMethodName;
			$arAdd['METHOD_NAME'] = $sMethodName;
			$arAdd['CLASS_NAME'] = $sClassName;
		}

		if (!is_null($sCode))
		{
			$arAdd['CODE'] = $sCode;
		}

		if (!is_null($sScriptName))
		{
			if (!true) //Здесь будет проверка на существование скрипта с таким именем
			{
				//'Скрипта с таким именем не существует'
				static::addError(Loc::getModuleMessage('ms.dobrozhil','error_script_no_exists'),'SCRIPT_NO_EXISTS');
				return false;
			}
			else
			{
				$arAdd['SCRIPT_NAME'] = $sScriptName;
			}
		}

		if (!is_null($sNote))
		{
			$arAdd['NOTE'] = $sNote;
		}

		$res = ClassMethodsTable::add($arAdd);
		if (!$res->getResult())
		{
			static::addError('Не удалось добавить новый метод класса','METHOD_NOT_ADD');
			return false;
		}
		else
		{
			return true;
		}
	}

	/* GETS */

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

			$arParentsList[] = $sClassName = $arRes['PARENT_CLASS'];
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
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOLS');
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
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOLS');
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
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOLS');
			return false;
		}
		if (!static::checkName($sPropertyName))
		{
			//'Имя свойства содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_wrong_symbols'),'PROPERTY_WRONG_SYMBOLS');
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
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOLS');
			return false;
		}
		elseif (!static::checkClassExists($sClassName))
		{
			//'Класса с таким именем не существует'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_class_no_exists'),'CLASS_NO_EXISTS');
			return false;
		}

		if (!isset($sMethodName))
		{
			//'Имя метода не задано'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_method_no_name'),'METHOD_NO_NAME');
			return false;
		}
		elseif (!static::checkName($sMethodName))
		{
			//'Имя метода содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_method_wrong_symbols'),'METHOD_WRONG_SYMBOLS');
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
			//'Метод не существует'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_method_no_exists'),'METHOD_NO_EXISTS');
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

	/* PRIVATE */

	/**
	 * Добавляет новую ошибку в коллекцию
	 *
	 * @param string $sMessage Сообщение об ошибке
	 * @param string $sCode Код ошибки
	 */
	private static function addError($sMessage, $sCode=null)
	{
		if (is_null(static::$errorCollection))
		{
			static::$errorCollection = new ErrorCollection();
		}
		static::$errorCollection->setError($sMessage,$sCode);
	}
}