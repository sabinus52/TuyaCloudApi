<?php
/**
 * Session de l'utilisateur au cloud
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Session;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;


class Session
{

    /**
     * Utilisateur de connection
     * 
     * @var String
     */
    private $username;

    /**
     * Mot de passe de connection
     * 
     * @var String
     */
    private $password;

    /**
     * Code du pays de l'utilisateur = indicatif téléphonique (33 : France)
     * 
     * @var Integer
     */
    private $countryCode;

    /**
     * @var Platform
     */
    private $platform;


    private $client;

    /**
     * @var Token
     */
    private $token;


    public function __construct($username, $password, $country, $biztype = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->countryCode = $country;
        $this->platform = new Platform($biztype);
        $this->token = new Token();
        $this->client = $this->_createClient();
    }


    public function getToken()
    {
        if ( !$this->token->has() ) {
            $this->_createToken();
        }
        if ( !$this->token->isValid() ) {
            $this->_refreshToken();
        }
        return $this->token->get();
    }


    public function getClient()
    {
        return $this->client;
    }


    private function _createClient()
    {
        return new Client(array(
            'base_uri' => $this->platform->getBaseUrl(),
            'connect_timeout' => 2.0,
            'timeout' => 2.0,
        ));
    }


    private function _createToken()
    {
        //$uri = UriResolver::resolve($this->getBaseUrl($this->session), new Uri('homeassistant/auth.do'));

        //$url = UriResolver::resolve($this->platform->getBaseUrl(), new Uri('homeassistant/auth.do'));
        $response = $this->client->post(new Uri('homeassistant/auth.do'), array(
            'form_params' => array(
                'userName'    => $this->username,
                'password'    => $this->password,
                'countryCode' => $this->countryCode,
                'bizType'     => $this->platform->getBizType(),
                'from'        => 'tuya',
            ),
        ));
        print 'CREATE : '.$response->getBody()."\n";
        $response = json_decode((string) $response->getBody(), true); // TODO gestion erreur

        $this->token->set($response);

        $this->platform->setRegionFromToken($this->token->get());
        $this->client = $this->_createClient();
    }


    private function _refreshToken()
    {
        //$url = UriResolver::resolve($this->platform->getBaseUrl(), new Uri('homeassistant/access.do'));
        $response = $this->client->get(new Uri('homeassistant/access.do'), array(
            'query' => array(
                'grant_type'    => 'refresh_token',
                'refresh_token' => $this->tokenManager->getToken()->getRefreshToken(),
            ),
        ));
        print 'REFRESH : '.$response->getBody()."\n";
        $response = json_decode((string) $response->getBody(), true); // TODO gestion erreur

        $this->token->set($response);
    }

    /*public function getPlatform()
    {
        return $this->platform;
    }*/


    /*public function getBaseUrl($uri)
    {
        return $this->platform->getBaseUrl($uri);
    }*/

}