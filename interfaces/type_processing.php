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
	/**
	 * @return TypeProcessing
	 */
	public static function getInstance (): TypeProcessing;

	/**
	 * @return string
	 */
	public function getTitle ():string;

	/**
	 * @return string
	 */
	public function getCode (): string;

	/**
	 * @param null|string $value
	 *
	 * @return mixed
	 */
	public function processingValueFromDB (string $value=null);

	/**
	 * @param null|mixed $value
	 *
	 * @return string
	 */
	public function processingValueToDB ($value=null): string;
}