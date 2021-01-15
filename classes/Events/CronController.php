<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Events;

use Ms\Contacts\Adapters\ErrorCollection;
use Ms\Core\Api\ApiAdapter;
use Ms\Core\Entity\Db\Result\DBResult;
use Ms\Core\Entity\Errors\FileLogger;
use Ms\Core\Entity\System\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\IO\Files;
use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\General\Multiton;

/**
 * Класс Ms\Dobrozhil\Events\CronController
 * Обработчик заданий кронтаб
 */
class CronController extends Multiton
{
    const MONTHS = [
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
    ];
    const DAYS_OF_WEEK = [
        'sun' => 0,
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6
    ];

    /** @var null|array */
    private $arMinute = null;
    /** @var null|array */
    private $arHour = null;
    /** @var null|array */
    private $arDay = null;
    /** @var null|array */
    private $arMonth = null;
    /** @var null|array */
    private $arDayOfWeek = null;
    /** @var null|array */
    private $arWeekEnd = null;

    /** @var ErrorCollection */
    private $errorCollection = null;
    /** @var FileLogger */
    protected $logger = null;

    protected function __construct ()
    {
        $this->errorCollection = new ErrorCollection();
        $this->logger = new FileLogger('ms.dobrozhil','debug');
    }

    /**
     * Инициирует событие OnStartUp, вызывается кроном при старте системы
     *
     * @param null|bool $bGoodStart Флаг запланированного перезапуска системы
     */
    public function initOnStartUp ($bGoodStart=false)
    {
        $this->logger->addMessage('Активация события OnStartUp');

        if (!Application::getInstance()->getConnection()->isSuccess())
        {
            $this->logger->addMessage('База данных не готова, ожидаем подключения...');
            die();
        }

        if (!is_null($bGoodStart))
        {
            ApiAdapter::getInstance()
                      ->getEventsApi()
                        ->runEvents('ms.dobrozhil','OnStartUp',[$bGoodStart])
            ;
        }
        else
        {
            if (Application::getInstance()->getConnection()->isSuccess())
            {
                ApiAdapter::getInstance()
                          ->getEventsApi()
                          ->runEvents('ms.dobrozhil','OnStartUp')
                ;
            }
        }
    }

    /**
     * Инициирует событие OnNewMinute, вызывается кроном каждую минуту
     */
    public function initOnNewMinute ()
    {
        ApiAdapter::getInstance()
                  ->getEventsApi()
                  ->runEvents('ms.dobrozhil','OnNewMinute')
        ;
    }

    /**
     * Инициирует событие OnNewHour, вызывается кроном каждый час
     */
    public function initOnNewHour ()
    {
        $this->logger->addMessage('Активация события OnNewHour');

        ApiAdapter::getInstance()
                  ->getEventsApi()
                  ->runEvents('ms.dobrozhil','OnNewHour')
        ;
    }

    /**
     * Инициирует событие OnNewDay, вызывается в начале каждого дня при запуске крона каждый час
     */
    public function initOnNewDay()
    {
        $this->logger->addMessage('Активация события OnNewDay');

        ApiAdapter::getInstance()
                  ->getEventsApi()
                  ->runEvents('ms.dobrozhil','OnNewDay')
        ;
    }

    /**
     * Инициирует событие OnNewWeek, вызывается в начале каждой недели при запуске крона каждый час
     */
    public function initOnNewWeek ()
    {
        $this->logger->addMessage('Активация события OnNewWeek');

        ApiAdapter::getInstance()
                  ->getEventsApi()
                  ->runEvents('ms.dobrozhil','OnNewWeek')
        ;
    }

    /**
     * Инициирует событие OnNewMonth, вызывается в начале месяца при запуске крона каждый час
     */
    public function initOnNewMonth()
    {
        $this->logger->addMessage('Активация события OnNewMonth');

        ApiAdapter::getInstance()
                  ->getEventsApi()
                  ->runEvents('ms.dobrozhil','OnNewMonth')
        ;
    }

    /**
     * Инициирует событие OnNewYear, вызывается в начале года при запуске крона каждый час
     */
    public function initOnNewYear ()
    {
        $this->logger->addMessage('Активация события OnNewYear');

        ApiAdapter::getInstance()
                  ->getEventsApi()
                  ->runEvents('ms.dobrozhil','OnNewYear')
        ;
    }

