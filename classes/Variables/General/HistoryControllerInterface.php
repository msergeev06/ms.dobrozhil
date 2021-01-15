<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\General;

use Ms\Core\Entity\Type\Date;

/**
 * Интерфейс Ms\Dobrozhil\Variables\General\HistoryControllerInterface
 * Интерфейс контроллера исторических значений переменной
 */
interface HistoryControllerInterface
{
    /**
     * Возвращает статический объект для заданного дополнительного имени таблицы
     *
     * @return HistoryControllerInterface|null
     */
    public static function getInstance (VariableInterface $variable);

    /**
     * Устанавливает объект переменной для объекта исторических значений
     *
     * @param VariableInterface $variable
     *
     * @return mixed
     */
    public function setVariable (VariableInterface $variable);

    /**
     * Возвращает дату/время первой записи значения переменной в истории
     *
     * @return Date|null
     */
    public function getFirstHistoryDateTime ();

    /**
     * Возвращает дату/время последней записи значения переменной в истории
     *
     * @return Date|null
     */
    public function getLastHistoryDateTime ();

    /**
     * Возвращает минимальное значение переменной из истории для выбранного интервала
     *
     * @param Date|null $startDate      Дата начала интервала (включительно)
     * @param Date|null $stopDate       Дата окончания интервала (включительно)
     *
     * @return HistoryValueInterface
     */
    public function getHistoryMin (Date $startDate = null, Date $stopDate = null);

    /**
     * Возвращает максимальное значение переменной из истории для выбранного интервала
     *
     * @param Date|null $startDate      Дата начала интервала (включительно)
     * @param Date|null $stopDate       Дата окончания интервала (включительно)
     *
     * @return HistoryValueInterface
     */
    public function getHistoryMax (Date $startDate = null, Date $stopDate = null);

    /**
     * Возвращает сумму всех значений переменной из истории для выбранного интервала
     *
     * @param Date|null $startDate      Дата начала интервала (включительно)
     * @param Date|null $stopDate       Дата окончания интервала (включительно)
     *
     * @return float
     */
    public function getHistorySum (Date $startDate = null, Date $stopDate = null): float;

    /**
     * Возвращает количество всех значений переменной из истории для выбранного интервала
     *
     * @param Date|null $startDate      Дата начала интервала (включительно)
     * @param Date|null $stopDate       Дата окончания интервала (включительно)
     *
     * @return int
     */
    public function getHistoryCount (Date $startDate = null, Date $stopDate = null): int;

    /**
     * Возвращает среднее значение переменной из истории для выбранного интервала
     *
     * @param Date|null $startDate      Дата начала интервала (включительно)
     * @param Date|null $stopDate       Дата окончания интервала (включительно)
     *
     * @return float
     */
    public function getHistoryAvg (Date $startDate = null, Date $stopDate = null): float;

    /**
     * Возвращает массив со всеми значениями переменной из истории для выбранного интервала
     *
     * @param Date|null $startDate      Дата начала интервала (включительно)
     * @param Date|null $stopDate       Дата окончания интервала (включительно)
     *
     * @return array
     */
    public function getHistoryCollection (Date $startDate = null, Date $stopDate = null);
}