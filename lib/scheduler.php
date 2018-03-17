<?php
/**
 * Класс для работы с запланированными действиями
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Type\Date;
use Ms\Dobrozhil\Tables;

class Scheduler
{
	/**
	 * Планирует выполнение задания на определенное время
	 *
	 * @param string $strName    Идентификатор задания
	 * @param string $strCode    PHP код задания
	 * @param Date   $startTime  Запланированное время запуска задания
	 * @param int    $iExpireSec Через какое время после запланированного времени запуска задача истечет
	 *
	 * @return bool
	 */
	public static function addTask($strName, $strCode, Date $startTime, $iExpireSec = 1800)
	{
		$strName = strtolower($strName);

		$expire = clone($startTime);
		$expire->modify('+'.(int)$iExpireSec.' second');
		$arData = array(
			'NAME' => $strName,
			'COMMANDS' => $strCode,
			'RUNTIME' => $startTime,
			'EXPIRE' => $expire
		);

		$res = Tables\SchedulerTable::add($arData);
		if ($res->getResult())
		{
			return $strName;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Устанавливает таймер выполнения задания через заданный интервал
	 *
	 * @param string $strName    Имя таймера
	 * @param string $strCode    PHP код таймера
	 * @param int    $iStartIn   Через сколько секунд должен быть запущен таймер
	 * @param int    $iExpireSec Через какое время после запланиованного запуска истечет необходимость запуска таймера
	 *
	 * @return bool
	 */
	public static function addTimer ($strName, $strCode, $iStartIn, $iExpireSec=1800)
	{
		$startTime = new Date();
		$startTime->modify('+'.(int)$iStartIn.' second');

		return static::addTask($strName,$strCode,$startTime,$iExpireSec);
	}

	/**
	 * Проверяет, существует ли запланированная невыполненная задача
	 *
	 * @param string $strName Имя задачи
	 *
	 * @return bool
	 */
	public static function checkTaskExists($strName)
	{
		$res = Tables\SchedulerTable::getOne(array (
			'filter' => array (
				'NAME'=>strtolower($strName),
				'PROCESSED' => false
			)
		));

		return (!!$res);
	}

	/**
	 * Проверяет, существует ли запланированный невыполненный таймер
	 *
	 * @param string $strName Имя таймера
	 *
	 * @return bool
	 */
	public static function checkTimerExists ($strName)
	{
		return static::checkTaskExists($strName);
	}

	/**
	 * Удаляет запланированную задачу из планировщика по ее имени
	 *
	 * @param string $strName Имя задачи
	 *
	 * @return bool
	 */
	public static function deleteTask ($strName)
	{
		if (static::checkTaskExists($strName))
		{
			$res = Tables\SchedulerTable::delete(strtolower($strName),true);
			if ($res->getResult())
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Удаляет установленный таймер по его имени
	 *
	 * @param string $strName Имя таймера
	 *
	 * @return bool
	 */
	public static function deleteTimer ($strName)
	{
		return static::deleteTask($strName);
	}

	/**
	 * Проверяет существование и выполняет запланированные задачи и таймеры
	 */
	public static function runScheduledJobs()
	{
		$now = new Date();

		//Сначала удаляем истекшие задачи
		$arRes = Tables\SchedulerTable::getList(array (
			'select' => 'NAME',
			'filter' => array('<=EXPIRE'=>$now)
		));
		if ($arRes)
		{
			foreach ($arRes as $ar_res)
			{
				Tables\SchedulerTable::delete($ar_res['NAME'],true);
			}
		}

		//Затем в бесконечном цикле проверяем задачи, которые нужно выполнить и выполняем, пока они на закончатся
		while (true)
		{
			$arRes = Tables\SchedulerTable::getOne(array (
				'select' => array ('NAME','CODE'),
				'filter' => array (
					'PROCESSED' => false,
					'>EXPIRE' => $now,
					'<=RUNTIME' => $now
				)
			));
			if (!$arRes)
			{
				//если нет задач, выходим из бесконечного цикла
				break;
			}
			else
			{
				//Устанавливаем флаг запуска задачи
				Tables\SchedulerTable::update(
					$arRes['NAME'],
					array (
						'PROCESSED'=>true,
						'STARTED'=>$now
					)
				);
				//Выполняем код задачи
				$resEval = eval ($arRes['CODE']);
				if ($resEval === false)
				{
					//Здась должна идти запись в лог об ошибке
					Logs::write2Log('Возникла ошибка при исполнении запланированной задачи '.$arRes['NAME']);
				}
			}
		}
	}

}