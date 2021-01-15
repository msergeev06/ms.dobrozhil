<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Objects\Classes;

/**
 * Интерфейс Ms\Dobrozhil\Objects\Classes\ClassInterface
 * Описывает основные возможности виртуальных КЛАССОВ системы
 */
interface ClassInterface
{
    /**
     * Конструктор класса ClassInterface
     *
     * @param string $objectName Имя ОБЪЕКТА класса
     */
    public function __construct (string $objectName);

    /**
     * Возвращает объекта класса \ReflectionClass для текущего класса
     *
     * @return \ReflectionClass
     */
    public function _getReflectionForThisClass ();

    /**
     * Возвращает имя ОБЪЕКТА класса
     *
     * @return string
     */
    public function _getObjectName (): string;

    /**
     * Возвращает имя класса объекта
     *
     * @return string
     */
    public function _getClassName (): string;

    /**
     * Возвращает объект родительского класса, либо null для базового класса
     *
     * @return ClassInterface|null
     */
    public function _getParentClass ();

    /**
     * Возвращает название родительского класса, либо null для базового класса
     *
     * @return string|null
     */
    public function _getParentClassName ();

    /**
     * Возвращает коллекцию дочерних классов для текущего класса.
     * Если у класса не дочерних классов, коллекция будет пустая
     *
     * @return ClassCollectionInterface
     */
    public function _getChildrenClassCollection ();

    /**
     * Возвращает коллекцию объектов виртуальных СВОЙСТВ класса
     *
     * @return mixed
     */
    public function _getPropertiesCollection ();

    /**
     * Возвращает список виртуальных СВОЙСТВ класса
     *
     * @return array
     */
    public function _getPropertiesList (): array;

    /**
     * Возвращает объект виртуального СВОЙСТВА класса
     *
     * @param string $propertyName Имя СВОЙСТВА класса
     *
     * @return mixed
     */
    public function _getProperty (string $propertyName);

    /**
     * Возвращает значение виртуального СВОЙСТВА класса для текущего виртуального ОБЪЕКТА
     *
     * @param string $propertyName Имя СВОЙСТВА класса
     *
     * @return mixed
     */
    public function _getPropertyValue (string $propertyName);

    /**
     * Устанавливает значение виртуального СВОЙСТВА класса для текущего виртуального ОБЪЕКТА
     *
     * @param string $propertyName  Имя СВОЙСТВА класса
     * @param mixed  $propertyValue Значение СВОЙСТВА для текущего ОБЪЕКТА
     *
     * @return ClassInterface
     */
    public function _setPropertyValue (string $propertyName, $propertyValue = null): ClassInterface;

    /**
     * Возвращает объект виртуального МЕТОДА класса, либо null, если МЕТОД не описан в классе
     *
     * @param string $methodName Имя МЕТОДА класса
     *
     * @return mixed
     */
    public function _getMethod (string $methodName);


}
