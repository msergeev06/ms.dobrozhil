<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Type;

/**
 * Класс Ms\Dobrozhil\Type\GpsCoordinates
 * Тип значения GPS координаты
 */
class GpsCoordinates
{
    const SYMBOL_DEGREES    = '°';
    const SYMBOL_MINUTES    = "'";
    const SYMBOL_SECONDS    = '"';
    const SYMBOL_DELIMITER  = ', ';
    const SYMBOL_NORTH      = 'N';
    const SYMBOL_SOUTH      = 'S';
    const SYMBOL_EAST       = 'E';
    const SYMBOL_WEST       = 'W';

    /** @var null|float */
    protected $latitude = null;
    /** @var null|float */
    protected $longitude = null;

    public function __construct ()
    {
        //Empty constructor
    }

    /**
     * Устанавливает значение координат из строки, если координаты соответствуют одному из форматов координат:
     * 55.755831, 37.617673 (десятичные дроби через запятую и пробел. основной формат)
     * 55,755831°, 37,617673° (градусы)
     * N55.755831°, E37.617673° — градусы (+ доп. буквы)
     * 55°45.35′N, 37°37.06′E — градусы и минуты (+ доп. буквы)
     * 55°45′20.9916″N, 37°37′3.6228″E — градусы, минуты и секунды (+ доп. буквы)
     *
     * @param string $coordinates Строка, содержащая координаты в одном из форматов
     *
     * @return $this
     */
    public function setFromString (string $coordinates)
    {
        $preg = preg_match_all ( '/[NS+]{0,1}([-]{0,1}\d{1,3}[\.,]{1}\d{0,})[°]{0,1}[NS]{0,1}/is', $coordinates, $m);
        if ((int)$preg == 2 && isset($m[1]) && count($m[1]) == 2)
        {
            $this->latitude = (float)str_replace(',','.',$m[1][0]);
            $this->longitude = (float)str_replace(',','.',$m[1][1]);

            return $this;
        }

        //TODO: Добавить обработку других форматов координат


        return $this;
    }

    /**
     * Магический метод приведения объекта к строке
     *
     * @return string
     */
    public function __toString ()
    {
        return $this->getStringDec();
    }

    /**
     * Возвращает координаты если они установлены в виде строки, либо пустую строку
     * Формат возвращаемых координат
     *
     * @return string
     */
    public function getStringDec ()
    {
        return (!is_null($this->latitude) && !is_null($this->longitude))
            ? (string)$this->latitude . self::SYMBOL_DELIMITER . (string)$this->longitude
            : ''
        ;
    }

    /**
     * Возвращает координаты в виде строки с градусами и минутами одного из следующих форматов:
     * 55°45.35', 37°37.06'
     * 55°45.35'N, 37°37.06'E
     * N55°45.35', E37°37.06'
     *
     * @param bool $addSymbol   Флаг необходимости добавления буквы
     * @param bool $symbolFirst Флаг необходимости добавилении буквы в начале
     *
     * @return string
     */
    public function getStringMinutes (bool $addSymbol = false, bool $symbolFirst = false)
    {
        if (is_null($this->latitude) || is_null($this->longitude))
        {
            return '';
        }

        $latitude = $longitude = 0;
        $symbolLat = $symbolLong = '';
        $this->prepareCoodrs($latitude, $longitude, $symbolLat, $symbolLong);
        $degreesLat = (int)$latitude;
        $minutesLat = round((($latitude - $degreesLat) * 60),2);
        $degreesLong = (int)$longitude;
        $minutesLong = round((($longitude - $degreesLong) * 60), 2);

        $string = '';
        if ($addSymbol && $symbolFirst)
        {
            $string .= $symbolLat;
        }
        $string .= $degreesLat . self::SYMBOL_DEGREES . $minutesLat . self::SYMBOL_MINUTES;
        if ($addSymbol && !$symbolFirst)
        {
            $string .= $symbolLat;
        }
        $string .= self::SYMBOL_DELIMITER;
        if ($addSymbol && $symbolFirst)
        {
            $string .= $symbolLong;
        }
        $string .= $degreesLong . self::SYMBOL_DEGREES . $minutesLong . self::SYMBOL_MINUTES;
        if ($addSymbol && !$symbolFirst)
        {
            $string .= $symbolLong;
        }

        return $string;
    }

