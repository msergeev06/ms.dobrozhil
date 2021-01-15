<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Locations;

use Ms\Dobrozhil\DataTypes\Coordinates;

/**
 * Интерфейс Ms\Dobrozhil\Locations\LocationInterface
 * Локации
 */
interface LocationInterface
{
    /**
     * Возвращает ID локации в текущей базе данных
     *
     * @return int
     */
    public function getID (): int;

    /**
     * Устанавливает уникальный UID локации.
     * UIDы локаций генерируются облаком и при синхронизации устанавливаются в умном доме
     *
     * @param string $uid Уникальный идентификатор локации
     *
     * @return bool
     */
    public function setUID (string $uid): bool;

    /**
     * Возвращает уникальный UID локации, если он уже был установлен, либо NULL
     *
     * @return null|string
     */
    public function getUID ();

    /**
     * Устанавливает название локации
     *
     * @param string $title Название локации
     *
     * @return bool
     */
    public function setTitle (string $title): bool;

    /**
     * Возвращает название локации
     *
     * @return string
     */
    public function getTitle (): string;

    /**
     * Устанавливает координаты локации
     *
     * @param Coordinates $coordinates Объект координат
     *
     * @return bool
     */
    public function setCoordinates (Coordinates $coordinates): bool;

    /**
     * Возвращает координаты локации
     *
     * @return Coordinates
     */
    public function getCoordinates (): Coordinates;
}