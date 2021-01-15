<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Locations;

/**
 * Интерфейс Ms\Dobrozhil\Locations\FloorInterface
 * Этаж
 */
interface FloorInterface
{
    /**
     * Возвращает ID этажа
     *
     * @return int
     */
    public function getID (): int;

    /**
     * Возвращает название этажа на языке системы
     *
     * @return string
     */
    public function getTitle (): string;

    /**
     * Устанавливает название этажа на языке системы
     *
     * @param string $title Название этажа
     *
     * @return bool
     */
    public function setTitle (string $title): bool;

    /**
     * Возвращает уровень этажа
     *
     * @return int
     */
    public function getLevel (): int;

    /**
     * Устанавливает уровень этажа
     *
     * @param int $level Уровень этажа
     *
     * @return bool
     */
    public function setLevel (int $level): bool;
}