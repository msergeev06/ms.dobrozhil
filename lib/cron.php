<?php
/**
 * Класс для работы с crontab и его эмуляцией
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\IO\Files;
use Ms\Core\Lib\Logs;
use Ms\Core\Entity\ErrorCollection;
use Ms\Dobrozhil\Tables\CronTable;

class Cron
{
	private static $arMinute = null;
	private static $arHour = null;
	private static $arDay = null;
	private static $arMonth = null;
	private static $arDayOfWeek = null;
	private static $arWeekEnd = null;
	private static $arMonths = array(
		'jan' => 1,
		'feb' => 2,
		'mar' => 3,
		'apr' => 4,
		'may' => 5,
		'jun' => 6,
		'jul' => 7,
		'aug' => 8,
		'sep' => 9,
		'oct' => 10,
		'nov' => 11,
		'dec' => 12
	);
	private static $arDaysOfWeek = array(
		'sun' => 0,
		'mon' => 1,
		'tue' => 2,
		'wed' => 3,
		'thu' => 4,
		'fri' => 5,
		'sat' => 6
	);

	/**
	 * @var null|ErrorCollection
	 */
	private static $errorCollection = null;

	/**
	 * Каждую минуту проверяет необходимость выполнить запланированные задачи
	 */
	public static function OnNewMinuteHandler ()
	{
		while (true)
		{
			$arRes = CronTable::getOne(
				array (
					'select' => array(
						'ID',
						'CRON_EXPRESSION',
						'CODE_CONDITION',
						'CODE',
						'SCRIPT_NAME'
					),
					'filter' => array (
						'ACTIVE'=>true,
						'<=NEXT_RUN'=>new Date(),
						'RUNNING'=>false
					),
					'order' => array ('NEXT_RUN'=>'ASC')
				)
			);
			if (!$arRes)
			{
				return;
			}
			else
			{
				static::setRunning($arRes['ID']);
				$bRun = true;
				if (!is_null($arRes['CODE_CONDITION']))
				{
					try
					{
						$res = eval($arRes['CODE_CONDITION']);
						if ($res !== true)
						{
							$bRun = false;
						}
					}
					catch (\Throwable $e)
					{
						Logs::write2Log('Возникла ошибка при попытке исполнения CODE_CONDITION в Cron Задаче #'.$arRes['ID'].'. Задача деактивирована.');
						static::deactivateJob($arRes['ID']);
						continue;
					}
				}

				if ($bRun)
				{
					$bScriptRun = false;
					if (!is_null($arRes['SCRIPT_NAME']))
					{
						//TODO: Тут должна быть проверка, что скрипта не существует, вместо нее TRUE
						if (TRUE && is_null($arRes['CODE']))
						{
							Logs::write2Log('Для cron задачи указано несуществующее имя скрипта и отвутствует PHP код.'
								.'Задача #'.$arRes['ID'].' не может быть выполнена и будет деактивирована');
							static::deactivateJob($arRes['ID']);
							continue;
						}
						else
						{
							//Тут будет попытка выполнить скрипт (ошибки обрабатываются уже там)
							$bScriptRun = true;
						}
					}

					//Если не был выполнен скрипт, проверяем код
					if (!$bScriptRun)
					{
						try
						{
							eval($arRes['CODE']);
						}
						catch (\Throwable $e)
						{
							Logs::write2Log('Возникла ошибка при попытке исполнения PHP кода задания #'.$arRes['ID'].'. Задание деактивировано');
							static::deactivateJob($arRes['ID']);
							continue;
						}
					}
				}

				//Планируем следующий запуск
				$nextRun = static::parseCronExpression($arRes['CRON_EXPRESSION']);
				if (!$nextRun)
				{
					Logs::write2Log('Возникла ошибка при планировании следующего выполнения задачи #'.$arRes['ID'].'. Задача деактивирована');
					static::deactivateJob($arRes['ID']);
					continue;
				}

				$arUpdate = array (
					'NEXT_RUN' => $nextRun,
					'LAST_RUN' => new Date()
				);
				CronTable::update($arRes['ID'],$arUpdate);
			}
		}
	}

	/**
	 * При старте системы переназначает все автивные запланированные задачи на правильное время
	 */
	public static function OnStartUpHandler ()
	{
		$arRes = CronTable::getList(
			array (
				'select' => array('ID','CRON_EXPRESSION'),
				'filter' => array (
					'ACTIVE' => true
				)
			)
		);
		if ($arRes)
		{
			foreach ($arRes as $ar_res)
			{
				$nextRun = static::parseCronExpression($ar_res['CRON_EXPRESSION']);
				CronTable::update($ar_res['ID'],array ('NEXT_RUN'=>$nextRun));
			}
		}
	}

	/**
	 * Удаляет запланированную задачу
	 *
	 * @param int $jobID ID задачи
	 *
	 * @return \Ms\Core\Entity\Db\DBResult
	 */
	public static function deleteJob ($jobID)
	{
		return CronTable::delete($jobID,true);
	}

	/**
	 * Возвращает ID задачи по ее имени
	 *
	 * @param string $jobName Имя задачи
	 *
	 * @return array|bool|string
	 */
	public static function getIdByName ($jobName)
	{
		return CronTable::getOne(array ('select'=>'ID','filter'=>array('NAME'=>$jobName)));
	}

	/**
	 * Деактивирует указанное задание
	 *
	 * @param int $jobID ID задачи
	 */
	public static function deactivateJob ($jobID)
	{
		CronTable::update($jobID,array ('ACTIVE'=>false));
	}

	/**
	 * Акивирует указанное задание
	 *
	 * @param int $jobID ID задания
	 */
	public static function activateJob ($jobID)
	{
		CronTable::update($jobID,array ('ACTIVE'=>true));
	}

	/**
	 * Добавляет новое задание в таблицу
	 *
	 * @param string        $cronExpression     Расписание задания в формате cron
	 * @param null|string   $name               Имя задания, чтобы можно было обращатся к нему из кода
	 * @param null|string   $code               PHP код задания, который будет выполнен в назначенное время,
	 *                                          и при исполнении или отсутствии дополнительного условия
	 * @param null|string   $scriptName         Имя скрипта, если задано и скрипт существует, будет выполнен
	 *                                          вместо кода задания
	 * @param null|string   $note               Описание задания
	 * @param null|string   $codeCondition      Дополнительное условие, в виде PHP кода, возвращающего true или false
	 *                                          задание будет выполнено, если данный код вернет true
	 *
	 * @return bool|int
	 */
	public static function addJob ($cronExpression, $name=null, $code=null,$scriptName=null,$note=null,$codeCondition=null)
	{
		$arAdd = array ();
		if (!Classes::checkName($name))
		{
			self::addError('Имя задания содержит недопустимые символы','WRONG_SYMBOLS');
			return false;
		}
		else
		{
			$arAdd['NAME'] = $name;
		}

		$checkCron = explode(' ',$cronExpression);
		if (count($checkCron)<5)
		{
			self::addError('Неверный формат выражения планировщика cron','WRONG_EXPRESSION');
			return false;
		}
		else
		{
			$arAdd['CRON_EXPRESSION'] = $cronExpression;
		}

		if (!is_null($note))
		{
			$arAdd['NOTE'] = $note;
		}

		if (!is_null($codeCondition))
		{
			$arAdd['CODE_CONDITION'] = $codeCondition;
		}

		if (!is_null($code))
		{
			$arAdd['CODE'] = $code;
		}

		if (!is_null($scriptName))
		{
			//TODO: Тут должна быть проверка на существование скрипта с заданным именем
			if (false)
			{
				self::addError('Скрипта с заданным именем не существует','SCRIPT_NOT_FOUND');
			}
			else
			{
				$arAdd['SCRIPT_NAME'] = $scriptName;
			}
		}

		if (!isset($arAdd['CODE']) && !isset($arAdd['SCRIPT_NAME']))
		{
			self::addError('Для создания задания необходимо указать или код или имя скрипта','EMPTY_CODE_OR_SCRIPT_NAME');
			return false;
		}

		$arAdd['NEXT_RUN'] = static::parseCronExpression($arAdd['CRON_EXPRESSION']);
		$arAdd['CHANGED'] = new Date();

		$res = CronTable::add($arAdd);
		if ($res->getResult())
		{
			return $res->getInsertId();
		}
		else
		{
			return false;
		}
	}






	/**
	 * Генерирует файл, с помощью которого устанавливается crontab
	 *
	 * @param string $php Путь к интерпретатору php
	 *
	 * @return bool
	 */
	public static function generateCrontab ($php='/usr/bin/php7.0')
	{
		$cronHandlersDir = Application::getInstance()->getSettings()->getModulesRoot().'/ms.dobrozhil/cron';
		$logsDir = Logs::getLogsDir();
		$data = "# Edit this file to introduce tasks to be run by cron.\n"
			."#\n"
			."# Each task to run has to be defined through a single line\n"
			."# indicating with different fields when the task will be run\n"
			."# and what command to run for the task\n"
			."#\n"
			."# To define the time you can provide concrete values for\n"
			."# minute (m), hour (h), day of month (dom), month (mon),\n"
			."# and day of week (dow) or use '*' in these fields (for 'any').#\n"
			."# Notice that tasks will be started based on the cron's system\n"
			."# daemon's notion of time and timezones.\n"
			."#\n"
			."# Output of the crontab jobs (including errors) is sent through\n"
			."# email to the user the crontab file belongs to (unless redirected).\n"
			."#\n"
			."# For example, you can run a backup of all your user accounts\n"
			."# at 5 a.m every week with:\n"
			."# 0 5 * * 1 tar -zcf /var/backups/home.tgz /home/\n"
			."#\n"
			."# For more information see the manual pages of crontab(5) and cron(8)\n"
			."#\n"
			."# m h  dom mon dow   command\n\n";
		$name = 'on_start_up';
		$data .= "# Event: OnStartUp\n";
		$data .= "@reboot ".$php." -f ".$cronHandlersDir."/".$name.".php >> ".$logsDir."/crontab_".$name.".log &\n";
		$name = 'on_new_minute';
		$data .= "# Every minute Event: OnNewMinute\n";
		$data .= "* * * * * ".$php." -f ".$cronHandlersDir."/".$name.".php >> ".$logsDir."/crontab_".$name.".log &\n";
		$name = 'on_new_hour';
		$data .= "# Every hour in 1 minute Event: OnNewHour\n";
		$data .= "1 */1 * * * ".$php." -f ".$cronHandlersDir."/".$name.".php >> ".$logsDir."/crontab_".$name.".log &\n";
		$name = 'on_new_day';
		$data .= "# Every day in 0 hour 2 minute Event: OnNewDay\n";
		$data .= "2 0 */1 * * ".$php." -f ".$cronHandlersDir."/".$name.".php >> ".$logsDir."/crontab_".$name.".log &\n";
		$name = "on_new_week";
		$data .= "# Every week in monday 0 hour 3 minute Event: OnNewWeek\n";
		$data .= "3 0 * * 1 ".$php." -f ".$cronHandlersDir."/".$name.".php >> ".$logsDir."/crontab_".$name.".log &\n";
		$name = 'on_new_month';
		$data .= "# Every month in 1 day 0 hour 4 minute Event: OnNewMonth\n";
		$data .= "4 0 1 * * ".$php." -f ".$cronHandlersDir."/".$name.".php >> ".$logsDir."/crontab_".$name.".log &\n";
		$name = 'on_new_year';
		$data .= "# Every year in 1 Jan 0:00 Event: OnNewYear\n";
		$data .= "0 0 1 1 * ".$php." -f ".$cronHandlersDir."/".$name.".php >> ".$logsDir."/crontab_".$name.".log &\n";

		return !!Files::saveFile($cronHandlersDir.'/crontab.txt',$data);
	}

	/**
	 * Устанавливает crontab. Требуется предварительно сгенерировать файл методом generateCrontab
	 *
	 * @return bool
	 */
	public static function setCrontab ()
	{
		$contabFile = Application::getInstance()->getSettings()->getModulesRoot().'/ms.dobrozhil/cron/crontab.txt';
		if (file_exists($contabFile))
		{
			exec('crontab '.$contabFile,$output);
			return true;
		}

		return false;
	}





	/**
	 * Парсит cron выражение и возвращает время следующего запуска задания
	 *
	 * @param string $cronExpression Cron выражение (расписание)
	 *
	 * @return bool|Date
	 */
	public static function parseCronExpression ($cronExpression)
	{
		$cronExpression = strtolower($cronExpression);
		$arTmp = explode(' ',$cronExpression);
		if (count($arTmp) < 5)
		{
			return false;
		}

		static::$arMinute = self::cronInterpret($arTmp[0],0,59);
		static::$arHour = self::cronInterpret($arTmp[1],0,23);
		static::$arDay = self::cronInterpret($arTmp[2],1,31);
		static::$arMonth = self::cronInterpret($arTmp[3],1,12,static::$arMonths);
		static::$arDayOfWeek = self::cronInterpret($arTmp[4],0,6,static::$arDaysOfWeek);
		static::$arWeekEnd = (isset($arTmp[5]))
			? self::cronInterpret($arTmp[5],0,1)
			: self::cronInterpret('*',0,1);
		unset($arTmp);

//		echo $cronExpression.'<br>';
		$now = new Date();
//		echo $now."<br>";
		$now->modify('+1 minute');
		self::checkTime($now);
		self::checkMonth($now);
		self::checkDay($now);
//		echo $now."<br><hr><br>";

		return $now;
	}



	/**
	 * Возвращает список ошибок, возникших в ходе работы методов класса
	 *
	 * @return ErrorCollection|null
	 */
	public static function getErrors ()
	{
		return static::$errorCollection;
	}

	/**
	 * Добавляет новую ошибку в коллекцию
	 *
	 * @param string $sMessage Сообщение об ошибке
	 * @param string $sCode Код ошибки
	 */
	private static function addError($sMessage, $sCode=null)
	{
		if (is_null(static::$errorCollection))
		{
			static::$errorCollection = new ErrorCollection();
		}
		static::$errorCollection->setError($sMessage,$sCode);
	}

	private static function checkTime(Date &$now)
	{
		$min = (int)$now->format('i');
		$hour = (int)$now->format('H');
		for ($i=0;$i<count(static::$arHour);$i++)
		{
			if (static::$arHour[$i]>=$hour)
			{
				for($j=0;$j<count(static::$arMinute);$j++)
				{
					if (static::$arMinute[$j]>=$min)
					{
//						echo 'arMin=',static::$arMinute[$j],'>=',$min.'<br>';
						$now = $now->setTime(static::$arHour[$i],static::$arMinute[$j]);
						return;
					}
				}
				$min = 0;
			}
		}
		$now = $now->setTime(0,0);
		$now->modify('+1 day');
		self::checkTime($now);
	}

	private static function checkMonth (Date &$now)
	{
		$month = (int)$now->format('m');
		for ($i=0; $i<count(static::$arMonth); $i++)
		{
			if (static::$arMonth[$i]>=$month)
			{
//				echo 'arMonth=',static::$arMonth[$i],'>=',$month,'<br>';
				$now = $now->setMonth(static::$arMonth[$i]);
				return;
			}
		}
		$now = $now->modify('+1 year');
		$now = $now->setMonth(1);
		self::checkMonth($now);
	}

	private static function checkDay (Date &$now)
	{
		$day = (int)$now->format('d');
		for ($i=0; $i<count(static::$arDay);$i++)
		{
			if (static::$arDay[$i]>=$day)
			{
//				echo 'arDay=',static::$arDay[$i],'>=',$day,'<br>';
				$dow = self::getDowDay($now,static::$arDay[$i]);
				if (in_array($dow,static::$arDayOfWeek))
				{
//					echo 'DoW=',$dow,' in[',implode(',',static::$arDayOfWeek),']<br>';
					if (count(static::$arWeekEnd) == 2)
					{
						$now = $now->setDay(static::$arDay[$i]);
//						echo 'Не важно выходной или рабочий<br>';
						return;
					}
					else
					{
						$strDate = $now->format('Y-m-');
						if (static::$arDay[$i] < 10)
						{
							$strDate .= '0';
						}
						$strDate .= static::$arDay[$i];
						$check = new Date($strDate);
						$bWeekEnd = $check->isWeekEnd();
						//Если нужен выходной, и этот день выходной
						if (static::$arWeekEnd[0]==1 && $bWeekEnd)
						{
//							echo '['.$check->format('w'),'] выходной<br>';
							$now = $now->setDay(static::$arDay[$i]);
							return;
						}
						//Если нужен рабочий и этот день рабочий
						elseif (static::$arWeekEnd[0]==0 && !$bWeekEnd)
						{
//							echo '['.$check->format('w'),'] рабочий<br>';
							$now = $now->setDay(static::$arDay[$i]);
							return;
						}
					}
				}
			}
		}
		$now = $now->setDay(1);
		$now = $now->modify('+1 month');
		self::checkMonth($now);
		self::checkDay($now);
	}

	private static function getDowDay (Date $now,$day)
	{
		$strDate = $now->format('Y-m-');
		if ((int)$day < 10)
		{
			$strDate .= '0';
		}
		$strDate .= (int)$day;
		$check = new Date($strDate);

		return $check->format('w');
	}

	private static function cronInterpret ($expression, $minValue, $maxValue, $arNames=array())
	{
		$arReturn = array ();

		if ((!is_string($expression) && !is_int($expression)) || $expression == '*')
		{
			for ($i=$minValue;$i<=$maxValue;$i++)
			{
				$arReturn[] = $i;
			}

			return $arReturn;
		}

		if (!empty($arNames))
		{
			foreach ($arNames as $code=>$value)
			{
				$expression = str_replace($code,$value,$expression);
			}
		}

		$arSections = explode(',',$expression);
		foreach($arSections as $section)
		{
			if (strpos($section,'/')!==false)
			{
				list($start,$factor) = explode('/',$section);
				if($start=='*')
				{
					$start = $minValue;
				}
				for ($i=(int)$start; $i<=$maxValue; $i+=(int)$factor)
				{
					$arReturn[] = $i;
				}
			}
			elseif (strpos($section,'-')!==false)
			{
				list($start,$stop) = explode('-',$section);
				if ($start<$minValue)
				{
					$start = $minValue;
				}
				if ($stop>$maxValue)
				{
					$stop = $maxValue;
				}
				for ($i=(int)$start; $i<=(int)$stop; $i++)
				{
					$arReturn[] = $i;
				}
			}
			else
			{
				$arReturn[] = (int)$section;
			}
		}
		sort($arReturn);

		return $arReturn;
	}

	private static function setRunning ($jobID)
	{
		CronTable::update($jobID,array ('RUNNING'=>true));
	}

}
