<?php
/**
 * Classe abstraite de l'ojet Device
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Device;


abstract class Device
{

    protected $api;
    
    /**
     * @var String
     */
    protected $id;
    
    /**
     * @var String
     */
    protected $type;

    /**
     * @var String
     */
    protected $name;
    
    /**
     * @var String
     */
    protected $icon;

    /**
     * @var Boolean
     */
    //protected $isOnline;


    public function __construct(array $datas, $api)
    {
        $this->id = $datas['id'];
        $this->type = $datas['dev_type'];
        $this->name = $datas['name'];
        $this->icon = $datas['icon'];

        $this->api = $api;
        
    }


    public function getId(): string
    {
        return $this->id;
    }

    
    public function getType(): string
    {
        return $this->type;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getIcon(): string
    {
        return $this->icon;
    }

}
