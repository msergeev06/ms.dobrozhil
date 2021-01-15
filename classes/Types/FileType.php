<?php
/**
 * Обработка свойств объектов типа file
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Core\Lib\Tools;

/**
 * Класс Ms\Dobrozhil\Types\FileType
 * Тип значения "Файл"
 */
class FileType extends TypeAbstract implements General\TypeInterface
{
    public static function getInstance (): FileType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
	{
		return 'Файл (N:FILE)';
	}

	public function getCode (): string
	{
		return General\Constants::TYPE_N_FILE;
	}


	public function processingValueFromDB (string $value=null)
	{
		if (is_null($value))
		{
			return NULL;
		}

		return Tools::validateIntVal($value);
	}

	public function processingValueToDB ($value=NULL): string
	{
		if (is_null($value))
		{
			return NULL;
		}

		return (string)Tools::validateIntVal($value);
	}
}