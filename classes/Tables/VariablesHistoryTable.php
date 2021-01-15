<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Db\Links\ForeignKey;
use Ms\Core\Entity\Db\Links\LinkedField;
use Ms\Core\Entity\Db\Tables\FieldsCollection;
use Ms\Core\Entity\Db\Tables\TableAbstract;
use Ms\Core\Entity\Helpers\TableHelper;

/**
 * Класс Ms\Dobrozhil\Tables\VariablesHistoryTable
 * Таблица исторических данных переменных ms_dobrozhil_variables_history
 */
class VariablesHistoryTable extends TableAbstract
{
    /**
     * @inheritDoc
     */
    public function getTableTitle (): string
    {
        return 'Исторические данные переменной '.$this->getAdditionalName();
    }

    /**
     * Возвращает коллекцию с описанием полей таблицы
     *
     * @return FieldsCollection
     */
    public function getMap (): FieldsCollection
    {
        return (new FieldsCollection())
            ->addField(
                TableHelper::getInstance()->primaryField()
            )
            ->addField(
                (new Fields\StringField('VARIABLE_NAME'))
                    ->setRequired()
                    ->setLink(
                        (new LinkedField(
                            new VariablesTable(),
                            'NAME',
                            (new ForeignKey())
                                ->setOnUpdateCascade()
                                ->setOnDeleteCascade()
                        ))
                    )
                    ->setTitle('Имя переменной, для которой сохраняется история')
            )
            ->addField(
                (new Fields\TextField('VALUE'))
                    ->setRequired()
                    ->setRequiredNull()
                    ->setTitle('Значение переменной')
            )
            ->addField(
                TableHelper::getInstance()->createdByField()
            )
            ->addField(
                TableHelper::getInstance()->createdDateField()
            )
        ;
    }
}