    /**
     * Каждую минуту проверяет необходимость выполнить запланированные задачи
     * TODO: Реализовать обработчик события onNewMinuteHandler
     */
    public static function onNewMinuteHandler ()
    {
/*        while (true)
        {
            $arRes = CronTable::getOne(
                array (
                    'select' => array(
                        'ID',
                        'CRON_EXPRESSION',
                        'CODE_CONDITION'
                        //'CODE',
                        //'SCRIPT_NAME'
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
                        try
                        {
                            $logger = (new FileLogger('ms.dobrozhil'));
                            $logger->addMessage('Возникла ошибка при попытке исполнения CODE_CONDITION в Cron Задаче #'.$arRes['ID'].'. Задача деактивирована.');
                        }
                        catch (SystemException $e){}

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
                            try
                            {
                                $logger = (new FileLogger('ms.dobrozhil'));
                                $logger->addMessage('Для cron задачи указано несуществующее имя скрипта и отвутствует PHP код.'
                                                    .'Задача #'.$arRes['ID'].' не может быть выполнена и будет деактивирована');
                            }
                            catch (SystemException $e){}

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
                            try
                            {
                                $logger = (new FileLogger('ms.dobrozhil'));
                                $logger->addMessage('Возникла ошибка при попытке исполнения PHP кода задания #'.$arRes['ID'].'. Задание деактивировано');
                            }
                            catch (SystemException $e){}

                            static::deactivateJob($arRes['ID']);
                            continue;
                        }
                    }
                }

                //Планируем следующий запуск
                $nextRun = static::parseCronExpression($arRes['CRON_EXPRESSION']);
                if (!$nextRun)
                {
                    try
                    {
                        $logger = (new FileLogger('ms.dobrozhil'));
                        $logger->addMessage('Возникла ошибка при планировании следующего выполнения задачи #'.$arRes['ID'].'. Задача деактивирована');
                    }
                    catch (SystemException $e){}

                    static::deactivateJob($arRes['ID']);
                    continue;
                }

                $arUpdate = array (
                    'NEXT_RUN' => $nextRun,
                    'LAST_RUN' => new Date()
                );
                CronTable::update($arRes['ID'],$arUpdate);
            }
        }*/
    }

    /**
     * При старте системы переназначает все автивные запланированные задачи на правильное время
     * TODO: Реализовать обработчик события onStartUpHandler
     */
    public static function onStartUpHandler ()
    {
/*        $arRes = CronTable::getList(
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
        }*/
    }

    /**
     * //TODO: Реализовать метод deleteJob
     * Удаляет запланированную задачу
     *
     * @param int $jobID ID задачи
     *
     * @return DBResult
     */
    public function deleteJob ($jobID)
    {
        // return CronTable::delete($jobID,true);
        return new DBResult();
    }

    /**
     * TODO: Реализовать метод getIdByName
     * Возвращает ID задачи по ее имени
     *
     * @param string $jobName Имя задачи
     *
     * @return array|bool|string
     */
    public function getIdByName ($jobName)
    {
        // return CronTable::getOne(array ('select'=>'ID','filter'=>array('NAME'=>$jobName)));
        return 0;
    }

    /**
     * TODO: Реализовать метод deactivateJob
     * Деактивирует указанное задание
     *
     * @param int $jobID ID задачи
     *
     */
    public function deactivateJob ($jobID)
    {
        // CronTable::update($jobID,array ('ACTIVE'=>false));
    }

    /**
     * TODO: Реализовать метод activateJob
     * Акивирует указанное задание
     *
     * @param int $jobID ID задания
     *
     */
    public function activateJob ($jobID)
    {
        // CronTable::update($jobID,array ('ACTIVE'=>true));
    }

    /**
     * TODO: Реализовать метод addJob
     * Добавляет новое задание в таблицу
     *
     * @param string      $cronExpression       Расписание задания в формате cron
     * @param null|string $name                 Имя задания, чтобы можно было обращатся к нему из кода
     * @param null|string $code                 PHP код задания, который будет выполнен в назначенное время,
     *                                          и при исполнении или отсутствии дополнительного условия
     * @param null|string $scriptName           Имя скрипта, если задано и скрипт существует, будет выполнен
     *                                          вместо кода задания
     * @param null|string $note                 Описание задания
     * @param null|string $codeCondition        Дополнительное условие, в виде PHP кода, возвращающего true или false
     *                                          задание будет выполнено, если данный код вернет true
     *
     */
    public function addJob ($cronExpression, $name=null, $code=null,$scriptName=null,$note=null,$codeCondition=null)
    {
/*        $arAdd = array ();
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
            if (Scripts::issetScript($scriptName, false, false, true))
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
        }*/
    }

    /**
     * TODO: Реализовать метод issetCronJobByID
     * Проверяет существование задачи в кроне по ее ID
     *
     * @param int $iCronJobID ID задачи крона
     *
     * @return bool Если задача существует, возвращает true, иначе false
     */
    public function issetCronJobByID ($iCronJobID)
    {
/*        $iCronJobID = (int)$iCronJobID;
        if ($iCronJobID<=0)
        {
            return false;
        }
        $res = CronTable::getById($iCronJobID);

        return (!!$res);*/
        return false;
    }

