<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\DataTypes;

/**
 * Интерфейс Ms\Dobrozhil\DataTypes\CoordinatesInterface
 * Тип данных "Координаты"
 */
interface CoordinatesInterface
{
    /**
     * Устанавливает широту десятичной дробью
     *
     * @param float|null $latitude Значение широты
     *
     * @return CoordinatesInterface
     */
    public function setLatitude (float $latitude = null): CoordinatesInterface;

    /**
     * Возвращает значение широты в виде десятичной дроби
     *
     * @return null|float
     */
    public function getLatitude ();

    /**
     * Устанавливает долготу десятичной дробью
     *
     * @param float|null $longitude Значение долготы
     *
     * @return CoordinatesInterface
     */
    public function setLongitude (float $longitude = null): CoordinatesInterface;

    /**
     * Возвращает значение долготы в виде десятичной дроби
     *
     * @return null|float
     */
    public function getLongitude ();
}