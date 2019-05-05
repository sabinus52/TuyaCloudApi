<?php

namespace Sabinus\TuyaCloudApi\Device;

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
    
}
