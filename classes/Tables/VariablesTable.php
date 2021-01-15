<?php

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Api\ApiAdapter;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Db\Result\DBResult;
use Ms\Core\Entity\Db\SqlHelper;
use Ms\Core\Entity\Db\Tables\FieldsCollection;
use Ms\Core\Entity\Db\Tables\TableAbstract;
use Ms\Core\Entity\Helpers\TableHelper;
use Ms\Core\Exceptions\Arguments\ArgumentTypeException;
use Ms\Core\Exceptions\Db\SqlQueryException;
use Ms\Core\Exceptions\SystemException;
use Ms\Dobrozhil\Types\General\Constants;
use Ms\Dobrozhil\Types\TypeBuilder;
use Ms\Dobrozhil\Variables\General\VariableDbHelper;

/**
 * Класс Ms\Dobrozhil\Tables\VariablesTable
 * Таблица "Переменные" ms_dobrozhil_variables
 */
class VariablesTable extends TableAbstract
{
    const SYSTEM_VARIABLES = [

    ];

    public function getDefaultRowsArray (): array
    {
        return [
            [
                'NAME'  => 'messagesLevel',
                'TITLE' => 'текущийУровеньВажности',
                'TYPE'  => Constants::TYPE_N_INT,
                'VALUE' => '100'
            ]
        ];
    }

    public function getMap (): FieldsCollection
    {
        $arTypesValues = TypeBuilder::getInstance()->getTypeCodes();

        return (new FieldsCollection())
            ->addField(
                (new Fields\StringField('NAME'))
                    ->setPrimary()
                    ->setTitle('Уникальное имя переменной')
            )
            ->addField(
                (new Fields\StringField('TITLE'))
                    ->setUnique()
                    ->setTitle('Уникальное название переменной')
            )
            ->addField(
                (new Fields\StringField('TYPE'))
                    ->setRequired()
                    ->setDefaultCreate(Constants::TYPE_STRING)
                    ->setDefaultInsert(Constants::TYPE_STRING)
                    ->setAllowedValues($arTypesValues)
                    ->setTitle('Тип переменной')
            )
            ->addField(
                (new Fields\TextField('VALUE'))
                    ->setTitle('Значение переменной')
            )

            ->addField(
                (new Fields\BooleanField('HIDDEN'))
                    ->setRequired()
                    ->setDefaultCreate(false)
                    ->setDefaultInsert(false)
                    ->setTitle ('Скрытая переменная')
            )

            ->addField(
                (new Fields\IntegerField('HISTORY_DAYS'))
                    ->setRequired()
                    ->setDefaultCreate(0)
                    ->setDefaultInsert(0)
                    ->setTitle ('Сколько дней хранить историю (0 - не хранить)')
            )
            ->addField(
                (new Fields\StringField('HISTORY_TABLE_NAME'))
                    ->setTitle ('Дополнительное имя для таблицы исторических значений')
            )
            ->addField(
                (new Fields\TextField('OPTIMIZE_HISTORY_PARAMS'))
                    ->setSerialized()
                    ->setTitle ('Параметры оптимизации исторических данных')
            )

            ->addField(
                (new Fields\BooleanField('SAVE_IDENTICAL_VALUES'))
                    ->setRequired()
                    ->setDefaultCreate(true)
                    ->setDefaultInsert(true)
                    ->setTitle ('Сохранять ли одинаковые значения переменной')
            )

            ->addField(
                (new Fields\BooleanField('AUTOMATED'))
                    ->setRequired()
                    ->setDefaultCreate(false)
                    ->setDefaultInsert(false)
                    ->setTitle ('Является ли автоматизированной переменной')
            )

            ->addField(
                (new Fields\BooleanField('READONLY'))
                    ->setRequired()
                    ->setDefaultCreate(false)
                    ->setDefaultInsert(false)
                    ->setTitle ('Запрещено изменение переменной в интерфейсе')
            )


            ->addField(
                TableHelper::getInstance()->createdByField()
                           ->setTitle('Кем создана переменная')
            )
            ->addField(
                TableHelper::getInstance()->createdDateField()
                           ->setTitle('Дата/время создания переменной')
            )
            ->addField(
                TableHelper::getInstance()->updatedByField()
                           ->setTitle('Кем изменена переменная')
            )
            ->addField(
                TableHelper::getInstance()->updatedDateField()
                           ->setTitle('Дата/время изменения переменной')
            )
            ;
    }

