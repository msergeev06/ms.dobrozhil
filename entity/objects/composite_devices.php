<?php
/**
 *
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Objects
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2019 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Objects;

class CompositeDevices extends Devices
{
	public function __construct ($objectName)
	{
		parent::__construct($objectName);
	}
}