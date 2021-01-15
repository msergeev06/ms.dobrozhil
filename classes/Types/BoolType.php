<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Core\Lib\Tools;

/**
 * Класс Ms\Dobrozhil\Types\BoolType
 * Тип данные boolean
 */
class BoolType extends TypeAbstract implements General\TypeInterface
{
    public static function getInstance (): BoolType
    {
        return parent::getInstance();
    }

    /**
     * Возвращает название типа
     *
     * @return string
     */
    public function getTitle (): string
    {
        return 'Да/Нет (B)';
    }

    /**
     * Возвращает код типа
     *
     * @return string
     */
    public function getCode (): string
    {
        return General\Constants::TYPE_BOOL;
    }

    /**
     * Конвертирует данные из формата БД в формат кода
     *
     * @param null|string $value
     *
     * @return mixed
     */
    public function processingValueFromDB (string $value = null)
    {
        if (is_null($value))
        {
            return NULL;
        }
        else
        {
            return Tools::validateBoolVal($value);
        }
    }

    /**
     * Конвертирует данные из формата кода в формат БД
     *
     * @param null|mixed $value
     *
     * @return string
     */
    public function processingValueToDB ($value = null): string
    {
        if (is_null($value))
        {
            return NULL;
        }
        else
        {
            return ($value)?'Y':'N';
        }
    }
}