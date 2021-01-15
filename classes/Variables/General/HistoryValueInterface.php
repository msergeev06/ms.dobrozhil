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
 * Интерфейс Ms\Dobrozhil\Variables\General\VariableHistoryInterface
 * Интерфейс, описывающий одно историческое значение переменной
 */
interface HistoryValueInterface
{
    /**
     * Создает объект из массива полей таблицы БД
     *
     * @param array $arFields Массив полей таблицы БД
     *
     * @return HistoryValueInterface
     */
    public static function createFromArray (array $arFields): HistoryValueInterface;

    /**
     * Возвращает ID записи
     *
     * @return int
     */
    public function getID ();

    /**
     * Возвращает название переменной
     *
     * @return string|null
     */
    public function getVariableName ();

    /**
     * Возвращает историческое значение переменной
     *
     * @return mixed|null
     */
    public function getValue ();

    /**
     * Возвращает ID пользователя, добавившего значение
     *
     * @return int|null
     */
    public function getCreatedBy ();

    /**
     * Возвращает дату добавления значения
     *
     * @return Date|null
     */
    public function getCreatedDate ();

    /**
     * Конвертирует параметры объекта в массив полей таблицы БД
     *
     * @return array
     */
    public function toArray (): array;
}