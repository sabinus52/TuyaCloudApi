<?php
/**
 * Interface de l'ojet Request
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Request;


 interface RequestInterface
 {

    /**
     * Requête au Cloud Tuya
     * 
     * @param String $action    : Valeur de l'action à effectuer
     * @param String $namespace : Espace de nom
     * @param Array  $payload   : Données à envoyer
     */
    public function request($action, $namespace, array $payload = []);

 }
