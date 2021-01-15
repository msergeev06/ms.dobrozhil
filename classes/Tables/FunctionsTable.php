<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Db\Tables\FieldsCollection;
use Ms\Core\Entity\Db\Tables\TableAbstract;

/**
 * Класс Ms\Dobrozhil\Tables\FunctionsTable
 * <Описание>
 */
class FunctionsTable extends TableAbstract
{
    public function getTableTitle (): string
    {
        return 'Функции';
    }

    public function getMap (): FieldsCollection
    {
        return (new FieldsCollection())
            ->addField(
                (new Fields\StringField('NAME'))
                    ->setPrimary()
                    ->setTitle ('Имя функции')
            )
            ->addField(
                (new Fields\StringField('TITLE'))
                    ->setTitle ('Название функции')
            )
        ;
    }
}