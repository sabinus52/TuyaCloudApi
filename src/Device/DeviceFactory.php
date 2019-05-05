<?php
/**
 * Fabrique des objets des devices
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Device;


class DeviceFactory
{

    /**
     * Types des différents devices
     */
    const SWITCH = 'switch';
    const COVER  = 'cover';

    
    /**
     * Créer l'objet de l'équipement à partir des données reçus par la découverte des devices
     * 
     * @param Array $datas
     * @return Device
     */
    static public function createDeviceFromDatas(array $datas)
    {
        switch ($datas['dev_type']) {
            case self::SWITCH :
                $device = new SwitchDevice($datas['id'], $datas['name'], $datas['icon']);
                $device->setData($datas['data']);
                break;
            case self::COVER :
                $device = new CoverDevice($datas['id'], $datas['name'], $datas['icon']);
                $device->setData($datas['data']);
                break;
            default:
                return null;
                break;
        }
        return $device;
    }


    /**
     * Créer l'objet vierge de l'équipement à partir de son ID et son type
     * 
     * @param String $id   : Identifiant de l'équipement
     * @param String $type : Type de l'équipement
     * @return Device
     */
    static public function createDeviceFromId($id, $type)
    {
        switch ($type) {
            case self::SWITCH :
                $device = new SwitchDevice($datas['id']);
                break;
            case self::COVER :
                $device = new CoverDevice($datas['id']);
                break;
            default:
                return null;
                break;
        }
        return $device;
    }

}