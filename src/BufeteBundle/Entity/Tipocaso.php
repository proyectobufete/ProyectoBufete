<?php

namespace BufeteBundle\Entity;

/**
 * Tipocaso
 */
class Tipocaso
{
    /**
     * @var integer
     */
    private $idTipo;

    /**
     * @var string
     */
    private $tipo;

    /**
     * @var boolean
     */
    private $estadoTipo;


    /**
     * Get idTipo
     *
     * @return integer
     */
    public function getIdTipo()
    {
        return $this->idTipo;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Tipocaso
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set estadoTipo
     *
     * @param boolean $estadoTipo
     *
     * @return Tipocaso
     */
    public function setEstadoTipo($estadoTipo)
    {
        $this->estadoTipo = $estadoTipo;

        return $this;
    }

    /**
     * Get estadoTipo
     *
     * @return boolean
     */
    public function getEstadoTipo()
    {
        return $this->estadoTipo;
    }
}

