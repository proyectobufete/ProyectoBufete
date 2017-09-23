<?php

namespace BufeteBundle\Entity;

/**
 * Paises
 */
class Paises
{
    /**
     * @var integer
     */
    private $idPais;

    /**
     * @var string
     */
    private $pais;

    /**
     * @var boolean
     */
    private $estadoPais;


    /**
     * Get idPais
     *
     * @return integer
     */
    public function getIdPais()
    {
        return $this->idPais;
    }

    /**
     * Set pais
     *
     * @param string $pais
     *
     * @return Paises
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set estadoPais
     *
     * @param boolean $estadoPais
     *
     * @return Paises
     */
    public function setEstadoPais($estadoPais)
    {
        $this->estadoPais = $estadoPais;

        return $this;
    }

    /**
     * Get estadoPais
     *
     * @return boolean
     */
    public function getEstadoPais()
    {
        return $this->estadoPais;
    }
}

