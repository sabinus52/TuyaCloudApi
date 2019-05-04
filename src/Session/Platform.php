<?php
/**
 * Plateforme du cloud Tuya
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Session;

use GuzzleHttp\Psr7\Uri;


class Platform
{

	const BASE_URL_FORMAT = 'https://px1.tuya%s.com';

	/**
	 * Constante de la liste des plateformes Tuya ou Smart Life
	 */
	const TUYA = 'tuya';
    const SMART_LIFE = 'smart_life';
    
    /**
     * Liste des regions des plateformes
     */
    const CN = 'cn';
    const EU = 'eu';
    const US = 'us';


    /**
     * Type de la plateforme utilisé (Tuya ou Smart Life)
     * 
     * @var String
     */
    private $biztype;


    /**
     * Region renvoyé par le token qui devra être utilisé dans les requêtes
     * 
     * @var String
     */
    private $region;

    

    public function __construct($biztype, $region = self::EU)
    {
        $this->biztype = ($biztype) ? $biztype : self::TUYA;
        $this->region = $region;
    }


    public function getBizType()
    {
    	return $this->biztype;
    }


    public function getRegion()
    {
        return $this->region;
    }


    public function setRegionFromToken($token)
    {
        $prefix = substr($token, 0, 2);
        $this->region = '';
        switch ($prefix) {
            case 'AY' : $this->region = self::CN; break;
            case 'EU' : $this->region = self::EU; break;
            case 'US' : $this->region = self::US; break;
        }
    }


    public function getBaseUrl()
    {
        return new Uri(sprintf(self::BASE_URL_FORMAT, $this->region));
    }


}