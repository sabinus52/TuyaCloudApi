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
use Sabinus\TuyaCloudApi\Device\Device;
use Sabinus\TuyaCloudApi\Device\DeviceFactory;
use Sabinus\TuyaCloudApi\Device\DeviceEvent;
use GuzzleHttp\Psr7\Uri;


class TuyaCloudApi
{

    /**
     * @var Session
     */
    private $session;
    
    /**
     * Tableau des devives trouvés
     */
    private $devices;
    


    /**
     * Contructeur
     * 
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }


    /**
     * Recherche tous les équipements disponibles pour cette session
     * 
     * @return Array|Boolean
     */
    public function discoverDevices()
    {
        $response = $this->_request('Discovery', 'discovery');

        // Si problème ou bien trop de requête à la suite
        if ( ! isset($response['payload']['devices']) ) {
            throw new \Exception('Problème ou trop de requête de découverte effectuée. Attendre 10 min avant de refaire une nouvelle découverte');
        }

        $this->devices = array();
        foreach ($response['payload']['devices'] as $datas) {
            $this->devices[] = DeviceFactory::createDeviceFromDatas($datas);
        }

        return $this->devices;
    }


    /**
     * Retourne l'objet du device
     * 
     * @param String $id : Identifiant du device
     * @return Device
     */
    public function getDeviceById($id)
    {
        foreach ($this->devices as $device) {
            if ($device && $device->getId() == $id)
                return $device;
        }
        return null;
    }


    /**
     * Envoi une requête de controle de l'équipement
     * 
     * @param String $id        : Identifiant du device
     * @param String $action    : Valeur de l'action à effectuer
     * @param Array  $payload   : Données à envoyer
     * @param String $namespace : Espace de nom
     * @return Array
     */
    public function controlDevice($id, $action, array $payload = [], $namespace = 'control')
    {
        $payload['devId'] = $id;
        return $this->_request($action, $namespace, $payload);
    }


    /**
     * Envoi un évènement de controle de l'équipement
     * 
     * @param DeviceEvent $event     : Objet de l'évènement à effectuer sur l'équipement
     * @param String      $namespace : Espace de nom
     * @return Array
     */
    public function sendEvent(DeviceEvent $event, $namespace = 'control')
    {
        return $this->_request($event->getAction(), $namespace, $event->getPayload());
    }


    /**
     * Effectue une requête HTTP dans le Cloud Tuya
     * 
     * @param String $name      : Valeur de l'action à effectuer
     * @param String $namespace : Espace de nom
     * @param Array  $payload   : Données à envoyer
     * @return Array
     */
    private function _request($name, $namespace, array $payload = [])
    {
        $token = $this->session->getToken();
        if (!$token) return null;

        // Si mode découverte limité à une seule intérrogation toutes les X minutes
        if ( $namespace == 'discovery' ) {
            $discovery = $this->session->getDiscoveryRequest();
            if ( $discovery != null ) return $discovery;
        }

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
        $response = json_decode((string) $response->getBody(), true);
        $this->session->checkResponse($response, sprintf('Failed to get "%s" response from Cloud Tuya', $name));

        // Si mode découverte limité à une seule intérrogation toutes les X minutes
        if ( $namespace == 'discovery' ) {
            $this->session->setPointDiscovery($response);
        }

        return $response;
    }

}