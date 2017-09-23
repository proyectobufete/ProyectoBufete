<?php

namespace BufeteBundle\Entity;

/**
 * Clinicas
 */
class Clinicas
{
    /**
     * @var integer
     */
    private $idClinica;

    /**
     * @var string
     */
    private $nombreClinica;

    /**
     * @var \DateTime
     */
    private $fechaAsignacion;

    /**
     * @var \DateTime
     */
    private $fechaFin;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var integer
     */
    private $estadoClinica;

    /**
     * @var \BufeteBundle\Entity\Tipocaso
     */
    private $idTipo;

    /**
     * @var \BufeteBundle\Entity\Personas
     */
    private $idPersona;


    /**
     * Get idClinica
     *
     * @return integer
     */
    public function getIdClinica()
    {
        return $this->idClinica;
    }

    /**
     * Set nombreClinica
     *
     * @param string $nombreClinica
     *
     * @return Clinicas
     */
    public function setNombreClinica($nombreClinica)
    {
        $this->nombreClinica = $nombreClinica;

        return $this;
    }

    /**
     * Get nombreClinica
     *
     * @return string
     */
    public function getNombreClinica()
    {
        return $this->nombreClinica;
    }

    /**
     * Set fechaAsignacion
     *
     * @param \DateTime $fechaAsignacion
     *
     * @return Clinicas
     */
    public function setFechaAsignacion($fechaAsignacion)
    {
        $this->fechaAsignacion = $fechaAsignacion;

        return $this;
    }

    /**
     * Get fechaAsignacion
     *
     * @return \DateTime
     */
    public function getFechaAsignacion()
    {
        return $this->fechaAsignacion;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Clinicas
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Clinicas
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
     * Set estadoClinica
     *
     * @param integer $estadoClinica
     *
     * @return Clinicas
     */
    public function setEstadoClinica($estadoClinica)
    {
        $this->estadoClinica = $estadoClinica;

        return $this;
    }

    /**
     * Get estadoClinica
     *
     * @return integer
     */
    public function getEstadoClinica()
    {
        return $this->estadoClinica;
    }

    /**
     * Set idTipo
     *
     * @param \BufeteBundle\Entity\Tipocaso $idTipo
     *
     * @return Clinicas
     */
    public function setIdTipo(\BufeteBundle\Entity\Tipocaso $idTipo = null)
    {
        $this->idTipo = $idTipo;

        return $this;
    }

    /**
     * Get idTipo
     *
     * @return \BufeteBundle\Entity\Tipocaso
     */
    public function getIdTipo()
    {
        return $this->idTipo;
    }

    /**
     * Set idPersona
     *
     * @param \BufeteBundle\Entity\Personas $idPersona
     *
     * @return Clinicas
     */
    public function setIdPersona(\BufeteBundle\Entity\Personas $idPersona = null)
    {
        $this->idPersona = $idPersona;

        return $this;
    }

    /**
     * Get idPersona
     *
     * @return \BufeteBundle\Entity\Personas
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }
}

