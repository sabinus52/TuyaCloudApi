<?php

namespace Sabinus\TuyaCloudApi\Device;

use Sabinus\TuyaCloudApi\TuyaCloudApi;


class SwitchDevice extends Device implements DeviceInterface
{

    /**
     * Etat
     * 
     * @var Boolean
     */
    private $state;

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = ($state) ? true : false;
    }


    public function getTurnOnEvent()
    {
        return new DeviceEvent($this, 'turnOnOff', array('value' => 1));
    }

    public function turnOn(TuyaCloudApi $api)
    {
        return $api->controlDevice($this->id, 'turnOnOff', array('value' => 1));
    }


    public function getTurnOffEvent()
    {
        return new DeviceEvent($this, 'turnOnOff', array('value' => 0));
    }

    public function turnOff(TuyaCloudApi $api)
    {
        return $api->controlDevice($this->id, 'turnOnOff', array('value' => 0));
    }
    
}
