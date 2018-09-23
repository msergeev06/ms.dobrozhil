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

/**
 * Interface TypeProcessing
 *
 * @package Ms\Dobrozhil\Interfaces
 */
interface TypeProcessing
{
	public static function getInstance (): TypeProcessing;

	public function getTitle ():string;

	public function getCode (): string;

	public function processingValueFromDB (string $value);

	public function processingValueToDB ($value): string;
}