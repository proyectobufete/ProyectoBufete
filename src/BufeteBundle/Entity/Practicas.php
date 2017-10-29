<?php

namespace BufeteBundle\Entity;

/**
 * Practicas
 */
class Practicas
{
    /**
     * @var integer
     */
    private $idPractica;

    /**
     * @var \DateTime
     */
    private $inicioPractica;

    /**
     * @var \DateTime
     */
    private $fechaFin;

    /**
     * @var boolean
     */
    private $estadoPractica;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var \BufeteBundle\Entity\Estudiantes
     */
    private $idEstudiante;


    /**
     * Get idPractica
     *
     * @return integer
     */
    public function getIdPractica()
    {
        return $this->idPractica;
    }

    /**
     * Set inicioPractica
     *
     * @param \DateTime $inicioPractica
     *
     * @return Practicas
     */
    public function setInicioPractica($inicioPractica)
    {
        $this->inicioPractica = $inicioPractica;

        return $this;
    }

    /**
     * Get inicioPractica
     *
     * @return \DateTime
     */
    public function getInicioPractica()
    {
        return $this->inicioPractica;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Practicas
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set estadoPractica
     *
     * @param boolean $estadoPractica
     *
     * @return Practicas
     */
    public function setEstadoPractica($estadoPractica)
    {
        $this->estadoPractica = $estadoPractica;

        return $this;
    }

    /**
     * Get estadoPractica
     *
     * @return boolean
     */
    public function getEstadoPractica()
    {
        return $this->estadoPractica;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Practicas
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set idEstudiante
     *
     * @param \BufeteBundle\Entity\Estudiantes $idEstudiante
     *
     * @return Practicas
     */
    public function setIdEstudiante(\BufeteBundle\Entity\Estudiantes $idEstudiante = null)
    {
        $this->idEstudiante = $idEstudiante;

        return $this;
    }

    /**
     * Get idEstudiante
     *
     * @return \BufeteBundle\Entity\Estudiantes
     */
    public function getIdEstudiante()
    {
        return $this->idEstudiante;
    }
}
