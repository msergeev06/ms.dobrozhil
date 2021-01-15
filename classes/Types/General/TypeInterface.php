<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types\General;

/**
 * Интерфейс Ms\Dobrozhil\Types\General\TypeInterface
 * Типы данных
 */
interface TypeInterface
{
    /**
     * Возвращает экземпляр объекта
     *
     * @return mixed
     */
    public static function getInstance ();

    /**
     * Возвращает название типа
     *
     * @return string
     */
    public function getTitle ():string;

    /**
     * Возвращает код типа
     *
     * @return string
     */
    public function getCode (): string;

    /**
     * Конвертирует данные из формата БД в формат кода
     *
     * @param null|string $value
     *
     * @return mixed
     */
    public function processingValueFromDB (string $value=null);

    /**
     * Конвертирует данные из формата кода в формат БД
     *
     * @param null|mixed $value
     *
     * @return string
     */
    public function processingValueToDB ($value=null): string;

    /**
     * Возвращает флаг возможности складывать значения
     *
     * @return bool
     */
    public function canSum (): bool;

    /**
     * Возвращает флаг возможности высчитывать среднее значение
     *
     * @return bool
     */
    public function canAvg (): bool;

    /**
     * Возвращает флаг возможности определять минимальное значение
     *
     * @return bool
     */
    public function canMin (): bool;

    /**
     * Возвращает флаг возможности определять максимальное значение
     *
     * @return bool
     */
    public function canMax (): bool;
}