<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\History;

use Ms\Dobrozhil\General\Collection;
use Ms\Dobrozhil\Variables\General\HistoryValueInterface;

/**
 * Класс Ms\Dobrozhil\Variables\History\HistoryValueCollection
 * Коллекция исторических значений переменной
 */
class HistoryValueCollection extends Collection
{
    /**
     * Добавляет объект исторического значения в коллекцию
     *
     * @param HistoryValueInterface $historyValue Объект исторического значения
     *
     * @return $this
     */
    public function addHistoryValue (HistoryValueInterface $historyValue)
    {
        if ((int)$historyValue->getID() > 0)
        {
            $this->offsetSet($historyValue->getID(),$historyValue);
        }
        else
        {
            $this->offsetSet($this->getNextIndex(),$historyValue);
        }

        return $this;
    }

    /**
     * Возвращает объект исторического значения по его ID или индексу
     *
     * @param int $historyValueID ID или индекс объекта исторического значения
     *
     * @return HistoryValueInterface|null
     */
    public function getHistoryValue (int $historyValueID)
    {
        if ($this->offsetExists($historyValueID))
        {
            return $this->offsetGet($historyValueID);
        }

        return null;
    }

    /**
     * Возвращает следующий несуществующий индекс в коллекции
     *
     * @return int
     */
    protected function getNextIndex ()
    {
        $index = $this->count();
        while ($this->offsetExists($index))
        {
            $index++;
        }

        return $index;
    }
}