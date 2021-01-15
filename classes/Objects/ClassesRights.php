<?php

namespace Ms\Dobrozhil\Objects;

use Ms\Core\Entity\System\Multiton;
use Ms\Core\Lib\Tools;
use Ms\Core\Entity\Modules\Access as ModuleAccess;
use Ms\Dobrozhil\Access\Access;

class ClassesRights extends Multiton
{
	/**
	 * Стандартные типы прав классов
	 * @var array
	 */
	public $arMainRightsTypes = [
		'CLASSES',
		'CLASS_PROPERTIES',
		'CLASS_METHODS',
		'CLASS_OBJECTS'
	];

	/**
	 * Возвращает главные права для классов, их свойств, их методов и их объектов
	 * Возвращает в виде массива, содержащего значения true или false, по каждому возможному праву
	 *
	 * @param null|int $userID ID пользователя
	 *
	 * @return array
	 */
	public function getMainRights ($userID=null)
	{
		$userID = Tools::normalizeUserID($userID);
		$canDefault = ModuleAccess::getInstance()->can($userID, $arUserGroups);

		$arRights = Access::getInstance()->getAllUserRights($this->arMainRightsTypes, $userID);
		if (!$arRights)
		{
			$arRights = [];
		}

		if (isset($arRights['CLASSES']) && !empty($arRights['CLASSES']))
		{
			if ($canDefault && !in_array(Access::LEVEL_ALL, $arRights['CLASSES']))
			{
				$arRights['CLASSES'][] = Access::LEVEL_ALL;
			}
		}
		elseif ($canDefault)
		{
			$arRights['CLASSES'] = [Access::LEVEL_ALL];
		}
		else
		{
			$arRights['CLASSES'] = [];
		}
		$arRights['CLASSES'] = $this->convertTypeToBool($arRights['CLASSES']);

		if (isset($arRights['CLASS_PROPERTIES']) && !empty($arRights['CLASS_PROPERTIES']))
		{
			if ($canDefault && !in_array(Access::LEVEL_ALL, $arRights['CLASS_PROPERTIES']))
			{
				$arRights['CLASS_PROPERTIES'][] = Access::LEVEL_ALL;
			}
		}
		elseif ($canDefault)
		{
			$arRights['CLASS_PROPERTIES'] = [Access::LEVEL_ALL];
		}
		else
		{
			$arRights['CLASS_PROPERTIES'] = [];
		}
		$arRights['CLASS_PROPERTIES'] = $this->convertTypeToBool($arRights['CLASS_PROPERTIES']);

		if (isset($arRights['CLASS_METHODS']) && !empty($arRights['CLASS_METHODS']))
		{
			if ($canDefault && !in_array(Access::LEVEL_ALL, $arRights['CLASS_METHODS']))
			{
				$arRights['CLASS_METHODS'][] = Access::LEVEL_ALL;
			}
		}
		elseif ($canDefault)
		{
			$arRights['CLASS_METHODS'] = [Access::LEVEL_ALL];
		}
		else
		{
			$arRights['CLASS_METHODS'] = [];
		}
		$arRights['CLASS_METHODS'] = $this->convertTypeToBool($arRights['CLASS_METHODS']);

		if (isset($arRights['CLASS_OBJECTS']) && !empty($arRights['CLASS_OBJECTS']))
		{
			if ($canDefault && !in_array(Access::LEVEL_ALL, $arRights['CLASS_OBJECTS']))
			{
				$arRights['CLASS_OBJECTS'][] = Access::LEVEL_ALL;
			}
		}
		elseif ($canDefault)
		{
			$arRights['CLASS_OBJECTS'] = [Access::LEVEL_ALL];
		}
		else
		{
			$arRights['CLASS_OBJECTS'] = [];
		}
		$arRights['CLASS_OBJECTS'] = $this->convertTypeToBool($arRights['CLASS_OBJECTS']);


		return $arRights;
	}

	/**
	 * Возвращает флаг возможности добавления класса пользователем
	 *
	 * @param array $arMainRights Массив главных прав, возвращаемых методом getMainRights
	 *
	 * @see ClassesRights::getMainRights
	 *
	 * @return bool
	 */
	public function canAddClass ($arMainRights)
	{
		if (!isset($arMainRights['CLASSES'])) return false;

		return (
			$arMainRights['CLASSES']['ALL']
			|| $arMainRights['CLASSES']['CREATE']
		);
	}

