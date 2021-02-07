<?php
/**
 * Classe de la requête de la découverte des objets
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Request;

use Sabinus\TuyaCloudApi\Session\Session;
use Sabinus\TuyaCloudApi\Tools\CachePool;
use Sabinus\TuyaCloudApi\Device\DeviceFactory;


class DiscoveryRequest extends Request implements RequestInterface
{
   
    /**
     * Délai entre 2 requêtes de découverte (restriction Tuya)
     */
    const CACHE_DELAY = 600;

    /**
     * Fichier du cache de la découverte
     */
    const CACHE_FILE = 'tuya.discovery';


    /**
     * Cache du Pool de la découverte
     * 
     * @var CachePool
     */
    private $discoveryPool;

    

    /**
     * Contructeur
     * 
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->discoveryPool = new CachePool(self::CACHE_FILE);
        parent::__construct($session);
    }


    public function request($action = 'Discovery', $namespace = 'discovery', array $payload = [])
    {
        // Si mode découverte limité à une seule intérrogation toutes les X minutes
        $this->response = $this->discoveryPool->fetchFromCache(self::CACHE_DELAY);
        if ( $this->response != null ) return $this->response;

        // Sinon fait la requête au Cloud
        parent::request($action, $namespace, $payload);

        // Sauvegarde dans le cache
        $this->discoveryPool->storeInCache($this->response);

        return $this->response;
    }


    /**
     * Retourne la liste des objets découverts
     * 
     * @return Array of Device
     */
    public function fetchDevices()
    {
        if ( ! isset($this->response['payload']['devices']) ) {
            return null;
        }

        $result = array();
        foreach ($this->response['payload']['devices'] as $datas) {
            $result[] = DeviceFactory::createDeviceFromDatas($datas);
        }

        return $result;
    }

}