    /**
     * Возвращает дополнительный SQL запрос, используемый после создания таблицы
     *
     * @return null|string
     * @unittest
     */
    public function getAdditionalCreateSql ()
    {
        $helper = new SqlHelper($this->getTableName());

        return "ALTER TABLE " . $helper->wrapTableQuotes() . "\n"
               . 'ADD ' . parent::getSqlAddUnique('TITLE', true) . ";\n";
    }

    /**
     * Не даем удалять системные переменные
     *
     * @param mixed       $primary
     * @param string|null $strSqlWhere
     *
     * @return bool
     */
    public function onBeforeDelete ($primary, $strSqlWhere): bool
    {
        if (in_array($primary,self::SYSTEM_VARIABLES))
        {
            return false;
        }

        return parent::onBeforeDelete($primary, $strSqlWhere);
    }

    /**
     * Событие перед изменением полей переменных
     *
     * @param mixed $primary
     * @param array $arUpdate
     * @param null  $sSqlWhere
     *
     * @return bool
     */
    public function onBeforeUpdate ($primary, &$arUpdate, &$sSqlWhere = null): bool
    {
        //Не даем изменять системные переменные
        if (in_array($primary, self::SYSTEM_VARIABLES))
        {
            if (array_key_exists('NAME',$arUpdate))
            {
                unset($arUpdate['NAME']);
            }
            if (array_key_exists('TYPE',$arUpdate))
            {
                unset($arUpdate['TYPE']);
            }
        }

        //Значение HISTORY_TABLE_NAME можно изменить лишь в методе $this->prepareChangeHistoryDays
        if (array_key_exists('HISTORY_TABLE_NAME',$arUpdate))
        {
            unset($arUpdate['HISTORY_TABLE_NAME']);
        }

        //Если изменяется значение поля HISTORY_DAYS необходимо произвести действия с таблицей истории
        if (array_key_exists('HISTORY_DAYS',$arUpdate))
        {
            $stopUpdate = $this->prepareChangeHistoryDays($primary, $arUpdate);
            if ($stopUpdate === true)
            {
                return false;
            }
        }

        //Если arUpdate стал пустым, не нужно ничего обновлять
        if (empty($arUpdate))
        {
            return false;
        }

        return parent::onBeforeUpdate($primary, $arUpdate, $sSqlWhere);
    }

    public function getTableTitle (): string
    {
        return 'Переменные';
    }

    /**
     * Обработка события onBeforeUpdate
     * Если изменяется значение поля HISTORY_DAYS необходимо произвести действия с таблицей истории
     *
     * @param string $primary
     * @param array $arUpdate
     *
     * @return bool
     */
    protected function prepareChangeHistoryDays ($primary, &$arUpdate)
    {
        //Проверяем наличие основного необходимого поля
        if (!array_key_exists('HISTORY_DAYS',$arUpdate))
        {
            return false;
        }
        if ((int)$arUpdate['HISTORY_DAYS'] < 0)
        {
            $arUpdate['HISTORY_DAYS'] = 0;
        }

        //Получаем текущее значение HISTORY_DAYS и HISTORY_TABLE_NAME для переменной
        try
        {
            $arCurrent = VariableDbHelper::getInstance()->getList(
                [
                    'select' => ['NAME', 'HISTORY_DAYS', 'HISTORY_TABLE_NAME'],
                    'filter' => ['NAME' => $primary],
                    'limit'  => 1
                ]
            )
            ;
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog(VariableDbHelper::getInstance()->getLogger());

            return false;
        }
        if (
            $arCurrent === false
            || !array_key_exists('HISTORY_DAYS',$arCurrent[0])
            || !array_key_exists('HISTORY_TABLE_NAME',$arCurrent[0])
        ) {
            return false;
        }
        $arCurrent = $arCurrent[0];

        //Выясняем вид необходимых изменений
        if ((int)$arCurrent['HISTORY_DAYS'] == 0 && (int)$arUpdate['HISTORY_DAYS'] > 0)
        {
            //Если ранее не нужно было хранить историю, а теперь нужно - действие создания таблицы истории

            return $this->createHistoryTable($primary, $arUpdate);
        }
        elseif ((int)$arCurrent['HISTORY_DAYS'] > 0 && (int)$arUpdate['HISTORY_DAYS'] <= 0)
        {
            //Если ранее сохранялась информация в истории, а теперь не нужно - действие удаления таблицы истории

            return $this->dropHistoryTable ($arUpdate, $arCurrent);
        }
        elseif ((int)$arCurrent['HISTORY_DAYS'] == (int)$arUpdate['HISTORY_DAYS'])
        {
            //Если старое и новое значение дней равны, не нужно его менять
            unset($arUpdate['HISTORY_DAYS']);

            return false;
        }
        else
        {
            //Если просто меняется количество дней сохранения истории, то просто меням их без дополнительной обработки

            return false;
        }
    }

