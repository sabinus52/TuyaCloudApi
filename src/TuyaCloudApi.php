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
use GuzzleHttp\Psr7\Uri;


class TuyaCloudApi
{

    //private $client;

    /**
     * @var Session
     */
    private $session;

    
    public function __construct(Session $session)
    {
        $this->session = $session;
        //$this->client = new Client();
    }


    public function discoverDevices(Type $var = null)
    {
        $response = $this->_request('Discovery', 'discovery');
        var_dump($response['payload']['devices']);
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

