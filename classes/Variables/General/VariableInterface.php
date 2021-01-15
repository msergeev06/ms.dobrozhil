<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\General;

use Ms\Core\Entity\Type\Date;
use Ms\Dobrozhil\Types\General\TypeInterface;

/**
 * Интерфейс Ms\Dobrozhil\Variables\General\VariableInterface
 * Интерфейс виртуальных переменных умного дома
 */
interface VariableInterface
{
    /**
     * Возвращает имя переменной, если оно задано, либо NULL
     *
     * @return string|null
     */
    public function getName ();

    /**
     * Устанавливает имя переменной
     *
     * @param string|null $name Название переменной
     *
     * @return $this
     */
    public function setName (string $name = null);

    /**
     * Возвращает название переменной, если оно задано, либо NULL
     *
     * @return string|null
     */
    public function getTitle ();

    /**
     * Устанавливает название переменной
     *
     * @param string|null $title Название переменной
     *
     * @return $this
     */
    public function setTitle (string $title = null);

    /**
     * Возвращает код типа переменной, по умолчанию возвращает Constants::TYPE_STRING (S - строка)
     *
     * @return string
     */
    public function getTypeCode (): string;

    /**
     * Возвращает обработчик установленного типа значения переменной
     *
     * @return TypeInterface
     */
    public function getTypeController (): TypeInterface;

    /**
     * Устанавливает тип значения переменной. При этом устанавливается обработчик для заданного типа
     *
     * @param string $typeCode Код типа переменной
     *
     * @return $this
     */
    public function setType (string $typeCode);

    /**
     * Возвращает необработанное значение при помощи обработчика установленного типа переменной
     *
     * @return string
     */
    public function getRawValue (): string;

    /**
     * Устанавливает необработанное значение при помощи обработчика установленного типа переменной
     *
     * @param string $rawValue Необработанное значение переменной
     *
     * @return VariableInterface
     */
    public function setRawValue (string $rawValue): VariableInterface;

    /**
     * Возвращает значение переменной, уже обработанное при помощи обработчика для установленного типа переменной
     *
     * @return mixed
     */
    public function getValue ();

    /**
     * Устанавливает новое значение переменной. Переданное значение обрабатывается обработчиком типа значения переменной
     * Установка значения не записывает изменения в БД автоматически. Это нужно произвести вручную
     *
     * @param mixed $value Новое значение переменной
     *
     * @return $this
     */
    public function setValue ($value = null);

    /**
     * Возвращает TRUE, если переменная является скрытой, иначе возвращает FALSE
     *
     * @return bool
     */
    public function isHidden (): bool;

    /**
     * Устанавливает флаг скрытой переменной
     *
     * @param bool $isHidden Флаг скрытой переменной
     *
     * @return $this
     */
    public function setHidden (bool $isHidden = false);

    /**
     * Возвращает количество дней, сколько необходимо хранить историю изменения значений переменной.
     * По умолчанию 0 дней (не хранить историю)
     *
     * @return int
     */
    public function getHistoryDays (): int;

    /**
     * Устанавливает количество дней, сколько нужно хранить историю изменений значений. Для отключения сохранения истории
     * и удаления всех сохраненных значений, нужно установить значение 0
     *
     * @param int $numberOfDays Количество дней хранения истории (по умолчанию 0 - не хранить историю)
     *
     * @return $this
     */
    public function setHistoryDays (int $numberOfDays = 0);

    /**
     * Возвращает постфикс таблицы в которой хранятся исторические значения данной переменной, либо NULL
     *
     * @return string|null
     */
    public function getHistoryTableName ();

    /**
     * Устанавливает постфикс таблицы в которой будут хранится исторические значения данной переменной. Если не нужно
     * хранить историю, следует установить значение NULL (по умолчанию)
     *
     * @param string|null $historyTableName Постфикс таблицы исторических значений
     *
     * @return $this
     */
    public function setHistoryTableName (string $historyTableName = null);

