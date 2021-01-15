<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables;

use Ms\Dobrozhil\General\Collection;
use Ms\Dobrozhil\Variables\General\VariableInterface;

/**
 * Класс Ms\Dobrozhil\Variables\VariableCollection
 * Коллекция объектов виртуальных переменных умного дома
 */
class VariableCollection extends Collection
{
    /**
     * Добавляет объект виртуальной переменной в коллекцию, если у нее как минимум задано имя
     *
     * @param VariableInterface $variable Объект виртуальной переменной
     *
     * @return $this
     */
    public function addVariable (VariableInterface $variable)
    {
        if (!is_null($variable->getName()))
        {
            $this->offsetSet($variable->getName(),$variable);
        }

        return $this;
    }

    /**
     * Возвращает объект виртуальной переменной по ее имени, если она существует в коллекции, иначе возвращает NULL
     *
     * @param string $variableName Имя переменной
     *
     * @return VariableInterface|null
     */
    public function getVariable (string $variableName)
    {
        if (!$this->offsetExists($variableName))
        {
            return null;
        }

        return $this->offsetGet($variableName);
    }

    /**
     * Проверяет существование в коллекции переменной с заданным именем
     *
     * @param string $variableName Имя переменной
     *
     * @return bool
     */
    public function isset(string $variableName)
    {
        return $this->offsetExists($variableName);
    }
}