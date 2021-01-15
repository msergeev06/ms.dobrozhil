<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Locations;

/**
 * Класс Ms\Dobrozhil\Locations\Floor
 * Описывает этаж локации
 */
class Floor implements FloorInterface
{
    /** @var int */
    protected $id = null;

    public function __construct (int $floorId)
    {
        $this->id = $floorId;
    }

    /**
     * Возвращает ID этажа
     *
     * @return int
     */
    public function getID (): int
    {
        return $this->id;
    }

    /**
     * Возвращает название этажа на языке системы
     *
     * @return string
     */
    public function getTitle (): string
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * Устанавливает название этажа на языке системы
     *
     * @param string $title Название этажа
     *
     * @return bool
     */
    public function setTitle (string $title): bool
    {
        // TODO: Implement setTitle() method.
    }

    /**
     * Возвращает уровень этажа
     *
     * @return int
     */
    public function getLevel (): int
    {
        // TODO: Implement getLevel() method.
    }

    /**
     * Устанавливает уровень этажа
     *
     * @param int $level Уровень этажа
     *
     * @return bool
     */
    public function setLevel (int $level): bool
    {
        // TODO: Implement setLevel() method.
    }
}