	/**
	 * Возвращает флаг возможности добавления объекта пользователем
	 *
	 * @param array      $arMainRights  Массив главных прав, возвращаемых методом getMainRights
	 * @param null|array $arClass       Массив параметров для класса, в который добавляется объект.
	 *                                  Необязательный. По-умолчанию, null - не проверять доступ к классу
	 * @param null|int   $userID        ID пользователя, для которого производится проверка
	 *                                  Необязательный. Требуется только при наличии массива класса, но все равно не обязателен
	 *                                  По-умолчанию, null - будет взят ID текущего пользователя
	 *
	 * @return bool
	 */
	public function canAddObject ($arMainRights, $arClass=null, $userID=null)
	{
		$userID = Tools::normalizeUserID($userID);

		if (!isset($arMainRights['CLASS_OBJECTS'])) return false;

		if (is_null($arClass))
		{
			return (
				$arMainRights['CLASSES']['ALL']
				|| $arMainRights['CLASSES']['CREATE']
				|| $arMainRights['CLASS_OBJECTS']['ALL']
				|| $arMainRights['CLASS_OBJECTS']['CREATE']
			);
		}
		else
		{
			return (
				$arMainRights['CLASSES']['ALL']
				|| $arMainRights['CLASS_OBJECTS']['ALL']
				|| ($arMainRights['CLASSES']['CREATE'] && $arClass['CREATED_BY'] == $userID)
				|| ($arMainRights['CLASS_OBJECTS']['CREATE'] && $arClass['CREATED_BY'] == $userID)
			);
		}
	}

	/**
	 * Преобразовывает массив типов прав в массив со значениями true/false для каждого типа прав
	 *
	 * @param array $arRights Массив типов прав
	 *
	 * @return array
	 */
	public function convertTypeToBool ($arRights)
	{
		$arBool = [
			'ALL'       => false,
			'VIEW_OWN'  => false,
			'VIEW_ALL'  => false,
			'CREATE'    => false,
			'EDIT_OWN'  => false,
			'EDIT_ALL'  => false,
			'WRITE_OWN' => false,
			'WRITE_ALL' => false,
			'DELETE_OWN'=> false,
			'DELETE_ALL'=> false,
			'RUN_OWN'   => false,
			'RUN_ALL'   => false
		];

		if (in_array(Access::LEVEL_ALL,$arRights))          $arBool['ALL']          = true;
		if (in_array(Access::LEVEL_VIEW_OWN,$arRights))     $arBool['VIEW_OWN']     = true;
		if (in_array(Access::LEVEL_VIEW,$arRights))         $arBool['VIEW_ALL']     = true;
		if (in_array(Access::LEVEL_CREATE,$arRights))       $arBool['CREATE']       = true;
		if (in_array(Access::LEVEL_EDIT_OWN,$arRights))     $arBool['EDIT_OWN']     = true;
		if (in_array(Access::LEVEL_EDIT,$arRights))         $arBool['EDIT_ALL']     = true;
		if (in_array(Access::LEVEL_WRITE_OWN,$arRights))    $arBool['WRITE_OWN']    = true;
		if (in_array(Access::LEVEL_WRITE,$arRights))        $arBool['WRITE_ALL']    = true;
		if (in_array(Access::LEVEL_DELETE_OWN,$arRights))   $arBool['DELETE_OWN']   = true;
		if (in_array(Access::LEVEL_DELETE,$arRights))       $arBool['DELETE_ALL']   = true;
		if (in_array(Access::LEVEL_RUN_OWN,$arRights))      $arBool['RUN_OWN']      = true;
		if (in_array(Access::LEVEL_RUN,$arRights))          $arBool['RUN_ALL']      = true;

		return $arBool;
	}


