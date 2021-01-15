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
 * Класс Ms\Dobrozhil\Locations\Location
 * Локации
 */
class Location implements LocationInterface
{
    /** @var int */
    private $id = null;
    /** @var null|string */
    protected $uid = null;
    /** @var null|string */
    protected $title = null;
    /** @var null|Coordinates */
    protected $coordinates = null;

    /**
     * Конструктор класса Location
     *
     * @param int $locationID ID локации в текущей базе данных
     */
    public function __construct (int $locationID)
    {
        $this->id = $locationID;
    }

    /**
     * Возвращает ID локации в текущей базе данных
     *
     * @return int
     */
    public function getID (): int
    {
        return $this->id;
    }

    /**
     * Устанавливает уникальный UID локации.
     * UIDы локаций генерируются облаком и при синхронизации устанавливаются в умном доме
     *
     * @param string $uid Уникальный идентификатор локации
     *
     * @return bool
     */
    public function setUID (string $uid): bool
    {
        if ($uid != $this->uid)
        {
            $this->uid = $uid;

            return LocationDbHelper::getInstance()->setUID($this->id, $uid);
        }

        return true;
    }

    /**
     * Возвращает уникальный UID локации, если он уже был установлен, либо NULL
     *
     * @return null|string
     */
    public function getUID ()
    {
        if (is_null($this->uid))
        {
            $this->uid = LocationDbHelper::getInstance()->getUID($this->id);
        }

        return $this->uid;
    }

    /**
     * Устанавливает название локации
     *
     * @param string $title Название локации
     *
     * @return bool
     */
    public function setTitle (string $title): bool
    {
        if ($title != $this->title)
        {
            $this->title = $title;

            return LocationDbHelper::getInstance()->setTitle($this->id, $title);
        }

        return true;
    }

    /**
     * Возвращает название локации
     *
     * @return string
     */
    public function getTitle (): string
    {
        if (is_null($this->title))
        {
            $this->title = LocationDbHelper::getInstance()->getTitle($this->id);
        }

        return $this->title;
    }

    /**
     * Устанавливает координаты локации
     *
     * @param Coordinates $coordinates Объект координат
     *
     * @return bool
     */
    public function setCoordinates (Coordinates $coordinates): bool
    {
        return LocationDbHelper::getInstance()->setCoordinates($this->id, $coordinates);
    }

    /**
     * Возвращает координаты локации
     *
     * @return Coordinates
     */
    public function getCoordinates (): Coordinates
    {
        return LocationDbHelper::getInstance()->getCoordinates($this->id);
    }
}