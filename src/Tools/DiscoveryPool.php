<?php
/**
 * Librairie du pooler de stockage de la découverte des objets Tuya via le système de fichier
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Tools;


class DiscoveryPool extends Pool
{

    const DISCOVERY_FILE = 'tuya.discovery';


    /**
     * Constructeur
     * 
     * @param String $folder
     */
    public function __construct($folder = null)
    {
        $this->filename = self::DISCOVERY_FILE;
        parent::__construct();
    }

}
