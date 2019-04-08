<?php

namespace Ms\Dobrozhil\Interfaces;

use Ms\Dobrozhil\Lib\Types;

interface IEntity
{
	/**
	 * Возвращает тип сущности
	 *
	 * @return string
	 */
	public static function getEntityType ();

	/**
	 * Возвращает пространство имен с именем класса сущности
	 *
	 * @return string
	 */
	public static function getEntityNamespace ();

	/**
	 * Возвращает значение свойства сущности, либо NULL
	 *
	 * @param string   $sPropertyName Имя свойства сущности
	 * @param null|int $userID        ID пользователя, из под которого будет выполнен метод
	 *
	 * @return mixed
	 */
	public function getProperty($sPropertyName, $userID=null);

	/**
	 * Записывает значение в свойство сущности
	 *
	 * @param string   $sPropertyName Имя свойства сущности
	 * @param mixed    $value         Новое значение свойства сущности
	 * @param string   $sPropertyType Тип свойства
	 * @param null|int $userID        ID пользователя, из под которого будет выполнен метод
	 *
	 * @return bool
	 */
	public function setProperty($sPropertyName, $value, $sPropertyType=Types::BASE_TYPE_STRING, $userID=null);

	/**
	 * Возвращает TRUE, если заданное свойство существует у сущности и оно не равно NULL,
	 * в противном случае возвращает FALSE
	 *
	 * @param string $sPropertyName Имя свойства сущности
	 * @param bool   $bSaveValue    Сохранять ли значение свойства при проверке (по-умолчанию, FALSE - не сохранять)
	 *
	 * @return bool
	 */
	public function issetProperty ($sPropertyName, $bSaveValue = FALSE);

	/**
	 * Добавляет новое свойство сущности, при необходимости записывая в него значение
	 *
	 * @param string     $sPropertyName Имя нового свойства сущности
	 * @param string     $sPropertyType Тип значения свойства сущности
	 * @param null|int   $userID        ID пользователя, из под которого будет выполнен метод
	 *
	 * @return bool TRUE, если свойство было создано, FALSE - в противном случае
	 */
	public function addProperty ($sPropertyName, $sPropertyType='S', $userID=NULL);

	/**
	 * Возвращает все существующие свойства сущности с их значениями
	 * ['PROPERTY_NAME','PROPERTY_TYPE','CREATED_BY','VALUE','UPDATED_BY','UPDATED_DATE']
	 *
	 * @param null|int $userID ID пользователя, из под которого будет выполнен метод
	 *
	 * @return array|bool
	 */
	public function getAllProperties ($userID=null);
}