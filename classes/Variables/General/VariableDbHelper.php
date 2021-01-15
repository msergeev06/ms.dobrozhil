<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\General;

use Ms\Core\Entity\Db\Tables\ORMController;
use Ms\Dobrozhil\General\Multiton;
use Ms\Dobrozhil\Tables\VariablesTable;

/**
 * Класс Ms\Dobrozhil\Variables\General\VariableDbHelper
 * Помощник для работы с таблицей виртуальных переменных
 */
class VariableDbHelper extends Multiton
{
    protected $orm = null;

    protected function __construct ()
    {
        parent::__construct();
        $this->orm = ORMController::getInstance(new VariablesTable());
    }

    /**
     * Возвращает поля переменной по ее имени или названию
     *
     * @param string $nameOrTitle Имя или название переменной
     *
     * @return array|bool|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentTypeException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     */
    public function get (string $nameOrTitle)
    {
        return $this->orm->getOne(
            [
                'filter' => [
                    'LOGIC' => 'OR',
                    'NAME' => $nameOrTitle,
                    'TITLE' => $nameOrTitle
                ]
            ]
        );
    }

    /**
     * Создает запись о новой переменной в таблице БД
     *
     * @param array $arFields Поля переменной
     *
     * @return \Ms\Core\Entity\Db\Result\DBResult|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentNullException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     * @throws \Ms\Core\Exceptions\Db\ValidateException
     */
    public function create (array $arFields)
    {
        return $this->orm->insert($arFields);
    }

    /**
     * Обновляет запись о переменной в БД
     *
     * @param string $variableName Имя переменной
     * @param array  $arFields     Массив изменяемых значений полей таблицы
     *
     * @return \Ms\Core\Entity\Db\Result\DBResult|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentException
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentNullException
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentOutOfRangeException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     * @throws \Ms\Core\Exceptions\Db\ValidateException
     */
    public function update (string $variableName, array $arFields)
    {
        return $this->orm->updateByPrimary($variableName,$arFields);
    }

    /**
     * Удаляет запись о переменной
     *
     * @param string $variableName Имя переменной
     *
     * @return \Ms\Core\Entity\Db\Result\DBResult
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentNullException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     */
    public function delete (string $variableName)
    {
        return $this->orm->delete($variableName);
    }

    /**
     * Возвращает список переменных по запросу
     *
     * @param array $arParams Массив параметров запроса
     *
     * @return array|bool|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentTypeException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     */
    public function getList (array $arParams = [])
    {
        return $this->orm->getList($arParams);
    }
}