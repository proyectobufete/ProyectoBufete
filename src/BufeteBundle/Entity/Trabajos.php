<?php

namespace BufeteBundle\Entity;

/**
 * Trabajos
 */
class Trabajos
{
    /**
     * @var integer
     */
    private $idTrabajo;

    /**
     * @var string
     */
    private $trabajo;

    /**
     * @var boolean
     */
    private $estadoTrabajo;


    /**
     * Get idTrabajo
     *
     * @return integer
     */
    public function getIdTrabajo()
    {
        return $this->idTrabajo;
    }

    /**
     * Set trabajo
     *
     * @param string $trabajo
     *
     * @return Trabajos
     */
    public function setTrabajo($trabajo)
    {
        $this->trabajo = $trabajo;

        return $this;
    }

    /**
     * Get trabajo
     *
     * @return string
     */
    public function getTrabajo()
    {
        return $this->trabajo;
    }

    /**
     * Set estadoTrabajo
     *
     * @param boolean $estadoTrabajo
     *
     * @return Trabajos
     */
    public function setEstadoTrabajo($estadoTrabajo)
    {
        $this->estadoTrabajo = $estadoTrabajo;

        return $this;
    }

    /**
     * Get estadoTrabajo
     *
     * @return boolean
     */
    public function getEstadoTrabajo()
    {
        return $this->estadoTrabajo;
    }
}