    /**
     * Возвращает координаты в виде строки с градусами, минутами и секундами одного из следующих форматов:
     * 55°45'20.9916", 37°37'3.6228"
     * 55°45'20.9916"N, 37°37'3.6228"E
     * N55°45'20.9916", E37°37'3.6228"
     *
     * @param bool $addSymbol   Флаг необходимости добавления буквы
     * @param bool $symbolFirst Флаг необходимости добавилении буквы в начале
     *
     * @return string
     */
    public function getStringSeconds (bool $addSymbol = false, bool $symbolFirst = false)
    {
        if (is_null($this->latitude) || is_null($this->longitude))
        {
            return '';
        }

        $latitude = $longitude = 0;
        $symbolLat = $symbolLong = '';
        $this->prepareCoodrs($latitude, $longitude, $symbolLat, $symbolLong);
        $degreesLat = (int)$latitude;
        $latitude = ($latitude - $degreesLat) * 60;
        $minutesLat = (int)$latitude;
        $secondsLat = round((($latitude - $minutesLat) * 60), 4);
        $degreesLong = (int)$longitude;
        $longitude = ($longitude - $degreesLong) * 60;
        $minutesLong = (int)$longitude;
        $secondsLong = round ((($longitude - $minutesLong) * 60), 4);

        $string = '';
        if ($addSymbol && $symbolFirst)
        {
            $string .= $symbolLat;
        }
        $string .= $degreesLat . self::SYMBOL_DEGREES
                   . $minutesLat . self::SYMBOL_MINUTES
                   . $secondsLat . self::SYMBOL_SECONDS
        ;
        if ($addSymbol && !$symbolFirst)
        {
            $string .= $symbolLat;
        }
        $string .= self::SYMBOL_DELIMITER;
        if ($addSymbol && $symbolFirst)
        {
            $string .= $symbolLong;
        }
        $string .= $degreesLong . self::SYMBOL_DEGREES
                   . $minutesLong . self::SYMBOL_MINUTES
                   . $secondsLong . self::SYMBOL_SECONDS;
        if ($addSymbol && !$symbolFirst)
        {
            $string .= $symbolLong;
        }

        return $string;
    }

    protected function prepareCoodrs (float &$latitude, float &$longitude, string &$symbolLat, string &$symbolLong)
    {
        if ($this->latitude >= 0)
        {
            $symbolLat = self::SYMBOL_NORTH;
            $latitude = $this->latitude;
        }
        else
        {
            $symbolLat = self::SYMBOL_SOUTH;
            $latitude = $this->latitude * (-1);
        }
        if ($this->longitude >= 0)
        {
            $symbolLong = self::SYMBOL_EAST;
            $longitude = $this->longitude;
        }
        else
        {
            $symbolLong = self::SYMBOL_WEST;
            $longitude = $this->longitude * (-1);
        }
    }

    /**
     * Магический метод приведения объекта к массиву
     *
     * @return array
     */
    public function __toArray ()
    {
        return $this->getArray();
    }

    /**
     * Возвращает массив с ключами LATITUDE и LONGITUDE, записывая в них соответствующие значения
     *
     * @return array
     */
    public function getArray ()
    {
        return [
            'LATITUDE' => $this->latitude,
            'LONGITUDE' => $this->longitude
        ];
    }

    /**
     * Магический метод для сериализации данных объекта
     *
     * @return array
     */
    public function __serialize ()
    {
        return $this->getArray();
    }

    /**
     * Магический метод для десериализации данных объекта
     *
     * @param array $arCoordinates
     */
    public function __unserialize (array $arCoordinates)
    {
        $this->latitude = $arCoordinates['LATITUDE'];
        $this->longitude = $arCoordinates['LONGITUDE'];
    }

    /**
     * Устанавливает координаты, принимая их в виде значений типа float
     *
     * @param float $latitude  Широта
     * @param float $longitude Долгота
     *
     * @return $this
     */
    public function setFromFloat (float $latitude, float $longitude)
    {
        $this->latitude = (float) $latitude;
        $this->longitude = (float) $longitude;

        return $this;
    }
}