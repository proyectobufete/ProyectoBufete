<?php

namespace BufeteBundle\Entity;

/**
 * Estadosciviles
 */
class Estadosciviles
{
    /**
     * @var integer
     */
    private $idEstadocivil;

    /**
     * @var string
     */
    private $estadocivil;

    /**
     * @var boolean
     */
    private $estadoEstadocivil;


    /**
     * Get idEstadocivil
     *
     * @return integer
     */
    public function getIdEstadocivil()
    {
        return $this->idEstadocivil;
    }

    /**
     * Set estadocivil
     *
     * @param string $estadocivil
     *
     * @return Estadosciviles
     */
    public function setEstadocivil($estadocivil)
    {
        $this->estadocivil = $estadocivil;

        return $this;
    }

    /**
     * Get estadocivil
     *
     * @return string
     */
    public function getEstadocivil()
    {
        return $this->estadocivil;
    }

    /**
     * Set estadoEstadocivil
     *
     * @param boolean $estadoEstadocivil
     *
     * @return Estadosciviles
     */
    public function setEstadoEstadocivil($estadoEstadocivil)
    {
        $this->estadoEstadocivil = $estadoEstadocivil;

        return $this;
    }

    /**
     * Get estadoEstadocivil
     *
     * @return boolean
     */
    public function getEstadoEstadocivil()
    {
        return $this->estadoEstadocivil;
    }
}

