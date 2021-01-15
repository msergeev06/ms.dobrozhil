<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\History;

use Ms\Core\Entity\Db\Tables\TableAbstract;
use Ms\Dobrozhil\Variables\General\HistoryOptimizerInterface;
use Ms\Dobrozhil\Variables\General\VariableInterface;

/**
 * Класс Ms\Dobrozhil\Variables\History\HistoryOptimizer
 * Оптимизатор исторических значений переменной
 */
class HistoryOptimizer implements HistoryOptimizerInterface
{
    protected $variable = null;
    protected $table = null;
    protected $arParams = [];

    public function __construct (VariableInterface $variable, TableAbstract $table)
    {
        $this->variable = $variable;
        $this->table = $table;
    }

    public function setObjectFromArray (array $arParams): HistoryOptimizerInterface
    {
        $this->arParams = $arParams;

        return $this;
    }

    public function saveObjectToArray (): array
    {
        return [];
    }

    public function __toArray (): array
    {
        return $this->saveObjectToArray();
    }
}