<?php
/**
 * Класс объектов режимов работы
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Objects
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Objects;

class NobodyHomeMode extends OperationModes
{
	public function __construct ($objectName)
	{
		parent::__construct($objectName);
	}
}