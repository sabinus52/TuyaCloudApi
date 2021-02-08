<?php
/**
 * Classe de la requête de l'interrogation sur un objet
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Request;

use Sabinus\TuyaCloudApi\Session\Session;
use Sabinus\TuyaCloudApi\Tools\CachePool;


class QueryRequest extends Request implements RequestInterface
{    

    /**
     * NameSpace de le requête
     */
    const NAMESPACE = 'query';

    /**
     * Délai entre 2 requêtes de query (restriction Tuya)
     */
    const CACHE_DELAY = 120;

    /**
     * Fichier du cache de la requête
     */
    const CACHE_FILE = 'tuya.query';


    /**
     * Cache du Pool de la requête
     * 
     * @var CachePool
     */
    private $queryPool;

    

    /**
     * Contructeur
     * 
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->queryPool = new CachePool(self::CACHE_FILE);
        $this->namespace = self::NAMESPACE;
        parent::__construct($session);
    }


    public function request($action = 'QueryDevice', array $payload = [])
    {
        // Si mode découverte limité à une seule intérrogation toutes les X minutes
        $this->response = $this->queryPool->fetchFromCache(self::CACHE_DELAY);
        if ( $this->response != null ) return $this->response;

        // Sinon fait la requête au Cloud
        parent::_request($action, $this->namespace, $payload);

        // Sauvegarde dans le cache
        $this->queryPool->storeInCache($this->response);

        return $this->response;
    }


    /**
     * Retourne les données de l'objet suite à la requête
     * 
     * @return Array
     */
    public function getDatas()
    {
        if ( ! isset($this->response['payload']['data']) ) {
            return null;
        }

        return $this->response['payload']['data'];
    }

}