	public function setClassRights (&$arClass, $arRights, $arResult, $userID=null)
	{
		$userID = Tools::normalizeUserID($userID);

		$arClass['RIGHTS'] = [
			'GENERAL' => [],
			'INHERIT' => []
		];
		if (
			isset($arRights['CLASS_NAME_'.$arClass['CLASS_NAME']])
			&& !empty($arRights['CLASS_NAME_'.$arClass['CLASS_NAME']])
		) {
			$arClass['RIGHTS']['GENERAL'] = Access::getInstance()->getRightsArray(
				$arRights['CLASS_NAME_'.$arClass['CLASS_NAME']],
				$arClass['CREATED_BY'],
				$userID
			);
		}

		if (strlen($arClass['PARENT_CLASS']) > 0)
		{
			$arParentRights = $this->getClassRightFromClassList($arResult['CLASSES'],$arClass['PARENT_CLASS']);
			if (!empty($arParentRights['GENERAL']))
			{
				$arClass['RIGHTS']['INHERIT'] = $arParentRights['GENERAL'];
			}
			elseif (!empty($arParentRights['INHERIT']))
			{
				$arClass['RIGHTS']['INHERIT'] = $arParentRights['INHERIT'];
			}
		}

		if (empty($arClass['RIGHTS']['INHERIT']) && isset($arResult['RIGHTS']['CLASSES']) && !empty($arResult['RIGHTS']['CLASSES']))
		{
			$arClass['RIGHTS']['INHERIT'] = Access::getInstance()->getRightsArray(
				$arResult['RIGHTS']['CLASSES'],
				$arClass['CREATED_BY'],
				$userID
			);
		}
	}

	public function setPropertyRights (&$arProperty, $arRights, $arResult, $userID=null)
	{
		$userID = Tools::normalizeUserID($userID);

		$arProperty['RIGHTS'] = [
			'GENERAL' => [],
			'INHERIT' => []
		];

		if (
			isset($arRights['CLASS_PROPERTY_NAME_'.$arProperty['CLASS_PROPERTY']])
			&& !empty($arRights['CLASS_PROPERTY_NAME_'.$arProperty['CLASS_PROPERTY']])
		) {
			$arProperty['RIGHTS']['GENERAL'] = Access::getInstance()->getRightsArray(
				$arRights['CLASS_PROPERTY_NAME_'.$arProperty['CLASS_PROPERTY']],
				$arProperty['CREATED_BY'],
				$userID
			);
		}

		$arClassRights = $this->getClassRightFromClassList($arResult['CLASSES'],$arProperty['CLASS_NAME']);
		if (!empty($arClassRights['GENERAL']))
		{
			$arProperty['RIGHTS']['INHERIT'] = $arClassRights['GENERAL'];
		}
		elseif (!empty($arClassRights['INHERIT']))
		{
			$arProperty['RIGHTS']['INHERIT'] = $arClassRights['INHERIT'];
		}

		if (empty($arProperty['RIGHTS']['INHERIT']) && isset($arResult['RIGHTS']['CLASS_PROPERTIES']) && !empty($arResult['RIGHTS']['CLASS_PROPERTIES']))
		{
			$arProperty['RIGHTS']['INHERIT'] = Access::getInstance()->getRightsArray(
				$arResult['RIGHTS']['CLASS_PROPERTIES'],
				$arProperty['CREATED_BY'],
				$userID
			);
		}

		if (!empty($arProperty['RIGHTS']['INHERIT']))
		{
			if (!$arProperty['RIGHTS']['INHERIT']['ALL'] && !$arProperty['RIGHTS']['INHERIT']['VIEW'])
			{
				$arProperty['RIGHTS']['INHERIT']['CREATE'] = false;
				$arProperty['RIGHTS']['INHERIT']['EDIT'] = false;
				$arProperty['RIGHTS']['INHERIT']['WRITE'] = false;
				$arProperty['RIGHTS']['INHERIT']['DELETE'] = false;
				$arProperty['RIGHTS']['INHERIT']['RUN'] = false;
			}
		}
	}

