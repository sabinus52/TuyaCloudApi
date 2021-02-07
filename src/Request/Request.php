<?php
/**
 * Classe abstraite de l'objet Request sur les requête au Cloud Tuya
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Request;

use Sabinus\TuyaCloudApi\Session\Session;
use GuzzleHttp\Psr7\Uri;


abstract class Request
{
   
    /**
     * @var Session
     */
    protected $session;

    /**
     * Réponse de la requête
     * @var Array
     */
    protected $response;



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
     * Effectue une requête HTTP dans le Cloud Tuya
     * 
     * @param String $name      : Valeur de l'action à effectuer
     * @param String $namespace : Espace de nom
     * @param Array  $payload   : Données à envoyer
     * @return Array
     */
    protected function request($name, $namespace, array $payload = [])
    {
        $token = $this->session->getToken();
        if (!$token) return null;

        $this->response = $this->session->getClient()->post(new Uri('/homeassistant/skill'), array(
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
        $this->response = json_decode((string) $this->response->getBody(), true);
        $this->checkResponse(sprintf('Failed to get "%s" response from Cloud Tuya', $name));

        return $this->response;
    }


    /**
     * Vérifie si pas d'erreur dans le retour de la requête
     * 
     * @param String $message : Message par défaut
     * @throws Exception
     */
    protected function checkResponse($message = null)
    {
        if ( empty($this->response) ) {
            throw new \Exception($message.' : Datas return null');
        }
        if ( isset($this->response['responseStatus']) && $this->response['responseStatus'] === 'error' ) {
            $message = isset($this->response['errorMsg']) ? $this->response['errorMsg'] : $message;
            throw new \Exception($message);
        }
        if ( isset($this->response['header']['code']) && $this->response['header']['code'] == 'FrequentlyInvoke' ) {
            $message = isset($this->response['header']['msg']) ? $this->response['header']['msg'] : $message;
            throw new \Exception($message);
        }
    }

}