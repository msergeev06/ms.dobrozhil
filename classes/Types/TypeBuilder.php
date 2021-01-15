<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Core\Api\Events;
use Ms\Core\Entity\Events\EventHandler;
use Ms\Dobrozhil\General\Multiton;
use Ms\Dobrozhil\Types\General\Constants;
use Ms\Dobrozhil\Types\General\TypeInterface;

/**
 * Класс Ms\Dobrozhil\Types\TypeBuilder
 * Создатель классов типа TypeInterface
 */
class TypeBuilder extends Multiton
{
    /**
     * Возвращает обработчик для указанного кода типа
     *
     * @param string $type
     *
     * @return TypeInterface
     */
    public function create (string $type)
    {
        $type = strtoupper($type);
        switch ($type)
        {
            case Constants::TYPE_STRING:
                return StringType::getInstance();
            case Constants::TYPE_S_COLOR:
                return ColorType::getInstance();
            case Constants::TYPE_S_COORDINATES:
                return CoordinatesType::getInstance();
            case Constants::TYPE_S_DATE:
                return DateType::getInstance();
            case Constants::TYPE_S_DATETIME:
                return DatetimeType::getInstance();
            case Constants::TYPE_S_TIME:
                return TimeType::getInstance();
            case Constants::TYPE_BOOL:
                return BoolType::getInstance();
            case Constants::TYPE_NUMERIC:
                return FloatType::getInstance();
            case Constants::TYPE_N_FILE:
                return FileType::getInstance();
            case Constants::TYPE_N_INT:
                return IntType::getInstance();
            case Constants::TYPE_N_TIMESTAMP:
                return TimestampType::getInstance();
            default:
                return $this->getModuleTypeHandler($type);
        }
    }

    /**
     * Возвращает список кодов доступных типов
     *
     * @return array
     */
    public function getTypeCodes ()
    {
        $arTypeCodes = $this->getSystemTypeCodes();
        $arModuleTypeCodes = $this->getModulesTypeCodes();

        return array_merge($arTypeCodes, $arModuleTypeCodes);
    }

    /**
     * Возвращает TRUE, если существует обработчик для данного типа
     *
     * @param string $code Код типа
     *
     * @return bool
     */
    public function isRightTypeCode (string $code)
    {
        return in_array(strtoupper($code),$this->getTypeCodes());
    }

    /**
     * Возвращает список кодов типов системных модулей
     *
     * @return array
     */
    protected function getSystemTypeCodes ()
    {
        try
        {
            $const = new \ReflectionClass(Constants::class);
        }
        catch (\ReflectionException $e)
        {
            return [];
        }
        $arTypesValues = [];
        if (!empty($const->getConstants()))
        {
            /** @var  $constant */
            foreach ($const->getConstants() as $name => $value)
            {
                if (strpos($name,'TYPE_') !== false && !in_array($value, $arTypesValues))
                {
                    $arTypesValues[] = $value;
                }
            }
        }

        return $arTypesValues;
    }

    /**
     * Возвращает список кодов типов сторонних модулей
     *
     * @return array
     */
    protected function getModulesTypeCodes ()
    {
        $arTypeCodes = [];
        Events::getInstance()->runEvents('ms.dobrozhil','OnGetTypeCodesList',[&$arTypeCodes]);

        return $arTypeCodes;
    }

    /**
     * Возвращает обработчик для заданного типа, либо, если он не был найден для типа S - строка
     *
     * @param string $type Тип значения
     *
     * @return TypeInterface
     */
    protected function getModuleTypeHandler (string $type)
    {
        $type = strtoupper($type);
        $handler = null;
        Events::getInstance()->runEvents('ms.dobrozhil','OnGetTypeHandler',[&$handler, $type]);
        if (is_null($handler) || !is_callable($handler) || !($handler instanceof TypeInterface))
        {
            return StringType::getInstance();
        }

        return $handler;
    }
}