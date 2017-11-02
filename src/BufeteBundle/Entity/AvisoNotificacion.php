<?php

namespace BufeteBundle\Entity;

/**
 * AvisoNotificacion
 */
class AvisoNotificacion
{
    /**
     * @var integer
     */
    private $idAviso;

    /**
     * @var \DateTime
     */
    private $fechaVisita;

    /**
     * @var \DateTime
     */
    private $horaVisita;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var boolean
     */
    private $vista;

    /**
     * @var \BufeteBundle\Entity\Casos
     */
    private $idCaso;

    /**
     * @var \BufeteBundle\Entity\Estudiantes
     */
    private $idEstudiante;

    /**
     * @var \BufeteBundle\Entity\Demandantes
     */
    private $idDemandante;

    /**
     * @var \BufeteBundle\Entity\Personas
     */
    private $idPersona;


    /**
     * Get idAviso
     *
     * @return integer
     */
    public function getIdAviso()
    {
        return $this->idAviso;
    }

    /**
     * Set fechaVisita
     *
     * @param \DateTime $fechaVisita
     *
     * @return AvisoNotificacion
     */
    public function setFechaVisita($fechaVisita)
    {
        $this->fechaVisita = $fechaVisita;

        return $this;
    }

    /**
     * Get fechaVisita
     *
     * @return \DateTime
     */
    public function getFechaVisita()
    {
        return $this->fechaVisita;
    }

    /**
     * Set horaVisita
     *
     * @param \DateTime $horaVisita
     *
     * @return AvisoNotificacion
     */
    public function setHoraVisita($horaVisita)
    {
        $this->horaVisita = $horaVisita;

        return $this;
    }

    /**
     * Get horaVisita
     *
     * @return \DateTime
     */
    public function getHoraVisita()
    {
        return $this->horaVisita;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return AvisoNotificacion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set vista
     *
     * @param boolean $vista
     *
     * @return AvisoNotificacion
     */
    public function setVista($vista)
    {
        $this->vista = $vista;

        return $this;
    }

    /**
     * Get vista
     *
     * @return boolean
     */
    public function getVista()
    {
        return $this->vista;
    }

    /**
     * Set idCaso
     *
     * @param \BufeteBundle\Entity\Casos $idCaso
     *
     * @return AvisoNotificacion
     */
    public function setIdCaso(\BufeteBundle\Entity\Casos $idCaso = null)
    {
        $this->idCaso = $idCaso;

        return $this;
    }

    /**
     * Get idCaso
     *
     * @return \BufeteBundle\Entity\Casos
     */
    public function getIdCaso()
    {
        return $this->idCaso;
    }

    /**
     * Set idEstudiante
     *
     * @param \BufeteBundle\Entity\Estudiantes $idEstudiante
     *
     * @return AvisoNotificacion
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

    /**
     * Set idDemandante
     *
     * @param \BufeteBundle\Entity\Demandantes $idDemandante
     *
     * @return AvisoNotificacion
     */
    public function setIdDemandante(\BufeteBundle\Entity\Demandantes $idDemandante = null)
    {
        $this->idDemandante = $idDemandante;

        return $this;
    }

    /**
     * Get idDemandante
     *
     * @return \BufeteBundle\Entity\Demandantes
     */
    public function getIdDemandante()
    {
        return $this->idDemandante;
    }

    /**
     * Set idPersona
     *
     * @param \BufeteBundle\Entity\Personas $idPersona
     *
     * @return AvisoNotificacion
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