	public function setMethodRights (&$arMethod, $arRights, $arResult, $userID=null)
	{
		$userID = Tools::normalizeUserID($userID);

		$arMethod['RIGHTS'] = [
			'GENERAL' => [],
			'INHERIT' => []
		];

		if (
			isset($arRights['CLASS_METHOD_NAME_'.$arMethod['CLASS_METHOD']])
			&& !empty($arRights['CLASS_METHOD_NAME_'.$arMethod['CLASS_METHOD']])
		) {
			$arMethod['RIGHTS']['GENERAL'] = Access::getInstance()->getRightsArray(
				$arRights['CLASS_METHOD_NAME_'.$arMethod['CLASS_METHOD']],
				$arMethod['CREATED_BY'],
				$userID
			);
		}

		$arClassRights = $this->getClassRightFromClassList($arResult['CLASSES'], $arMethod['CLASS_NAME']);
		if (!empty($arClassRights['GENERAL']))
		{
			$arMethod['RIGHTS']['INHERIT'] = $arClassRights['GENERAL'];
		}
		elseif (!empty($arClassRights['INHERIT']))
		{
			$arMethod['RIGHTS']['INHERIT'] = $arClassRights['INHERIT'];
		}

		if (empty($arMethod['RIGHTS']['INHERIT']) && isset($arResult['RIGHTS']['CLASS_METHODS']) && !empty($arResult['RIGHTS']['CLASS_METHODS']))
		{
			$arMethod['RIGHTS']['INHERIT'] = Access::getInstance()->getRightsArray(
				$arResult['RIGHTS']['CLASS_METHODS'],
				$arMethod['CREATED_BY'],
				$userID
			);
		}

		if (!empty($arMethod['RIGHTS']['INHERIT']))
		{
			if (!$arMethod['RIGHTS']['INHERIT']['ALL'] && !$arMethod['RIGHTS']['INHERIT']['VIEW'])
			{
				$arMethod['RIGHTS']['INHERIT']['CREATE'] = false;
				$arMethod['RIGHTS']['INHERIT']['EDIT'] = false;
				$arMethod['RIGHTS']['INHERIT']['WRITE'] = false;
				$arMethod['RIGHTS']['INHERIT']['DELETE'] = false;
				$arMethod['RIGHTS']['INHERIT']['RUN'] = false;
			}
		}
	}

	public function setObjectRights (&$arObject, $arRights, $arResult, $userID=null)
	{
		$userID = Tools::normalizeUserID($userID);

		$arObject['RIGHTS'] = [
			'GENERAL' => [],
			'INHERIT' => []
		];

		if (
			isset($arRights['CLASS_OBJECT_NAME_'.$arObject['OBJECT_NAME']])
			&& !empty($arRights['CLASS_OBJECT_NAME_'.$arObject['OBJECT_NAME']])
		) {
			$arObject['RIGHTS']['GENERAL'] = Access::getInstance()->getRightsArray(
				$arRights['CLASS_OBJECT_NAME_'.$arObject['OBJECT_NAME']],
				$arObject['CREATED_BY'],
				$userID
			);
		}

		$arClassRights = $this->getClassRightFromClassList($arResult['CLASSES'], $arObject['CLASS_NAME']);
		if (!empty($arClassRights['GENERAL']))
		{
			$arObject['RIGHTS']['INHERIT'] = $arClassRights['GENERAL'];
		}
		elseif (!empty($arClassRights['INHERIT']))
		{
			$arObject['RIGHTS']['INHERIT'] = $arClassRights['INHERIT'];
		}

		if (empty($arObject['RIGHTS']['INHERIT']) && isset($arResult['RIGHTS']['CLASS_OBJECTS']) && !empty($arResult['RIGHTS']['CLASS_OBJECTS']))
		{
			$arObject['RIGHTS']['INHERIT'] = Access::getInstance()->getRightsArray(
				$arResult['RIGHTS']['CLASS_OBJECTS'],
				$arObject['CREATED_BY'],
				$userID
			);
		}

		if (!empty($arObject['RIGHTS']['INHERIT']))
		{
			if (!$arObject['RIGHTS']['INHERIT']['ALL'] && !$arObject['RIGHTS']['INHERIT']['VIEW'])
			{
				$arObject['RIGHTS']['INHERIT']['CREATE'] = false;
				$arObject['RIGHTS']['INHERIT']['EDIT'] = false;
				$arObject['RIGHTS']['INHERIT']['WRITE'] = false;
				$arObject['RIGHTS']['INHERIT']['DELETE'] = false;
				$arObject['RIGHTS']['INHERIT']['RUN'] = false;
			}
		}
	}

	public function getClassRightFromClassList ($arClassList, $sClassName)
	{
		if (!empty($arClassList) && strlen($sClassName) > 0)
		{
			foreach ($arClassList as $arClass)
			{
				if ($arClass['CLASS_NAME'] == $sClassName && isset($arClass['RIGHTS']))
				{
					return $arClass['RIGHTS'];
				}
			}
		}

		return ['GENERAL'=>[],'INHERIT'=>[]];
	}


}
