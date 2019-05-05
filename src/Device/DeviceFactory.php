<?php

namespace Sabinus\TuyaCloudApi\Device;

class DeviceFactory
{

    const SWITCH = 'switch';

    static public function createDevice($api, array $datas)
    {
        switch ($datas['dev_type']) {
            case self::SWITCH :
                $device = new SwitchDevice($datas);
                $device->setState($datas['data']['state'], $api);
                break;
            
            default:
                return null;
                break;
        }
        return $device;
    }

}