    /**
     * Генерирует файл, с помощью которого устанавливается crontab
     *
     * @param string $php Путь к интерпретатору php
     *
     * @return bool
     */
    public function generateCrontab ($php='/usr/bin/php7.0')
    {
        $cronHandlersDir = Application::getInstance()->getSettings()->getModulesRoot().'/ms.dobrozhil/cron';
        $logsDir = Application::getInstance()->getSettings()->getDirLogs();

        $crontabData = <<<EOL
# Edit this file to introduce tasks to be run by cron.
#
# Each task to run has to be defined through a single line
# indicating with different fields when the task will be run
# and what command to run for the task
#
# To define the time you can provide concrete values for
# minute (m), hour (h), day of month (dom), month (mon),
# and day of week (dow) or use '*' in these fields (for 'any').#
# Notice that tasks will be started based on the cron's system
# daemon's notion of time and timezones.
#
# Output of the crontab jobs (including errors) is sent through
# email to the user the crontab file belongs to (unless redirected).
#
# For example, you can run a backup of all your user accounts
# at 5 a.m every week with:
# 0 5 * * 1 tar -zcf /var/backups/home.tgz /home/
#
# For more information see the manual pages of crontab(5) and cron(8)
#
# m h  dom mon dow   command
                
# EventHandler: OnStartUp
@reboot     $php -f   $cronHandlersDir/on_start_up.php >> $logsDir/crontab_on_start_up.log &
# Every minute EventHandler: OnNewMinute
* * * * *   $php -f   $cronHandlersDir/on_new_minute.php >> $logsDir/crontab_on_new_minute.log &
# Every hour in 1 minute EventHandler: OnNewHour
1 */1 * * * $php -f   $cronHandlersDir/on_new_hour.php >> $logsDir/crontab_on_new_hour.log &
# Every day in 0 hour 2 minute EventHandler: OnNewDay
2 0 */1 * * $php -f   $cronHandlersDir/on_new_day.php >> $logsDir/crontab_on_new_day.log &
# Every week in monday 0 hour 3 minute EventHandler: OnNewWeek
3 0 * * 1   $php -f   $cronHandlersDir/on_new_week.php >> $logsDir/crontab_on_new_week.log &
# Every month in 1 day 0 hour 4 minute EventHandler: OnNewMonth
4 0 1 * *   $php -f   $cronHandlersDir/on_new_month.php >> $logsDir/crontab_on_new_month.log &
# Every year in 1 Jan 0:00 EventHandler: OnNewYear
0 0 1 1 *   $php -f   $cronHandlersDir/on_new_year.php >> $logsDir/crontab_on_new_year.log &

EOL;
/*        $crontabData = Tools::strReplace(
            [
                'PHP_PATH' => $php,
                'CRON_HANDLERS_DIR' => $cronHandlersDir,
                'LOGS_DIR' => $logsDir
            ],
            $crontabData
        );*/

        return !!Files::saveFile($cronHandlersDir.'/crontab.txt',$crontabData);
    }

