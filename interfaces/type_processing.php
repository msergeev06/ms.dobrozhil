<?php
/**
 * Интерфейс обработки свойств различных типов
 *
 * @package Ms\Dobrozhil
 * @subpackage Interfaces
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Interfaces;

interface TypeProcessing
{
	public static function getInstance ();

	public function processingValueFromDB (string $value);

	public function processingValueToDB ($value): string;
}