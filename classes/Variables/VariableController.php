<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables;

use Ms\Core\Exceptions\SystemException;
use Ms\Dobrozhil\General\Multiton;
use Ms\Dobrozhil\Types\General\Constants;
use Ms\Dobrozhil\Types\TypeBuilder;
use Ms\Dobrozhil\Variables\General\VariableDbHelper;
use Ms\Dobrozhil\Variables\General\VariableInterface;

/**
 * Класс Ms\Dobrozhil\Variables\VariableController
 * Обработчик виртуальных переменных
 */
class VariableController extends Multiton
{
    /**
     * Создает объект переменной, при необходимости устанавливая дополнительные параметры
     *
     * @param string      $name  Имя переменной
     * @param string|null $title Название переменной
     * @param string      $type  Тип значения переменной
     *
     * @return Variable
     */
    public function build (string $name, string $title = null, string $type = Constants::TYPE_STRING)
    {
        $object = (new Variable())
            ->setName($name)
        ;
        if (!is_null($title))
        {
            $object->setTitle($title);
        }
        $type = strtoupper($type);
        if (TypeBuilder::getInstance()->isRightTypeCode($type))
        {
            $object->setType($type);
        }

        return $object;
    }

    /**
     * Получает данные об указанной переменной из БД.
     * Если указан флаг облегченной загрузки, загружает только следующие поля: NAME, TITLE, TYPE, VALUE
     *
     * @param string $nameOrTitle  Имя или название переменной
     * @param bool   $isSimpleLoad Флаг облегченной загрузки
     *
     * @return VariableInterface|null
     * @throws SystemException
     */
    public function load (string $nameOrTitle, bool $isSimpleLoad = false)
    {
        try
        {
            $arFields = VariableDbHelper::getInstance()->get($nameOrTitle);
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return null;
        }

        $object = (new Variable())
            ->setName($arFields['NAME'])
            ->setTitle($arFields['TITLE'])
            ->setType($arFields['TYPE'])
        ;
        if (!is_null($arFields['VALUE']))
        {
            $object
                ->setRawValue($arFields['VALUE'])
            ;
        }
        if (!$isSimpleLoad)
        {
            $object
                ->setHidden($arFields['HIDDEN'])
                ->setAutomated($arFields['AUTOMATED'])
                ->setReadOnly($arFields['READONLY'])
                ->setCreatedBy($arFields['CREATED_BY'])
                ->setCreatedDate($arFields['CREATED_DATE'])
                ->setUpdatedBy($arFields['UPDATED_BY'])
                ->setUpdatedDate($arFields['UPDATED_DATE'])
            ;
            if ((int)$arFields['HISTORY_DAYS'] > 0)
            {
                $object
                    ->setHistoryDays($arFields['HISTORY_DAYS'])
                    ->setHistoryTableName($arFields['HISTORY_TABLE_NAME'])
                    ->setOptimizationHistoryParams($arFields['OPTIMIZE_HISTORY_PARAMS'])
                    ->setSaveIdenticalValues($arFields['SAVE_IDENTICAL_VALUES'])
                ;
            }
        }

        return $object;
    }

    /**
     * Получает основные данные об указанной переменной из БД. Загружаются следующие поля: NAME, TITLE, TYPE, VALUE
     *
     * @param string $nameOrTitle Имя или название переменной
     *
     * @return VariableInterface|null
     * @throws SystemException
     */
    public function loadSimple (string $nameOrTitle)
    {
        return $this->load($nameOrTitle, true);
    }

    /**
     * Создает новую запись о переменной в БД, записывая только имя переменной и ее тип
     *
     * @param string $name Имя переменной
     * @param string $type Тип значения переменной
     *
     * @return bool
     */
    public function create (string $name, string $type = Constants::TYPE_STRING)
    {
        $arFields = [
            'NAME' => $name
        ];
        $type = strtoupper($type);
        if (TypeBuilder::getInstance()->isRightTypeCode($type))
        {
            $arFields['TYPE'] = $type;
        }
        else
        {
            $arFields['TYPE'] = Constants::TYPE_STRING;
        }

        try
        {
            $res = VariableDbHelper::getInstance()->create($arFields);
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return false;
        }

        return $res->isSuccess();
    }

