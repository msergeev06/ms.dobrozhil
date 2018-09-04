<?php
/**
 * Интерфейс редакторов кода
 *
 * @package Ms\Dobrozhil
 * @subpackage Interfaces
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Interfaces;

interface CodeEditor
{
	/**
	 * Возвращает код скрипта
	 *
	 * @param string $scriptName Имя скрипта
	 *
	 * @return string|null
	 */
	public function getCode($scriptName);

	/**
	 * Сохраняет код скрипта
	 *
	 * @param string $scriptName Имя скрипта
	 * @param string $code Код
	 *
	 * @return bool
	 */
	public function saveCode($scriptName,$code='');

/*	public static function getName();

	public static function runEditor($code=null);*/

}