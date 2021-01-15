<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Exceptions;

use Ms\Core\Exceptions\SystemException;
use Ms\Dobrozhil\Lib\Errors;

/**
 * Класс Ms\Dobrozhil\Exceptions\VariableHistoryException
 * Исключение, вызываемое при ошибках с историческими значениями переменных
 */
class VariableHistoryException extends SystemException
{
    /**
     * Конструктор. Создает новый объект исключения.
     *
     * @param string     $message   Сообщение исключения
     * @param int        $code      Код исключения.
     *                              Необязательный, по-умолчанию равен 0
     * @param \Exception $previous  Исключение, предшествующее текущему
     *                              Необязательный, по-умолчанию null
     */
    public function __construct ($message = "", $code = 0, \Exception $previous = null)
    {
        if ((int)$code == 0)
        {
            $code = Errors::ERROR_ERROR;
        }
        parent::__construct($message, $code, '', 0, $previous);
    }
}