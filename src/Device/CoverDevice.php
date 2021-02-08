<?php
/**
 * Classe de l'équipement de type cover (volets roulants)
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */
namespace Sabinus\TuyaCloudApi\Device;

use Sabinus\TuyaCloudApi\Session\Session;


class CoverDevice extends Device implements DeviceInterface
{

    /**
     * Constructeur
     */
    public function __construct(Session $session, $id, $name = '', $icon = '')
    {
        parent::__construct($session, $id, $name, $icon);
        $this->type = DeviceFactory::TUYA_COVER;
    }


    /**
     * Retourne le statut de la prise
     * 
     * @return Boolean
     */
    public function getState()
    {
        return $this->data['state'];
    }


    /**
     * Retourne le support du bouton STOP
     * 
     * @return Boolean
     */
    public function getSupportStop()
    {
        return $this->data['support_stop'];
    }


    /**
     * Ouvre le volet
     * 
     * @return DeviceEvent
     */
    public function getOpenEvent()
    {
        return new DeviceEvent($this, 'turnOnOff', array('value' => 1));
    }

    /**
     * Ouvre le volet
     * 
     * @return Array
     */
    public function open()
    {
        return $this->control('turnOnOff', array('value' => 1));
    }


    /**
     * Ferme le volet
     * 
     * @return DeviceEvent
     */
    public function getCloseEvent()
    {
        return new DeviceEvent($this, 'turnOnOff', array('value' => 0));
    }

    /**
     * Ferme le volet
     * 
     * @return Array
     */
    public function close()
    {
        return $this->control('turnOnOff', array('value' => 0));
    }


    /**
     * Stoppe le volet
     * 
     * @return DeviceEvent
     */
    public function getStopEvent()
    {
        return new DeviceEvent($this, 'startStop', array('value' => 0));
    }

    /**
     * Stoppe le volet
     * 
     * @return Array
     */
    public function stop()
    {
        return $this->control('startStop', array('value' => 0));
    }
    
}