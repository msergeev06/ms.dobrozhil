<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Db\Tables\FieldsCollection;
use Ms\Core\Entity\Db\Tables\TableAbstract;
use Ms\Core\Entity\Helpers\TableHelper;

/**
 * Класс Ms\Dobrozhil\Tables\LocationsTable
 * Таблица Локаций ms_dobrozhil_locations
 */
class LocationsTable extends TableAbstract
{
    /**
     * Возвращает описание таблицы
     *
     * @return string Текст описания таблицы
     */
    public function getTableTitle (): string
    {
        return 'Локации';
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
                (new Fields\StringField('UID'))
                    ->setUnique()
                    ->setTitle('Уникальный идентификатор локации')
            )
            ->addField(
                (new Fields\StringField('TITLE'))
                    ->setTitle('Название локации')
            )
            ->addField(
                (new Fields\DecimalField('LATITUDE'))
                    ->setSize(10)
                    ->setScale(6)
                    ->setTitle('Широта')
            )
            ->addField(
                (new Fields\DecimalField('LONGITUDE'))
                    ->setSize(10)
                    ->setScale(6)
                    ->setTitle('Долгота')
            )
        ;
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
                'TITLE' => 'Дом',
                'LATITUDE' => 55.738403,
                'LONGITUDE' => 37.657327
            ]
        ];
    }
}