<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\DataTypes;

/**
 * Класс Ms\Dobrozhil\DataTypes\Coordinates
 * Координаты
 */
class Coordinates implements CoordinatesInterface
{
    const DELIMITER = ',';

    /** @var null|float */
    private $latitude = null;
    /** @var null|float */
    private $longitude = null;

    /**
     * Конструктор класса Coordinates
     *
     * @param float|null $latitude  Широта в виде десятичной дроби
     * @param float|null $longitude Долгота в виде десятичной дроби
     */
    public function __construct (float $latitude = null, float $longitude = null)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    /**
     * Устанавливает координаты из строки
     *
     * @param null|string $coordinates Координаты в виде строки десятичных дробей
     * @param string      $delimiter   Разделитель широты и долготы
     *
     * @return $this
     */
    public function setFromString ($coordinates, string $delimiter = self::DELIMITER)
    {
        if (is_null($coordinates) || strlen($coordinates) <= 0 || strpos($coordinates,self::DELIMITER) === false)
        {
            return $this;
        }
        list ($lat, $long) = explode($delimiter, $coordinates);
        $this->setLatitude((float)trim($lat));
        $this->setLongitude((float)trim($long));

        return $this;
    }

    /**
     * Устанавливает широту десятичной дробью
     *
     * @param float|null $latitude Значение широты
     *
     * @return CoordinatesInterface
     */
    public function setLatitude (float $latitude = null): CoordinatesInterface
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Возвращает значение широты в виде десятичной дроби
     *
     * @return null|float
     */
    public function getLatitude ()
    {
        return $this->latitude;
    }

    /**
     * Устанавливает долготу десятичной дробью
     *
     * @param float|null $longitude Значение долготы
     *
     * @return CoordinatesInterface
     */
    public function setLongitude (float $longitude = null): CoordinatesInterface
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Возвращает значение долготы в виде десятичной дроби
     *
     * @return null|float
     */
    public function getLongitude ()
    {
        return $this->longitude;
    }

    /**
     * Возвращает координаты в виде массива с ключами LATITUDE и LONGITUDE
     *
     * @return array
     */
    public function getCoordinatesArray (): array
    {
        return [
            'LATITUDE' => $this->latitude,
            'LONGITUDE' => $this->longitude
        ];
    }

    /**
     * Магический метод, возвращающий координаты в виде массива
     *
     * @return array
     */
    public function __toArray()
    {
        return $this->getCoordinatesArray();
    }

    /**
     * Возвращает координаты в виде строки. Координаты разделяются указанной строкой/символом
     *
     * @param string $delimiter Разделитель
     * @param bool   $bReverse  Выводить сначала долготу, а затем широту
     *
     * @return string|null
     */
    public function getCoordinatesString (string $delimiter = self::DELIMITER, bool $bReverse = false)
    {
        if (!is_null($this->latitude) && !is_null($this->longitude))
        {
            if (!$bReverse)
            {
                return $this->latitude . $delimiter . $this->longitude;
            }
            else
            {
                return $this->longitude . $delimiter . $this->latitude;
            }
        }
        else
        {
            return null;
        }
    }

    /**
     * Магический метод, возвращающий координаты в виде строки
     *
     * @return string|null
     */
    public function __toString ()
    {
        $strCoords = $this->getCoordinatesString();
        if (!is_null($strCoords))
        {
            return $strCoords;
        }
        else
        {
            return '';
        }
    }
}