    /**
     * Создает запись о новой переменной в таблице переменных БД. Сохраняет все параметры переменной, а также ее значение
     *
     * @param VariableInterface $variable Объект переменной
     *
     * @return bool
     */
    public function createNewVariable (VariableInterface $variable)
    {
        if (is_null($variable->getName()))
        {
            return false;
        }
        $bOk = $this->create($variable->getName(), $variable->getTypeCode());
        if (!$bOk)
        {
            return false;
        }
        $bOk = $this->save($variable);
        if (!$bOk)
        {
            return false;
        }
        $bOk = $this->setValue($variable);

        return $bOk;
    }

    /**
     * Сохраняет изменения параметров произведенные в объекте переменной.
     * Не сохраняет значение переменной, а также информацию о создавшем/изменившем переменную, так и о дате изменений
     *
     * @param VariableInterface $variable Объект переменной
     *
     * @return bool
     */
    public function save (VariableInterface $variable)
    {
        $arFields = $variable->toArray();
        $name = $arFields['NAME'];
        unset($arFields['NAME']);
        if (array_key_exists('VALUE',$arFields))
        {
            unset($arFields['VALUE']);
        }
        if (array_key_exists('CREATED_BY',$arFields))
        {
            unset($arFields['CREATED_BY']);
        }
        if (array_key_exists('CREATED_DATE',$arFields))
        {
            unset($arFields['CREATED_DATE']);
        }
        if (array_key_exists('UPDATED_BY',$arFields))
        {
            unset($arFields['UPDATED_BY']);
        }
        if (array_key_exists('UPDATED_DATE',$arFields))
        {
            unset($arFields['UPDATED_DATE']);
        }

        try
        {
            $res = VariableDbHelper::getInstance()->update($name, $arFields);
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return false;
        }

        return $res->isSuccess();
    }

    /**
     * Устанавливает новое значение переменной. Если необходимо, значение также записывается в историю
     *
     * @param VariableInterface $variable Объект переменной
     *
     * @return bool
     */
    public function setValue (VariableInterface $variable)
    {
        try
        {
            $arFields = VariableDbHelper::getInstance()->get($variable->getName());
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return false;
        }
        if ($arFields === false)
        {
            return false;
        }

        $bOk = true;
        if ((int)$arFields['HISTORY_DAYS'] > 0)
        {
            $bOk = $this->setHistoryValue($variable);
        }
        $rawValue = $variable->getRawValue();
        if ($rawValue != $arFields['VALUE'] || $arFields['SAVE_IDENTICAL_VALUES'] === true)
        {
            $bOk = $this->setNewValue($variable);
        }

        return $bOk;
    }

    /**
     * Удаляет запись об указанной переменной из БД
     *
     * @param string $name Имя переменной
     *
     * @return bool
     */
    public function delete (string $name)
    {
        try
        {
            $res = VariableDbHelper::getInstance()->delete($name);
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return false;
        }

        return $res->isSuccess();
    }

    /**
     * Создает коллекцию переменных
     *
     * @return VariableCollection
     */
    public function buildCollection ()
    {
        return new VariableCollection();
    }

    /**
     * Возвращает коллекцию переменных, полученных методом getList таблицы БД
     *
     * @param array $arListParams Массив параметров getList
     *
     * @return VariableCollection
     */
    public function getListCollection (array $arListParams)
    {
        $collection = $this->buildCollection();
        try
        {
            $arRes = VariableDbHelper::getInstance()->getList($arListParams);
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return $collection;
        }
        if ($arRes === false || empty($arRes))
        {
            return $collection;
        }
        foreach ($arRes as $arVariable)
        {
            $collection->addVariable(Variable::createFromArray($arVariable));
        }

        return $collection;
    }

    /**
     * Добавляет новое значение переменной в историю, если это необходимо
     *
     * @param VariableInterface $variable Объект переменной
     *
     * @return bool
     */
    protected function setHistoryValue (VariableInterface $variable)
    {
        //TODO: Реализовать метод сохранения значения переменной в историю

        return true;
    }

    /**
     * Устанавливает новое значение переменной
     *
     * @param VariableInterface $variable Объект переменной
     *
     * @return bool
     */
    protected function setNewValue (VariableInterface $variable)
    {
        $arFields = [
            'VALUE' => $variable->getRawValue()
        ];

        try
        {
            $res = VariableDbHelper::getInstance()->update($variable->getName(), $arFields);
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return false;
        }

        return $res->isSuccess();
    }
}