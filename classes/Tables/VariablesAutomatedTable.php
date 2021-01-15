<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Links\ForeignKey;
use Ms\Core\Entity\Db\Links\LinkedField;
use Ms\Core\Entity\Db\Tables\FieldsCollection;
use Ms\Core\Entity\Db\Tables\TableAbstract;
use Ms\Core\Entity\Db\Fields;

/**
 * Класс Ms\Dobrozhil\Tables\VariablesAutomatedTable
 * <Описание>
 */
class VariablesAutomatedTable extends TableAbstract
{
    const TYPE_SCRIPT      = 'script';
    const TYPE_SCRIPT_TEXT = 'Выполнение функции';
    const TYPE_WEB         = 'parse';
    const TYPE_WEB_TEXT    = 'Парсер';

    public function getMap (): FieldsCollection
    {
        return (new FieldsCollection())
            ->addField(
                (new Fields\StringField('VARIABLE_NAME'))
                    ->setPrimary()
                    ->setLink(
                        (new LinkedField(
                            new VariablesTable(),
                            'NAME',
                            (new ForeignKey())
                                ->setOnUpdateCascade()
                                ->setOnDeleteCascade()
                        ))
                    )
                    ->setTitle('Имя переменной')
            )
            ->addField(
                (new Fields\StringField('TYPE'))
                    ->setRequired()
                    ->setAllowedValues([self::TYPE_SCRIPT, self::TYPE_WEB])
                    ->setDefaultCreate(self::TYPE_SCRIPT)
                    ->setDefaultInsert(self::TYPE_SCRIPT)
                    ->setTitle('Тип автоматизации')
            )
            ->addField(
                (new Fields\StringField('FUNCTION_NAME'))
                    ->setLink (
                        (new LinkedField(
                            new FunctionsTable (),
                            'NAME',
                            (new ForeignKey())
                                ->setOnUpdateCascade()
                                ->setOnDeleteSetNull()
                        ))
                    )
            )

            ->addField(
                (new Fields\StringField('URL'))
                    ->setTitle ('URL  получения данных')
            )
            ->addField(
                (new Fields\StringField('REGEXP'))
                    ->setTitle ('Регулярное выражение получения данных')
            )


            ->addField(
                (new Fields\IntegerField('PERIOD'))
                    ->setRequired()
                    ->setDefaultCreate(3600)
                    ->setDefaultInsert(3600)
                    ->setTitle ('Период обновления (сек.)')
            )
            ->addField(
                (new Fields\DateTimeField('LAST_RUN'))
                    ->setTitle ('Дата/время последнего запуска')
            )
            ->addField(
                (new Fields\DateTimeField('NEXT_RUN'))
                    ->setTitle('Дата/время следующего запуска')
            )
            ->addField(
                (new Fields\BooleanField('RUNNING'))
                    ->setRequired()
                    ->setDefaultCreate(false)
                    ->setDefaultInsert(false)
                    ->setTitle('Сейчас запущен')
            )
            ;
    }

    public function getTableTitle (): string
    {
        return 'Автоматизация переменных';
    }
}