    /**
     * Если ранее не нужно было хранить историю, а теперь нужно - действие создания таблицы истории
     *
     * @param $primary
     * @param $arUpdate
     *
     * @return bool
     */
    protected function createHistoryTable ($primary, &$arUpdate)
    {
        $historyOrm = ApiAdapter::getInstance()->getDbApi()->getTableOrmByClass(VariablesHistoryTable::class);
        $arTables = $historyOrm->getListTables($historyOrm->getTableName());

        if (!empty($arTables))
        {
            $additionalName = $this->generateAdditionalName($primary, $arTables);
            $variableHistoryOrm = ApiAdapter::getInstance()
                                            ->getDbApi()
                                            ->getTableOrm(
                                                new VariablesHistoryTable($additionalName)
                                            )
            ;
            try
            {
                $res = $variableHistoryOrm->createTable();
            }
            catch (SqlQueryException $e)
            {
                $res = new DBResult();
            }
            if (!$res->isSuccess())
            {
                return true;
            }
            $arUpdate['HISTORY_TABLE_NAME'] = $additionalName;

            return false;
        }

        return true;
    }

    /**
     * Генерирует дополнительное имя таблицы истории для имени переменной
     *
     * @param string $variableName
     * @param array  $arTables
     *
     * @return mixed|string
     */
    protected function generateAdditionalName (string $variableName, array $arTables = [])
    {
        $historyTableName = (new VariablesHistoryTable())->getTableName();
        $additionalName = strtolower($variableName);
        $additionalName = str_replace('.','_',$additionalName);
        $additionalName = str_replace('::','_',$additionalName);
        $bIssetTable = false;
        $issetTableCount = 0;
        foreach ($arTables as $tableName)
        {
            //Если есть таблица с похожим названием
            if (strpos($tableName, $historyTableName . '_' . $additionalName) !== false)
            {
                $bIssetTable = true;
                $issetTableCount++;
            }
        }
        if ($bIssetTable)
        {
            //Если была найдена таблица с похожим названием
            while (in_array($historyTableName . '_' . $additionalName . $issetTableCount, $arTables))
            {
                $issetTableCount++;
            }
            $additionalName .= $issetTableCount;
        }

        return $additionalName;
    }

    /**
     * Если ранее сохранялась информация в истории, а теперь не нужно - действие удаления таблицы истории
     *
     * @param $arUpdate
     * @param $arCurrent
     *
     * @return bool
     */
    protected function dropHistoryTable (&$arUpdate, $arCurrent)
    {
        msDebug($arUpdate);
        msDebug($arCurrent);
        if (is_null($arCurrent['HISTORY_TABLE_NAME']))
        {
            $arUpdate['HISTORY_TABLE_NAME'] = null;
            $arUpdate['HISTORY_DAYS'] = 0;

            return false;
        }
        $orm = ApiAdapter::getInstance()
                         ->getDbApi()
                         ->getTableOrm(
                             new VariablesHistoryTable($arCurrent['HISTORY_TABLE_NAME'])
                         )
        ;
        try
        {
            if (!$orm->issetTable())
            {
                $arUpdate['HISTORY_TABLE_NAME'] = null;
                $arUpdate['HISTORY_DAYS'] = 0;

                return false;
            }
        }
        catch (SqlQueryException $e)
        {
            $e->addMessageToLog(VariableDbHelper::getInstance()->getLogger());

            return true;
        }
        try
        {
            $dropResult = $orm->dropTable(true);
            msDebug($dropResult);
        }
        catch (SqlQueryException $e)
        {
            $e->addMessageToLog(VariableDbHelper::getInstance()->getLogger());
            msDebug($e);

            return true;
        }
        if (!$dropResult->isSuccess())
        {
            return true;
        }

        try
        {
            if (!$orm->issetTable())
            {
                $arUpdate['HISTORY_TABLE_NAME'] = null;
                $arUpdate['HISTORY_DAYS'] = 0;

                return false;
            }
        }
        catch (SqlQueryException $e)
        {
            $e->addMessageToLog(VariableDbHelper::getInstance()->getLogger());
        }

        return true;
    }
}