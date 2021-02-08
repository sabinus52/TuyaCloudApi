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
use Sabinus\TuyaCloudApi\Request\DiscoveryRequest;
use Sabinus\TuyaCloudApi\Request\ControlRequest;
use Sabinus\TuyaCloudApi\Request\QueryRequest;
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
     * Vérifie la connexion
     * 
     * @return Boolean
     */
    public function checkConnection()
    {
        $token = $this->session->getToken();
        return ( $token ) ? true : false;
    }


    /**
     * Recherche tous les équipements disponibles pour cette session
     * 
     * @return Array|Boolean
     */
    public function discoverDevices()
    {
        $reqDiscovery = new DiscoveryRequest($this->session);
        $reqDiscovery->request();

        $this->devices = $reqDiscovery->fetchDevices();
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
        switch ($namespace) {
            case 'query' :
                $req = new QueryRequest($this->session);
                break;
            case 'control' :
            default :
                $req = new ControlRequest($this->session);
                break;
        }

        return $req->request($action, $payload);
    }


    /**
     * Envoi une requête de query de l'équipement
     * 
     * @param String $id        : Identifiant du device
     * @return QueryRequest
     */
    public function getQueryDevice($id)
    {
        $payload['devId'] = $id;
        $query = new QueryRequest($this->session);
        $query->request('QueryDevice', $payload);
        return $query;
    }

}