<?php
/**
 * Token de la session
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Session;


class Token
{

	private $tokenAccess;

    private $tokenRefresh;

    private $expireTime;


	


    public function get()
    {
        return $this->tokenAccess;
    }


    public function set(array $data)
    {
        $this->tokenAccess = $data['access_token'];
        $this->tokenRefresh = $data['refresh_token'];
        $this->expireTime = $data['expires_in'];
    }


    public function has()
    {
        if ( $this->tokenAccess && $this->tokenRefresh && $this->expireTime )
            return true;
        else
            return false;
    }

    public function isValid()
    {
        return time() + $this->expireTime > time();
    }

    


}