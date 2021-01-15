<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\General;

/**
 * Класс Ms\Dobrozhil\General\Options
 * Адаптер класса \Ms\Core\Api\Options
 */
class Options extends \Ms\Core\Api\Options
{
    public function getOptionString (string $optionName, string $optionDefaultValue = null)
    {
        return parent::getOptionString(
            'ms.dobrozhil', $optionName, $optionDefaultValue
        );
    }

    public function getOptionInt (string $optionName, int $optionDefaultValue = null)
    {
        return parent::getOptionInt(
            'ms.dobrozhil', $optionName, $optionDefaultValue
        );
    }

    public function getOptionFloat (string $optionName, float $optionDefaultValue = null)
    {
        return parent::getOptionFloat(
            'ms.dobrozhil', $optionName, $optionDefaultValue
        );
    }

    public function getOptionBool (string $optionName, bool $optionDefaultValue = null)
    {
        return parent::getOptionBool(
            'ms.dobrozhil', $optionName, $optionDefaultValue
        );
    }

    public function setOption (string $optionName, $optionValue = null)
    {
        return parent::setOption('ms.dobrozhil', $optionName, $optionValue);
    }

    public function getOptionFullName (string $optionName)
    {
        return parent::getOptionFullName('ms.dobrozhil', $optionName);
    }
}