<?php

namespace Sabinus\TuyaCloudApi\Device;

class DeviceFactory
{

    const SWITCH = 'switch';

    static public function createDevice(array $datas)
    {
        switch ($datas['dev_type']) {
            case self::SWITCH :
                $device = new SwitchDevice($datas);
                $device->setState($datas['data']['state']);
                break;
            
            default:
                return null;
                break;
        }
        return $device;
    }

}
