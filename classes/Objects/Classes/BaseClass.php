<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Objects\Classes;

/**
 * Класс Ms\Dobrozhil\Objects\Classes\BaseClass
 * Базовый класс системы. Все остальные классы наследуются от него или от его потомков
 */
class BaseClass implements ClassInterface
{
    /** @var string */
    public $_objectName = null;

    /** @var \ReflectionClass */
    protected $_reflection = null;
    /** @var ClassCollection */
    protected $_childCollection = null;

    /**
     * @inheritDoc
     */
    public function __construct (string $objectName)
    {
        $this->_objectName = $objectName;
        try
        {
            $this->_reflection = new \ReflectionClass($this->_getClassName());
        }
        catch (\ReflectionException $e)
        {
            $this->_reflection = new \ReflectionClass(__CLASS__);
        }
        $this->_childCollection = new ClassCollection();
    }

    /**
     * @inheritDoc
     */
    public function _getReflectionForThisClass ()
    {
        return $this->_reflection;
    }

    /**
     * @inheritDoc
     */
    public function _getObjectName (): string
    {
        return $this->_objectName;
    }

    /**
     * @inheritDoc
     */
    public function _getClassName (): string
    {
        return get_called_class();
    }

    /**
     * @inheritDoc
     */
    public function _getParentClass ()
    {
        return $this->_reflection->getParentClass();
    }

    /**
     * @inheritDoc
     */
    public function _getParentClassName ()
    {
        return (!$this->_reflection->getParentClass()) ? $this->_reflection->getParentClass()->getName() : null;
    }

    /**
     * @inheritDoc
     */
    public function _getChildrenClassCollection ()
    {
        return $this->_childCollection;
    }

    /**
     * @inheritDoc
     */
    public function _getPropertiesCollection ()
    {
        // TODO: Implement _getPropertiesCollection() method.
    }

    /**
     * @inheritDoc
     */
    public function _getPropertiesList (): array
    {
        // TODO: Implement _getPropertiesList() method.
    }

    /**
     * @inheritDoc
     */
    public function _getProperty (string $propertyName)
    {
        // TODO: Implement _getProperty() method.
    }

    /**
     * @inheritDoc
     */
    public function _getPropertyValue (string $propertyName)
    {
        // TODO: Implement _getPropertyValue() method.
    }

    /**
     * @inheritDoc
     */
    public function _setPropertyValue (string $propertyName, $propertyValue = null): ClassInterface
    {
        // TODO: Implement _setPropertyValue() method.
    }

    /**
     * @inheritDoc
     */
    public function _getMethod (string $methodName)
    {
        // TODO: Implement _getMethod() method.
    }

    /**
     * @inheritDoc
     */
    public function __call ($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    /**
     * @inheritDoc
     */
    public function __get ($name)
    {
        return $this->_getPropertyValue($name);
    }

    /**
     * @inheritDoc
     */
    public function __set ($name, $value)
    {
        $this->_setPropertyValue($name, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __isset ($name)
    {
        return (in_array($name, $this->_getPropertiesList()));
    }

    /**
     * @inheritDoc
     */
    public function __unset ($name)
    {
        // TODO: clear property value from cache
    }

    /**
     * @inheritDoc
     */
    public function __toString ()
    {
        return $this->_objectName;
    }
}