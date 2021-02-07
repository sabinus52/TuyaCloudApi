<?php
/**
 * Classe de la requête pour le controle d'un objet
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Request;


class ControlRequest extends Request implements RequestInterface
{

    public function request($action, $namespace = 'control', array $payload = [])
    {
        parent::request($action, $namespace, $payload);
    }

}