<?php
/**
 * Classe abstraite de l'objet Device
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

namespace Sabinus\TuyaCloudApi\Device;


abstract class Device
{
   
    /**
     * Identifiant de l'équipement
     * 
     * @var String
     */
    protected $id;
    
    /**
     * Type de l'équipement
     * 
     * @var String
     */
    protected $type;

    /**
     * Nom de l'équipement
     * 
     * @var String
     */
    protected $name;
    
    /**
     * URL de l'icone de l'équipement
     * 
     * @var String
     */
    protected $icon;

    /**
     * Données supplémentaires du device
     * 
     * @var Array
     */
    protected $data;


    /**
     * Constructeur
     * 
     * @param String $id   : Identifiant du device
     * @param String $name : Nom du device
     * @param String $icon : URL de l'icone du device
     */
    public function __construct($id, $name = '', $icon = '')
    {
        $this->id = $id;
        if ($name) $this->name = $name;
        if ($icon) $this->icon = $icon;
    }


    /**
     * Affecte les données supplémentaires de l'équipement
     * 
     * @param Array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }


    /**
     * Retourne l'identifiant de l'équipement
     * 
     * @return String
     */
    public function getId(): string
    {
        return $this->id;
    }

    
    /**
     * Retourne le type de l'équipement
     * 
     * @return String
     */
    public function getType(): string
    {
        return $this->type;
    }


    /**
     * Retourne le nom de l'équipement
     * 
     * @return String
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Retourne l'URL de l'icone de l'équipement
     * 
     * @return String
     */
    public function getIcon(): string
    {
        return $this->icon;
    }


    /**
     * Retourne si l'équipement est en ligne ou pas
     * 
     * @return Boolean
     */
    public function isOnline()
    {
        return $this->data['online'];
    }

}