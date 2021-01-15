<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Objects\Classes;

use Ms\Dobrozhil\General\Collection;

/**
 * Класс Ms\Dobrozhil\Objects\Classes\ClassCollection
 * Коллекция виртуальных КЛАССОВ системы
 */
class ClassCollection extends Collection implements ClassCollectionInterface
{
    /**
     * @inheritDoc
     */
    public function addClass (ClassInterface $class)
    {
        $this->offsetSet($class->_getClassName(),$class);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getClassByName (string $className)
    {
        if (!$this->offsetExists($className))
        {
            return null;
        }

        return $this->offsetGet($className);
    }

    /**
     * @inheritDoc
     */
    public function getCount (): int
    {
        return $this->count();
    }

    /**
     * @inheritDoc
     */
    public function isset (string $className): bool
    {
        return $this->offsetExists($className);
    }
}