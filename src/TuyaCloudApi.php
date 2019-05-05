<?php
/**
 * Librairie de base de l'API
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi;

use Sabinus\TuyaCloudApi\Session\Session;
use Sabinus\TuyaCloudApi\Device\DeviceFactory;
use Sabinus\TuyaCloudApi\Device\DeviceEvent;
use GuzzleHttp\Psr7\Uri;


class TuyaCloudApi
{

    //private $client;

    /**
     * @var Session
     */
    private $session;
    
    private $devices;
    
    public function __construct(Session $session)
    {
        $this->session = $session;
        //$this->client = new Client();
    }


    public function discoverDevices(Type $var = null)
    {
        $response = $this->_request('Discovery', 'discovery');

        //print_r($response['payload']['devices']);
        $this->devices = array();
        foreach ($response['payload']['devices'] as $datas) {
            $this->devices[] = DeviceFactory::createDeviceFromDatas($datas);
        }
        //var_dump($this->devices);
        return $this->devices;
       
    }


    public function getDeviceById($id)
    {
        foreach ($this->devices as $device) {
            if ($device && $device->getId() == $id)
                return $device;
        }
        return null;
    }


    public function controlDevice($id, $action, array $payload = [], $namespace = 'control')
    {
        $payload['devId'] = $id;
        return $this->_request($action, $namespace, $payload);
    }


    public function sendEvent(DeviceEvent $event, $namespace = 'control')
    {
        return $this->_request($event->getAction(), $namespace, $event->getPayload());
    }

    
    private function _request($name, $namespace, array $payload = [])
    {
        $token = $this->session->getToken();
        var_dump($token);
        if (!$token) return null;
        
        $response = $this->session->getClient()->post(new Uri('/homeassistant/skill'), array(
            'json' => array(
                'header' => array(
                    'name'           => $name,
                    'namespace'      => $namespace,
                    'payloadVersion' => 1,
                ),
                    'payload' => $payload + array(
                    'accessToken'    => $token,
                ),
            ),
        ));
        //print_r(json_decode($response));
        $response = json_decode((string) $response->getBody(), true); // TODO gestion erreur

        return $response;
    }

}

