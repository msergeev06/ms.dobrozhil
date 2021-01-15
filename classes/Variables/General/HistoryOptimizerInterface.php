<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\General;

use Ms\Core\Entity\Db\Tables\TableAbstract;

/**
 * Интерфейс Ms\Dobrozhil\Variables\General\HistoryOptimizerInterface
 * Интерфейс оптимизатора исторических данных переменной
 */
interface HistoryOptimizerInterface
{
    public function __construct (VariableInterface $variable, TableAbstract $table);

    public function setObjectFromArray (array $arParams): HistoryOptimizerInterface;

    public function saveObjectToArray (): array;

    public function __toArray (): array;
}