<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Locations;

use Ms\Core\Entity\Db\Tables\ORMController;
use Ms\Core\Exceptions\SystemException;
use Ms\Dobrozhil\DataTypes\Coordinates;
use Ms\Dobrozhil\General\Multiton;
use Ms\Dobrozhil\Tables\LocationsTable;

/**
 * Класс Ms\Dobrozhil\Locations\LocationDbHelper
 * Адаптер базы данных для Локаций
 */
class LocationDbHelper extends Multiton
{
    private $orm = null;

    protected function __construct ()
    {
        $this->orm = ORMController::getInstance(new LocationsTable());
    }

    public function setUID (int $locationID, string $uid)
    {
        try
        {
            $res = $this->orm->update($locationID, ['UID' => $uid]);

            return $res->isSuccess();
        }
        catch (SystemException $e)
        {
            return false;
        }
    }

    public function getUID (int $locationID)
    {
        try
        {
            $arRes = $this->orm->getByPrimary(
                $locationID,
                ['UID']
            );
        }
        catch (SystemException $e)
        {
            return null;
        }

        return (!$arRes || !array_key_exists('UID',$arRes)) ? null : $arRes['UID'];
    }

    public function setTitle (int $locationID, string $title = null)
    {
        try
        {
            $res = $this->orm->update($locationID, ['TITLE' => $title]);

            return $res->isSuccess();
        }
        catch (SystemException $e)
        {
            return false;
        }
    }

    public function getTitle (int $locationID)
    {
        try
        {
            $arRes = $this->orm->getByPrimary($locationID, ['TITLE']);
        }
        catch (SystemException $e)
        {
            return null;
        }

        return (!$arRes || !array_key_exists('TITLE',$arRes)) ? null : $arRes['TITLE'];
    }

    public function setCoordinates (int $locationID, Coordinates $coordinates)
    {
        try
        {
            $res = $this->orm->updateByPrimary(
                $locationID,
                [
                    'LATITUDE'  => $coordinates->getLatitude(),
                    'LONGITUDE' => $coordinates->getLongitude()
                ]
            );

            return $res->isSuccess();
        }
        catch (SystemException $e)
        {
            return false;
        }
    }

    public function getCoordinates (int $locationID)
    {
        try
        {
            $arRes = $this->orm->getByPrimary(
                $locationID,
                ['LATITUDE', 'LONGITUDE']
            );
        }
        catch (SystemException $e)
        {
            return new Coordinates();
        }
        if (!$arRes || !array_key_exists('LATITUDE',$arRes) || !array_key_exists('LONGITUDE', $arRes))
        {
            return new Coordinates();
        }

        return new Coordinates($arRes['LATITUDE'], $arRes['LONGITUDE']);
    }
}