    /**
     * Возвращает параметры оптимизации таблицы исторических значений данной переменной
     *
     * @return mixed
     */
    public function getOptimizationHistoryParams (); //TODO: Описать

    /**
     * Устанавливает параметры оптимизации таблицы исторических значений данной переменной
     *
     * @param array $arParams Массив параметров оптимизации
     *
     * @return $this
     */
    public function setOptimizationHistoryParams (array $arParams = null); //TODO: Описать

    /**
     * Возвращает TRUE, если необходимо сохранять значение даже в том случае, если оно равно предыдущему значению
     * переменной, иначе возвращает FALSE
     *
     * @return bool
     */
    public function isSaveIdenticalValues (): bool;

    /**
     * Устанавливает флаг необходисти сохранения одинаковых значений в истории
     *
     * @param bool $isSaveIdenticalValues Флаг необходимости сохранения одинаковых значений в истории
     *
     * @return $this
     */
    public function setSaveIdenticalValues (bool $isSaveIdenticalValues = true);

    /**
     * Возвращает TRUE, если переменная является автоматизированной, иначе возвращает FALSE (по умолчанию)
     *
     * @return bool
     */
    public function isAutomated (): bool;

    /**
     * Устанавливает флаг автоматизированной переменной
     *
     * @param bool $isAutomated Флаг автоматизированной переменной (по умолчанию false - не автоматизированная)
     *
     * @return $this
     */
    public function setAutomated (bool $isAutomated = false);

    /**
     * Возвращает флаг, запрещающий редактирование переменной в интерфейсе
     *
     * @return bool
     */
    public function isReadOnly (): bool;

    /**
     * Устанавливает флаг, запрещающий редактирование переменной в интерфейсе
     *
     * @param bool $isReadOnly Флаг: TRUE - запрещено редактирование в интерфейсе, FALSE - разрешено
     *
     * @return mixed
     */
    public function setReadOnly (bool $isReadOnly = false);

    /**
     * Возвращает ID пользователя, создавшего переменную, либо NULL
     *
     * @return null|int
     */
    public function getCreatedBy ();

    /**
     * Устанавливает ID пользователя, который создал переменную
     *
     * @param int|null $createdBy ID пользователя, создавшего переменную
     *
     * @return $this
     */
    public function setCreatedBy (int $createdBy = null);

    /**
     * Возвращает дату создания переменной, либо NULL
     *
     * @return null|Date
     */
    public function getCreatedDate ();

    /**
     * Устанавливает дату создания переменной
     *
     * @param Date|null $createdDate Дата создания переменной, либо NULL (по умолчанию)
     *
     * @return $this
     */
    public function setCreatedDate (Date $createdDate = null);

    /**
     * Возвращает ID пользователя, последним изменившего параметры или значение переменной, либо возвращает NULL
     *
     * @return null|int
     */
    public function getUpdatedBy ();

    /**
     * Устанавливает ID пользователя, изменившего параметры или значение переменной
     *
     * @param int|null $updatedBy ID пользователя, либо NULL (по умолчанию)
     *
     * @return $this
     */
    public function setUpdatedBy (int $updatedBy = null);

    /**
     * Возвращает дату изменения пераметров или значения переменной, либо NULL
     *
     * @return null|Date
     */
    public function getUpdatedDate ();

    /**
     * Устанавливает дату изменения параметров или значения переменной
     *
     * @param Date|null $updatedDate Дата изменения, либо NULL (по умолчанию)
     *
     * @return $this
     */
    public function setUpdatedDate (Date $updatedDate = null);

    /**
     * Преобразует параметры объекта в массив с полями таблицы переменных БД
     *
     * @return array
     */
    public function toArray (): array;

    /**
     * Создает объект переменной из массива полей таблицы переменных БД
     *
     * @param array $arDbFields Массив полей таблицы переменных БД
     *
     * @return VariableInterface
     */
    public static function createFromArray (array $arDbFields): VariableInterface;
}