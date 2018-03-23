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
	public static function getCode($scriptName);

	public static function getName();

	public static function runEditor($code=null);
}