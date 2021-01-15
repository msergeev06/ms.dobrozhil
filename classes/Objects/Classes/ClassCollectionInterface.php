<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Objects\Classes;

/**
 * Интерфейс Ms\Dobrozhil\Objects\Classes\ClassCollectionInterface
 * Описывает коллекцию классов
 */
interface ClassCollectionInterface
{
    /**
     * Добавляет класс в коллекцию классов
     *
     * @param ClassInterface $class
     *
     * @return $this
     */
    public function addClass (ClassInterface $class);

    /**
     * Возвращает класс из коллекции по имени класса, либо null, если такого класса нет в коллекции
     *
     * @param string $className Имя класса
     *
     * @return ClassInterface|null
     */
    public function getClassByName (string $className);

    /**
     * Возвращает количество классов в коллекции
     *
     * @return int
     */
    public function getCount (): int;

    /**
     * Возвращает TRUE, если коллекция классов пустая
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Возвращает TRUE, если класс с заданным именем существует в коллекции
     *
     * @param string $className Имя класса в коллекции
     *
     * @return bool
     */
    public function isset(string $className): bool;
}