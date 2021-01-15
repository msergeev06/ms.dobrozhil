<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Ui;

use Ms\Dobrozhil\General\ConstantsAbstract;

/**
 * Класс Ms\Dobrozhil\Ui\Paths
 * Пути системы
 */
class Paths extends ConstantsAbstract
{
    const TOP_MENU_MAIN = '/';
    const TOP_MENU_MENU = '/menu.php';
    const TOP_MENU_CONSOLE = '#';
    const TOP_MENU_DEBUG = '#';
    const TOP_MENU_MESSAGES = 'javascript:void(0);';
    const TOP_MENU_WIKI = 'https://api.dobrozhil.ru';

    const PROFILE = '/profile/';
    const PROFILE_EDIT = '/profile/edit/';
    const PROFILE_SETTINGS = '/profile/settings/';

    const AUTH_PATH = '/auth.php';
    const AUTH_LOGIN = self::AUTH_PATH . '?act=login';
    const AUTH_LOGOUT = self::AUTH_PATH . '?act=logout';

    public function getName ($value)
    {
        return '';
    }
}