    /**
     * Устанавливает crontab. Требуется предварительно сгенерировать файл методом generateCrontab
     *
     * @return bool
     */
    public static function setCrontab ()
    {
        $crontabFile = Application::getInstance()->getSettings()->getModulesRoot().'/ms.dobrozhil/cron/crontab.txt';
        if (file_exists($crontabFile))
        {
            exec('crontab '.$crontabFile,$output);
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
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentException
     * @throws \Ms\Core\Exceptions\SystemException
     */
    public function parseCronExpression ($cronExpression)
    {
        $cronExpression = strtolower($cronExpression);
        $arTmp = explode(' ',$cronExpression);
        if (count($arTmp) < 5)
        {
            return false;
        }

        $this->arMinute = $this->cronInterpret($arTmp[0],0,59);
        $this->arHour = $this->cronInterpret($arTmp[1],0,23);
        $this->arDay = $this->cronInterpret($arTmp[2],1,31);
        $this->arMonth = $this->cronInterpret($arTmp[3],1,12,self::MONTHS);
        $this->arDayOfWeek = $this->cronInterpret($arTmp[4],0,6,self::DAYS_OF_WEEK);
        $this->arWeekEnd = (isset($arTmp[5]))
            ? $this->cronInterpret($arTmp[5],0,1)
            : $this->cronInterpret('*',0,1);
        unset($arTmp);

        //		echo $cronExpression.'<br>';
        $now = new Date();
        //		echo $now."<br>";
        $now->modify('+1 minute');
        $this->checkTime($now);
        $this->checkMonth($now);
        $this->checkDay($now);
        //		echo $now."<br><hr><br>";

        return $now;
    }

    /**
     * Возвращает список ошибок, возникших в ходе работы методов класса
     *
     * @return ErrorCollection
     */
    public function getErrors ()
    {
        return $this->errorCollection;
    }

    /**
     * Добавляет новую ошибку в коллекцию
     *
     * @param string $sMessage Сообщение об ошибке
     * @param string $sCode Код ошибки
     *
     * @return $this
     */
    private function addError($sMessage, $sCode=null)
    {
        $this->errorCollection->addErrorEasy($sMessage,$sCode);

        return $this;
    }

    /**
     * @param Date $now
     *
     * @return void
     */
    private function checkTime(Date $now)
    {
        $min = (int)$now->format('i');
        $hour = (int)$now->format('H');
        for ($i=0;$i<count($this->arHour);$i++)
        {
            if ($this->arHour[$i]>=$hour)
            {
                for($j=0;$j<count($this->arMinute);$j++)
                {
                    if ($this->arMinute[$j]>=$min)
                    {
                        //						echo 'arMin=',static::$arMinute[$j],'>=',$min.'<br>';
                        $now = $now->setTime($this->arHour[$i],$this->arMinute[$j]);
                        return;
                    }
                }
                $min = 0;
            }
        }
        $now = $now->setTime(0,0);
        $now->modify('+1 day');
        $this->checkTime($now);
    }

    /**
     * @param Date $now
     *
     * @return void
     */
    private function checkMonth (Date &$now)
    {
        $month = (int)$now->format('m');
        for ($i=0; $i<count($this->arMonth); $i++)
        {
            if ($this->arMonth[$i]>=$month)
            {
                //				echo 'arMonth=',$this->arMonth[$i],'>=',$month,'<br>';
                $now = $now->setMonth($this->arMonth[$i]);
                return;
            }
        }
        $now = $now->modify('+1 year');
        $now = $now->setMonth(1);
        $this->checkMonth($now);
    }

    /**
     * @param Date $now
     *
     * @return void
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentException
     * @throws \Ms\Core\Exceptions\SystemException
     */
    private function checkDay (Date &$now)
    {
        $day = (int)$now->format('d');
        for ($i=0; $i<count($this->arDay);$i++)
        {
            if ($this->arDay[$i]>=$day)
            {
                //				echo 'arDay=',static::$arDay[$i],'>=',$day,'<br>';
                $dow = $this->getDowDay($now,$this->arDay[$i]);
                if (in_array($dow,$this->arDayOfWeek))
                {
                    //					echo 'DoW=',$dow,' in[',implode(',',static::$arDayOfWeek),']<br>';
                    if (count($this->arWeekEnd) == 2)
                    {
                        $now = $now->setDay($this->arDay[$i]);
                        //						echo 'Не важно выходной или рабочий<br>';
                        return;
                    }
                    else
                    {
                        $strDate = $now->format('Y-m-');
                        if ($this->arDay[$i] < 10)
                        {
                            $strDate .= '0';
                        }
                        $strDate .= $this->arDay[$i];
                        $check = new Date($strDate);
                        $bWeekEnd = $check->isWeekEnd();
                        //Если нужен выходной, и этот день выходной
                        if ($this->arWeekEnd[0]==1 && $bWeekEnd)
                        {
                            //							echo '['.$check->format('w'),'] выходной<br>';
                            $now = $now->setDay($this->arDay[$i]);
                            return;
                        }
                        //Если нужен рабочий и этот день рабочий
                        elseif ($this->arWeekEnd[0]==0 && !$bWeekEnd)
                        {
                            //							echo '['.$check->format('w'),'] рабочий<br>';
                            $now = $now->setDay($this->arDay[$i]);
                            return;
                        }
                    }
                }
            }
        }
        $now = $now->setDay(1);
        $now = $now->modify('+1 month');
        $this->checkMonth($now);
        $this->checkDay($now);
    }

    /**
     * @param Date $now
     * @param      $day
     *
     * @return string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentException
     * @throws \Ms\Core\Exceptions\SystemException
     */
    private function getDowDay (Date $now,$day)
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

    /**
     * @param       $expression
     * @param       $minValue
     * @param       $maxValue
     * @param array $arNames
     *
     * @return array
     */
    private function cronInterpret ($expression, $minValue, $maxValue, $arNames=[])
    {
        $arReturn = [];

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

    /**
     * TODO: Реализовать метод setRunning
     *
     * @param $jobID
     */
    private function setRunning ($jobID)
    {
        // CronTable::update($jobID,array ('RUNNING'=>true));
    }

}