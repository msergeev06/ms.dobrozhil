<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\General;

use Ms\Core\Entity\Errors\FileLogger;

/**
 * Класс Ms\Dobrozhil\General\Multiton
 * Адаптер класса Multiton ядра
 */
abstract class Multiton extends \Ms\Core\Entity\System\Multiton
{
    /** @var FileLogger */
    protected $logger = null;

    protected function __construct ()
    {
        $this->logger = new FileLogger('ms.dobrozhil');
    }

    public function getLogger ()
    {
        return $this->logger;
    }
}