<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Db\Links\ForeignKey;
use Ms\Core\Entity\Db\Links\LinkedField;
use Ms\Core\Entity\Db\Query\QueryBase;
use Ms\Core\Entity\Db\SqlHelper;
use Ms\Core\Entity\Db\Tables\FieldsCollection;
use Ms\Core\Entity\Db\Tables\ORMController;
use Ms\Core\Entity\Db\Tables\TableAbstract;
use Ms\Core\Entity\Helpers\TableHelper;
use Ms\Core\Entity\System\Application;
use Ms\Core\Exceptions\Arguments\ArgumentTypeException;
use Ms\Core\Exceptions\Db\SqlQueryException;
use Ms\Core\Exceptions\SystemException;

/**
 * Класс Ms\Dobrozhil\Tables\FloorsTable
 * Таблица этажей ms_dobrozhil_floors
 */
class FloorsTable extends TableAbstract
{
    const DEFAULT_FLOOR_TITLE = 'Новый этаж';
    const DEFAULT_FLOOR_LEVEL = 1;

    /**
     * Возвращает описание таблицы
     *
     * @return string Текст описания таблицы
     */
    public function getTableTitle (): string
    {
        return 'Этажи';
    }

    /**
     * Возвращает коллекцию с описанием полей таблицы
     *
     * @return FieldsCollection
     */
    public function getMap (): FieldsCollection
    {
        $collection = (new FieldsCollection())
            ->addField(
                TableHelper::getInstance()->primaryField()
            )
            ->addField(
                (new Fields\IntegerField('LEVEL'))
                    ->setRequired()
                    ->setDefaultCreate(self::DEFAULT_FLOOR_LEVEL)
                    ->setDefaultInsert(self::DEFAULT_FLOOR_LEVEL)
                    ->setTitle ('Уровень этажа')
            )
        ;
        try
        {
            $collection
                ->addField(
                    (new Fields\StringField('TITLE'))
                        ->setRequired()
                        ->setDefaultCreate(self::DEFAULT_FLOOR_TITLE)
                        ->setDefaultInsert(self::DEFAULT_FLOOR_TITLE)
                        ->setTitle('Название этажа')
                );
        }
        catch (ArgumentTypeException $e)
        {
            $collection
                ->addField(
                    (new Fields\StringField('TITLE'))
                        ->setRequired()
                        ->setTitle('Название этажа')
                );
        }

        return $collection;
    }

    /**
     * Возвращает массив дефолтных значений таблицы, которые добавляются в таблицу при установке ядра или модуля
     *
     * @return array Массив дефолтных значений таблицы
     */
    public function getDefaultRowsArray (): array
    {
        return [
            [
                'ID' => 1,
                'LEVEL' => 0,
                'TITLE' => 'Основной этаж'
            ]
        ];
    }

    /**
     * Обработчик события перед добавлением строки в таблицу
     *
     * @param array $arInsert
     *
     * @return bool
     */
    public function onBeforeInsert (array &$arInsert): bool
    {
        if (!array_key_exists('LEVEL',$arInsert))
        {
            $arInsert['LEVEL'] = self::DEFAULT_FLOOR_LEVEL;
        }

        $table = new self();
        $orm = ORMController::getInstance($table);
        try
        {
            $checkRes = $orm->getOne(
                [
                    'select' => ['ID', 'LEVEL'],
                    'filter' => ['LEVEL' => $arInsert['LEVEL']]
                ]
            );
        }
        catch (SystemException $e)
        {
            return true;
        }
        if (!$checkRes)
        {
            return true;
        }

        $sql = $this->getSqlChangeLevel($arInsert['LEVEL']);
        $query = new QueryBase($sql);
        try
        {
            $res = $query->exec();
        }
        catch (SqlQueryException $e)
        {
            return false;
        }

        return $res->isSuccess();
    }

    /**
     * Обработчик события перед обновление записи таблицы
     *
     * @param mixed $primary   Значение PRIMARY поля изменяемой строки
     * @param array $arUpdate  Изменяемые поля и их новые значения
     * @param null  $sSqlWhere Дополнительный запрос SQL WHERE
     *
     * @return bool
     */
    public function onBeforeUpdate ($primary, &$arUpdate, &$sSqlWhere = null): bool
    {
        if (!is_null($sSqlWhere))
        {
            return true;
        }
        $table = new self();
        $orm = ORMController::getInstance($table);
        if (array_key_exists('LEVEL',$arUpdate))
        {
            try
            {
                $checkRes = $orm->getOne(
                    [
                        'select' => ['ID', 'LEVEL'],
                        'filter' => ['ID' => $primary]
                    ]
                );
            }
            catch (SystemException $e)
            {
                return true;
            }
            if (!$checkRes)
            {
                return true;
            }
            if (array_key_exists('LEVEL',$checkRes) && $checkRes['LEVEL'] == $arUpdate['LEVEL'])
            {
                $sql = $this->getSqlChangeLevel($checkRes['LEVEL']);
                $query = new QueryBase($sql);
                try
                {
                    $res = $query->exec();
                }
                catch (SqlQueryException $e)
                {
                    return false;
                }

                return $res->isSuccess();
            }
            else
            {
                return true;
            }
        }

        return true;
    }

    /**
     * @param int $level
     *
     * @return string
     */
    private function getSqlChangeLevel (int $level)
    {
        $helper = new SqlHelper(new self());

        $sql = <<<EOL
UPDATE
  #TABLE_NAME# floors
SET
  floors.`LEVEL` = floors.`LEVEL` + 1
WHERE
  floors.`LEVEL` >= #LEVEL#
EOL;
        $sql = str_replace('#TABLE_NAME#', $helper->wrapTableQuotes(), $sql);
        $sql = str_replace('#LEVEL#', $level, $sql);

        return $sql;
    }
}