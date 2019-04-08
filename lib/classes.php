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
use Ms\Core\Lib\Logs;
use Ms\Core\Lib\Modules;
use Ms\Dobrozhil\Entity\Objects\Base;
use Ms\Dobrozhil\Entity\Script;
use Ms\Dobrozhil\Tables\ClassesTable;
use Ms\Dobrozhil\Tables\ClassMethodsTable;
use Ms\Dobrozhil\Tables\ClassPropertiesTable;
use Ms\Core\Lib\Loc;
use Ms\Core\Lib\Errors as CoreErrors;

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

	const CLASS_TYPE_USER = 'U';
	const CLASS_TYPE_SYSTEM = 'S';
	const CLASS_TYPE_PROGRAM = 'P';

	/**
	 * Коллекция ошибок
	 * @var null|ErrorCollection
	 */
	private static $errorCollection;

	/**
	 * Кеш проверок существования классов
	 * @var array
	 */
	private static $arClassExists = array();

	/**
	 * Кеш проверок существования свойств классов
	 * @var array
	 */
	private static $arClassPropertyExists = array ();

	/**
	 * Кеш проверок существования методов классов
	 * @var array
	 */
	private static $arClassMethodsExists = array ();

	/**
	 * Проверяет правильность имени класса
	 *
	 * @param string $sClassName Имя класса
	 * @param bool   $bAddErrors Добавлять ошибки, при их наличии
	 *
	 * @return bool
	 */
	public static function checkName ($sClassName, $bAddErrors=true)
	{
		if (
			!isset($sClassName)
			|| is_null($sClassName)
			|| strlen($sClassName)<=0
		) {
			if ($bAddErrors)
			{
				//'Не указано название класса'
				Logs::setError(
					'Не указано название класса',
					[],
					static::$errorCollection,
					Errors::ERROR_NO_NAME
				);
			}
			return false;
		}

		$bOk = (!!preg_match(self::NAME_REGULAR,$sClassName));

		if (!$bOk)
		{
			if ($bAddErrors)
			{
				//'Имя класса "#CLASS_NAME#" содержит запрещенные символы',
				Logs::setError(
					'Имя класса "#CLASS_NAME#" содержит запрещенные символы',
					['CLASS_NAME'=>$sClassName],
					self::$errorCollection,
					Errors::ERROR_WRONG_NAME
				);

			}
			return false;
		}

		return true;
	}




	//<editor-fold defaultstate="collapse" desc="Deprecated">
	/**
	 * @deprecated
	 * @see Classes::issetClass()
	 * @param      $sClassName
	 * @param bool $bUpdate
	 *
	 * @return bool
	 */
	public static function checkClassExists ($sClassName, $bUpdate=false)
	{
		return self::issetClass($sClassName, $bUpdate);
	}

	/**
	 * @deprecated
	 * @see Classes::issetClassProperty()
	 * @param      $sClassName
	 * @param      $sPropertyName
	 * @param bool $bUpdate
	 * @return bool
	 */
	public static function checkClassPropertyExists ($sClassName,$sPropertyName, $bUpdate=false)
	{
		return self::issetClassProperty($sClassName,$sPropertyName, $bUpdate);
	}

	//</editor-fold>


	//<editor-fold defaultstate="collapse" desc="General">

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
	    //TODO: Оптимизировать так, чтобы не было отдельных запросов потомков для каждого класса
		$arReturn = array ();

		$USER = Application::getInstance()->getUser();

		$arClasses = ClassesTable::getList(array (
			'select' => array (
				'NAME',
				'TITLE',
				'NOTE',
				'PARENT_CLASS',
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
							'ID',
							'TITLE',
							'CLASS_NAME',
							'PROPERTY_NAME',
							'NOTE',
							'TYPE',
							'HISTORY',
							'SAVE_IDENTICAL_VALUES',
							'LINKED',
							'CREATED',
							'UPDATED'
						)
					);
					$arReturn[$i]['METHODS'] = Classes::getClassMethodList(
						$ar_class['NAME'],
						array (
							'ID',
							'TITLE',
							'CLASS_NAME',
							'METHOD_NAME',
							'NOTE',
							'LAST_PARAMETERS',
							'LAST_RUN',
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

	/**
	 * Возвращает список ошибок, возникших в ходе работы методов класса
	 *
	 * @return ErrorCollection|null
	 */
	public static function getErrors ()
	{
		return static::$errorCollection;
	}

	/**
	 * Очищает список ошибок
	 */
	public static function clearErrors ()
	{
		self::$errorCollection = null;
	}

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


	//<editor-fold defaultstate="collapse" desc="Classes">

	/**
	 * Проверяет, существует ли указанный класс
	 *
	 * @param string $sClassName       Имя класса
	 * @param bool   $bUpdate          Флаг принудительного получения данных из БД
	 * @param bool   $bErrorIfExist    Флаг добавления ошибки при существовании класса
	 * @param bool   $bErrorIfNotExist Флаг добавления ошибки при отсутствии класса
	 *
	 * @return bool|null
	 */
	public static function issetClass ($sClassName, $bUpdate=false, $bErrorIfExist=false, $bErrorIfNotExist=false)
	{
		//Проверяем имя класса
		if (!static::checkName($sClassName))
		{
			//'Имя класса "#CLASS_NAME#" содержит запрещенные символы',
			Logs::setError(
				Errors::getErrorTextByCode(
					Errors::ERROR_CLASS_NAME_WRONG_SYMBOLS,
					array ('CLASS_NAME'=>$sClassName)
				),
				array (),
				self::$errorCollection,
				Errors::ERROR_CLASS_NAME_WRONG_SYMBOLS
			);
			return null;
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

		if (!static::$arClassExists[$sClassName])
		{
			if ($bErrorIfNotExist)
			{
				//'Класс с именем #CLASS_NAME# не существует'
				Logs::setWarning(
					Errors::getErrorTextByCode(
						Errors::ERROR_CLASS_NOT_EXIST,
						array('CLASS_NAME'=>$sClassName)
					),
					array (),
					self::$errorCollection,
					Errors::ERROR_CLASS_NOT_EXIST
				);
			}
			return false;
		}
		else
		{
			if ($bErrorIfExist)
			{
				//'Класс с именем #CLASS_NAME# существует'
				Logs::setWarning(
					Errors::getErrorTextByCode(
						Errors::ERROR_CLASS_EXIST,
						array('CLASS_NAME'=>$sClassName)
					),
					array (),
					self::$errorCollection,
					Errors::ERROR_CLASS_EXIST
				);
			}
			return true;
		}
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
		$sReturn = '';

		self::$errorCollection = null;
		$issetClass = static::issetClass($sClassName);

		if (!is_null(self::$errorCollection))
		{
			$arErrors = self::$errorCollection->toArray();
			if (!empty($arErrors))
			{
				foreach ($arErrors as $code=>$text)
				{
					$sReturn .= '['.$code.'] '.$text."<br>";
				}
			}
		}

		if (strlen($sReturn)>0)
		{
			return $sReturn;
		}

		if ($issetClass)
		{
			return Errors::getError(Errors::ERROR_CLASS_EXIST,array ('CLASS_NAME'=>$sClassName));
		}

		return true;
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
		$sReturn = '';

		self::$errorCollection = null;
		static::issetClass($sClassName,false,false,true);

		if (!is_null(self::$errorCollection))
		{
			$arErrors = self::$errorCollection->toArray();
			if (!empty($arErrors))
			{
				foreach ($arErrors as $code=>$text)
				{
					$sReturn .= '['.$code.'] '.$text."<br>";
				}
			}
		}
		if (strlen($sReturn)>0)
		{
			return $sReturn;
		}

		return true;
	}

	/**
	 * Добавляет новый класс
	 *
	 * @param string $sClassName Имя класса
	 * @param string $sTitle Имя класса на языке системы
	 * @param string $sParentClass Имя родительского класса
	 * @param string $sNote Описание класса
	 * @param string $iSort Число для сортировки
	 * @param string $sModuleName Имя модуля, который добавил класс
	 * @param string $sNamespace Неймспейс программного класса
	 *
	 * @return bool
	 */
	public static function addNewClass ($sClassName, $sTitle=null, $sParentClass=null, $sNote=null, $iSort=null, $sModuleName=null, $sNamespace=null)
	{
		$arAdd = array();

		if (!static::issetClass($sClassName))
		{
			$arAdd['NAME'] = $sClassName;
		}

		if (!is_null($sTitle) && strlen(trim($sTitle))>0)
		{
			$arAdd['TITLE'] = trim($sTitle);
		}

		//Сохраняем сортировку
		if (!is_null($iSort))
		{
			$arAdd['SORT'] = (int)$iSort;
		}

		//Сохраняем описание
		if (!is_null($sNote))
		{
			$arAdd['NOTE'] = $sNote;
		}

		//Проверяем класс - родитель
		if (is_null($sParentClass))
		{
			$arAdd['PARENT_CLASS'] = null;
		}
		elseif (static::issetClass($sParentClass))
		{
			$arAdd['PARENT_CLASS'] = $sParentClass;
		}

		//Проверяем модуль
		if (!is_null($sModuleName))
		{
			if (!Modules::checkModuleName($sModuleName))
			{
				//'Неверное имя модуля "#MODULE_NAME#"'
				Logs::setError(
					CoreErrors::getErrorTextByCode(
						CoreErrors::ERROR_MODULE_WRONG_NAME,
						array('CLASS_NAME'=>$sClassName)
					),
					array (),
					self::$errorCollection,
					CoreErrors::ERROR_MODULE_WRONG_NAME
				);
				return false;
			}
			elseif (!Loader::issetModule($sModuleName))
			{
				//'Модуль "#MODULE_NAME#" не установлен'
				Logs::setError(
					CoreErrors::getErrorTextByCode(
						CoreErrors::ERROR_MODULE_NOT_INSTALLED,
						array('CLASS_NAME'=>$sClassName)
					),
					array (),
					self::$errorCollection,
					CoreErrors::ERROR_MODULE_NOT_INSTALLED
				);
				return false;
			}
			else
			{
				$arAdd['MODULE'] = $sModuleName;
				if (!Loader::includeModule($sModuleName))
				{
					return false;
				}

				//Проверяем класс модуля
				if (!isset($sNamespace) || strlen($sNamespace)<=0)
				{
					//'Неверное имя класса "#CLASS_NAME#"'
					Logs::setError(
						CoreErrors::getErrorTextByCode(
							CoreErrors::ERROR_CLASS_WRONG_NAME,
							array('CLASS_NAME'=>$sClassName)
						),
						array (),
						self::$errorCollection,
						CoreErrors::ERROR_CLASS_WRONG_NAME
					);
					return false;
				}
				elseif (!Loader::classExists($sNamespace))
				{
					//'Класс "#CLASS_NAME#" не существует среди автозагружаемых классов модуля "#MODULE_NAME#"'
					Logs::setError(
						CoreErrors::getErrorTextByCode(
							CoreErrors::ERROR_CLASS_NOT_AUTOLOAD,
							array(
								'CLASS_NAME'=>$sClassName,
								'MODULE_NAME'=>$sModuleName
							)
						),
						array (),
						self::$errorCollection,
						CoreErrors::ERROR_CLASS_NOT_AUTOLOAD
					);
					return false;
				}
				else
				{
					$arAdd['NAMESPACE'] = $sNamespace;
					$arAdd['TYPE'] = static::CLASS_TYPE_PROGRAM;
				}
			}
		}

		$res = ClassesTable::add($arAdd);
		if (!$res->getResult())
		{
			//'Ошибка добавления нового класса "#CLASS_NAME#"'
			Logs::setError(
				Errors::getErrorTextByCode(
					Errors::ERROR_CLASS_ADD,
					array('CLASS_NAME'=>$sClassName)
				),
				array (),
				self::$errorCollection,
				Errors::ERROR_CLASS_ADD
			);
			return false;
		}

		return true;
	}

	/**
	 * Возвращает список родителей класса
	 *
	 * @param string $sClassName Имя класса
	 * @param bool   $bAddSelf   Добавлять себя в список
	 *
	 * @return array
	 */
	public static function getParentsList($sClassName, $bAddSelf=false)
	{
		if ($bAddSelf)
		{
			$arParentsList = array($sClassName);
		}
		else
		{
			$arParentsList = array ();
		}

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
	 *      Доступны следующие поля:
	 *      NAME - имя класса
	 *      TITLE - название класса на языке системы
	 *      NOTE - описание класса
	 *      PARENT_CLASS - имя родительского класса
	 *      MODULE - модуль, добавивший класс (для программных классов)
	 *      NAMESPACE - программный класс с пространством имен (для программных классов)
	 *      TYPE - тип класса, гле S - системный класс, P - программый класс, U - пользовательский класс
	 *      CREATED - дата/время создания класса
	 *      UPDATED - дата/время изменения класса
	 *
	 * @return null|array|bool|string
	 */
	public static function getClassParams ($sClassName, $arParams='*')
	{
		if (!static::issetClass($sClassName,false,false,true))
		{
			return null;
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
	 * Удаляет указанный класс.
	 * Удаляет также все методы, свойства и значения свойств класса.
	 * Все потомки (если есть) будут привязаны к базовому классу.
	 *
	 * @param string $sClassName Имя класса
	 * @param bool   $bConfirm   Подтверждение удаления при наличие потомков
	 *
	 * @return bool Флаг успешного удаления класса
	 */
	public static function delete ($sClassName, $bConfirm=false)
	{
		self::$errorCollection = null;
		if (!static::issetClass($sClassName))
		{
			if (is_null(self::$errorCollection))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		if (!static::getParentClassName($sClassName) || $bConfirm)
		{
			$res = ClassesTable::delete($sClassName,true);
			if ($res->getResult())
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Возвращает имя родительского класса, либо FALSE
	 *
	 * @param string $sClassName Имя класса
	 *
	 * @return bool
	 */
	public static function getParentClassName ($sClassName)
	{
		$arRes = ClassesTable::getOne(array (
			'select' => array('PARENT_CLASS'),
			'filter' => array ('NAME'=>$sClassName)
		));
		if ($arRes && !is_null($arRes['PARENT_CLASS']))
		{
			return $arRes['PARENT_CLASS'];
		}

		return false;
	}

	//</editor-fold>


	//<editor-fold defaultstate="collapse" desc="Class property">

	/**
	 * Проверяет правильность имени свойства класса
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	public static function checkPropertyName ($sPropertyName, $bAddErrors=TRUE)
	{
		if (
			!isset($sPropertyName)
			|| is_null($sPropertyName)
			|| strlen($sPropertyName)<=0
		) {
			if ($bAddErrors)
			{
				//'Не указано название свойства класса'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_CLASS_NO_PROPERTY_NAME),
					array (),
					self::$errorCollection,
					Errors::ERROR_CLASS_NO_PROPERTY_NAME
				);
			}
			return false;
		}

		$bOk = (!!preg_match(self::NAME_REGULAR,$sPropertyName));

		return $bOk;
	}

	/**
	 * Проверяет существование свойства у класса или его предков
	 *
	 * @param string $sClassName        Имя класса
	 * @param string $sPropertyName     Имя свойства
	 * @param bool   $bUpdate           Флаг обязательной проверки в БД
	 * @param bool   $bErrorIfExist     Флаг добавления ошибки при существовании свойства
	 * @param bool   $bErrorIfNotExist  Флаг добавления ошибки при отсутствии свойства
	 *
	 * @return bool
	 */
	public static function issetClassProperty ($sClassName,$sPropertyName, $bUpdate=false, $bErrorIfExist=false, $bErrorIfNotExist=false)
	{
		//очищаем список ошибок
		static::clearErrors();

		//Проверяем имя класса
		if (!static::issetClass($sClassName))
		{
			return null;
		}
		//Проверяем имя свойства
		elseif (!static::checkPropertyName($sPropertyName))
		{
			//'Имя свойства "#PROPERTY_NAME#" содержит запрещенные символы',
			Logs::setError(
				Errors::getErrorTextByCode(
					Errors::ERROR_CLASS_PROPERTY_NAME_WRONG_SYMBOLS,
					array ('PROPERTY_NAME'=>$sPropertyName)
				),
				array (),
				self::$errorCollection,
				Errors::ERROR_CLASS_PROPERTY_NAME_WRONG_SYMBOLS
			);
			return null;
		}
		//Если все ОК
		else
		{
			//Если нет информации о существовании свойства в заданном классе (еще не проверяли) или нужно обновить данные
			if (
				!isset(static::$arClassPropertyExists[$sClassName][$sPropertyName])
				|| $bUpdate
			) {
				$sCheckClass = $sClassName;

				while (true)
				{
					$arRes = ClassPropertiesTable::getOne(
						array(
							'select' => 'ID',
							'filter' => array (
								'CLASS_NAME' => $sCheckClass,
								'PROPERTY_NAME' => $sPropertyName
							)
						)
					);

					static::$arClassPropertyExists[$sCheckClass][$sPropertyName] = (!!$arRes);
					static::$arClassPropertyExists[$sClassName][$sPropertyName] = static::$arClassPropertyExists[$sCheckClass][$sPropertyName];

					if (static::$arClassPropertyExists[$sClassName][$sPropertyName])
					{
						break;//Нашли? Хорошо!
					}
					else
					{
						//Не нашли, ищем в программном классе или у родителя
						$arRes = ClassesTable::getOne(array (
							'select' => array ('NAME','MODULE','NAMESPACE','PARENT_CLASS'),
							'filter' => array ('NAME'=>$sCheckClass)
						));
						if (!$arRes)
						{
							break;
						}
						if (!is_null($arRes['MODULE']) && !is_null($arRes['NAMESPACE'] && Loader::includeModule($arRes['MODULE'])))
						{
							//Проверка наличия программного свойства класса
							static::$arClassPropertyExists[$sCheckClass][$sPropertyName] = static::issetClassProgramProperty($arRes['NAMESPACE'],$sPropertyName);
							static::$arClassPropertyExists[$sClassName][$sPropertyName] = static::$arClassPropertyExists[$sCheckClass][$sPropertyName];

							if (static::$arClassPropertyExists[$sClassName][$sPropertyName])
							{
								break;
							}
						}

						if (!isset($arRes['PARENT_CLASS']) || is_null($arRes['PARENT_CLASS']))
						{
							break;
						}

						$sCheckClass = $arRes['PARENT_CLASS'];
					}
				}

				//Если не нашли свойство во всех предках класса, ищем в базовом классе
				if (!static::$arClassPropertyExists[$sClassName][$sPropertyName])
				{
					static::$arClassPropertyExists[$sClassName][$sPropertyName] = static::issetClassProgramProperty('Ms\Dobrozhil\Entity\Objects\Base',$sPropertyName);
				}
			}

			msDebug(static::$arClassPropertyExists);
			if (!static::$arClassPropertyExists[$sClassName][$sPropertyName])
			{
				if ($bErrorIfNotExist)
				{
					//'Свойства #PROPERTY_NAME# нет у класса #CLASS_NAME# и у его предков'
					Logs::setWarning(
						Errors::getErrorTextByCode(
							Errors::ERROR_CLASS_PROPERTY_NOT_EXIST,
							array('CLASS_NAME'=>$sClassName,'PROPERTY_NAME'=>$sPropertyName)
						),
						array (),
						self::$errorCollection,
						Errors::ERROR_CLASS_PROPERTY_NOT_EXIST
					);
				}
				return false;
			}
			else
			{
				if ($bErrorIfExist)
				{
					//'Свойство #PROPERTY_NAME# уже существует у класса #CLASS_NAME# или у его предков'
					Logs::setWarning(
						Errors::getErrorTextByCode(
							Errors::ERROR_CLASS_PROPERTY_EXIST,
							array('CLASS_NAME'=>$sClassName,'PROPERTY_NAME'=>$sPropertyName)
						),
						array (),
						self::$errorCollection,
						Errors::ERROR_CLASS_PROPERTY_EXIST
					);
				}
				return true;
			}
		}
	}

	/**
	 * Проверяет наличие программного свойства в указанном классе
	 *
	 * @param string $sClassNamespace Имя класса с пространством имен
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	public static function issetClassProgramProperty ($sClassNamespace, $sPropertyName)
	{
		$arProperties = static::getClassProgramProperties($sClassNamespace);
		$arProperties = array_keys($arProperties);

		return (in_array($sPropertyName,$arProperties));
	}

	/**
	 * Проверяет правильность заполнения поля с именем свойства класса, для добавления свойства
	 *
	 * @param string $sClassName    Имя класса
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool|string
	 */
	public static function checkPropertyAddNameField ($sClassName, $sPropertyName)
	{
		$sReturn = '';

		self::$errorCollection = null;
		$issetClassProperty = static::issetClassProperty($sClassName, $sPropertyName);

		if (!is_null(self::$errorCollection))
		{
			$arErrors = self::$errorCollection->toArray();
			if (!empty($arErrors))
			{
				foreach ($arErrors as $code=>$text)
				{
					$sReturn .= '['.$code.'] '.$text."<br>";
				}
			}
		}
		if (strlen($sReturn)>0)
		{
			return $sReturn;
		}

		if ($issetClassProperty)
		{
			return Errors::getError(
				Errors::ERROR_CLASS_PROPERTY_EXIST,
				array ('CLASS_NAME'=>$sClassName,'PROPERTY_NAME'=>$sPropertyName)
			);
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
	 * Возвращает список свойств текущего класса
	 *
	 * @param null|string имя класса, по умолчанию класс объекта
	 * @param null|array|string список получаемых полей, по умолчанию ('NAME','LINKED')
	 *
	 * @return array|bool
	 */
	public static function getClassPropertiesList ($sClassName, $arFields = array('PROPERTY_NAME'))
	{
		//TODO: Переделать
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
		//<editor-fold defaultstate="collapse" desc="Проверки">
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
		//</editor-fold>

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
	 * Возвращает список программных свойств указанного класса
	 *
	 * @param string|Base $sClassNamespace Имя класса с пространством имен
	 *
	 * @return array
	 */
	public static function getClassProgramProperties ($sClassNamespace)
	{
		if ($sClassNamespace instanceof Base)
		{
			$sClassNamespace = get_class($sClassNamespace);
		}

		return get_class_vars($sClassNamespace);
	}

	//</editor-fold>


	//<editor-fold defaultstate="collapse" desc="Class methods">

	/**
	 * Проверяет правильность имени метода класса
	 *
	 * @param string $sMethodName Имя метода
	 *
	 * @return bool
	 */
	public static function checkMethodName ($sMethodName, $bAddErrors=TRUE)
	{
		if (
			!isset($sMethodName)
			|| is_null($sMethodName)
			|| strlen($sMethodName)<=0
		) {
			if ($bAddErrors)
			{
				//'Не указано название метода класса'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_CLASS_NO_METHOD_NAME),
					array (),
					self::$errorCollection,
					Errors::ERROR_CLASS_NO_METHOD_NAME
				);
			}
			return FALSE;
		}

		$bOk = (!!preg_match(self::NAME_REGULAR,$sMethodName));

		if (!$bOk)
		{
			if ($bAddErrors)
			{
				//'Имя метода "#METHOD_NAME#" содержит запрещенные символы',
				Logs::setError(
					Errors::getErrorTextByCode(
						Errors::ERROR_CLASS_METHOD_NAME_WRONG_SYMBOLS,
						array ('METHOD_NAME'=>$sMethodName)
					),
					array (),
					self::$errorCollection,
					Errors::ERROR_CLASS_METHOD_NAME_WRONG_SYMBOLS
				);
			}
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Проверяет существования метода указанного класса
	 *
	 * @param string $sClassName Имя класса
	 * @param string $sMethodName Имя метода
	 * @param bool   $bUpdate Флаг принудтельной проверки в БД
	 * @param bool   $bErrorIfExist     Флаг добавления ошибки при существовании метода
	 * @param bool   $bErrorIfNotExist  Флаг добавления ошибки при отсутствии метода
	 *
	 * @return bool|null Возвращает TRUE, если метод существует, FALSE в противном случае. Либо NULL, если ошибка
	 */
	public static function issetClassMethod ($sClassName, $sMethodName, $bUpdate=false, $bErrorIfExist=false, $bErrorIfNotExist=false)
	{
		if (
			!static::issetClass($sClassName)
			|| !static::checkMethodName($sMethodName)
		)
		{
			return null;
		}

		if (!isset(static::$arClassMethodsExists[$sClassName][$sMethodName]) || $bUpdate)
		{
			$sCheckClass = $sClassName;
			while (true)
			{
				$arRes = ClassMethodsTable::getOne(
					array(
						'select' => 'NAME',
						'filter'=> array(
							'CLASS_NAME'=>$sCheckClass,
							'METHOD_NAME'=>$sMethodName
						)
					)
				);

				static::$arClassMethodsExists[$sClassName][$sMethodName] = (!!$arRes);

				if (static::$arClassMethodsExists[$sClassName][$sMethodName])
				{
					if ($sCheckClass != $sClassName)
					{
						static::$arClassMethodsExists[$sCheckClass][$sMethodName] = true;
					}
					break;//Нашли? Хорошо!
				}
				else
				{
					//Не нашли, ищем у родителя
					$arRes = ClassesTable::getOne(array (
						'select' => array ('NAME','PARENT_CLASS'),
						'filter' => array ('NAME'=>$sCheckClass)
					));
					if (!$arRes || !isset($arRes['PARENT_CLASS']) || is_null($arRes['PARENT_CLASS']))
					{
						break;
					}

					$sCheckClass = $arRes['PARENT_CLASS'];
				}
			}

		}

		if (!static::$arClassMethodsExists[$sClassName][$sMethodName])
		{
			if ($bErrorIfNotExist)
			{
				//'Метода #METHOD_NAME# нет у класса #CLASS_NAME# и у его предков'
				Logs::setWarning(
					Errors::getErrorTextByCode(
						Errors::ERROR_CLASS_METHOD_NOT_EXIST,
						array('CLASS_NAME'=>$sClassName,'METHOD_NAME'=>$sMethodName)
					),
					array (),
					self::$errorCollection,
					Errors::ERROR_CLASS_METHOD_NOT_EXIST
				);
			}
			return false;
		}
		else
		{
			if ($bErrorIfExist)
			{
				//'Метод #METHOD_NAME# существует у класса #CLASS_NAME# или у его предков'
				Logs::setWarning(
					Errors::getErrorTextByCode(
						Errors::ERROR_CLASS_METHOD_NOT_EXIST,
						array('CLASS_NAME'=>$sClassName,'METHOD_NAME'=>$sMethodName)
					),
					array (),
					self::$errorCollection,
					Errors::ERROR_CLASS_METHOD_NOT_EXIST
				);
			}
			return true;
		}
	}

	/**
	 * Проверяет существование программного метода указанного класса
	 *
	 * @param string $sClassNamespace Имя класса с пространством имен
	 * @param string $sMethodName Имя метода
	 *
	 * @return bool
	 */
	public static function issetClassProgramMethod ($sClassNamespace, $sMethodName)
	{
		$arMethods = static::getClassProgramMethods($sClassNamespace);

		return (in_array($sMethodName,$arMethods));
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

	public static function getClassMethodList ($sClassName, $arFields=array('METHOD_NAME','LAST_RUN','LAST_PARAMETERS'))
	{
		//TODO: Переделать
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
	 * Возвращает список методов указанного класса
	 *
	 * @param string $sClassNamespace Имя класса с пространством имен
	 *
	 * @return array
	 */
	public static function getClassProgramMethods ($sClassNamespace)
	{
		return get_class_methods($sClassNamespace);
	}

	//</editor-fold>

}