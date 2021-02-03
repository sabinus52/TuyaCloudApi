<?php
/**
 * Librairie du pooler de stockage du token Tuya via le système de fichier
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Tools;


class TokenPool extends Pool
{

    const TOKEN_FILE = 'tuya.token';

    /**
     * Constructeur
     * 
     * @param String $folder
     */
    public function __construct($folder = null)
    {
        $this->filename = self::TOKEN_FILE;
        parent::__construct();
    }

}
