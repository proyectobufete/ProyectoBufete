<?php

namespace BufeteBundle\Entity;

/**
 * Demandantes
 */
class Demandantes
{
    /**
     * @var integer
     */
    private $idDemandante;

    /**
     * @var string
     */
    private $nombreDemandante;

    /**
     * @var integer
     */
    private $edadDemandante;

    /**
     * @var integer
     */
    private $dpiDemandante;

    /**
     * @var string
     */
    private $cedulaDemandante;

    /**
     * @var string
     */
    private $direccionDemandante;

    /**
     * @var string
     */
    private $dirtrabajoDemandante;

    /**
     * @var integer
     */
    private $telefonoDemandante;

    /**
     * @var \BufeteBundle\Entity\Estadosciviles
     */
    private $idEstadocivil;

    /**
     * @var \BufeteBundle\Entity\Trabajos
     */
    private $idTrabajo;

    /**
     * @var \BufeteBundle\Entity\Ciudad
     */
    private $idCiudad;


    /**
     * Get idDemandante
     *
     * @return integer
     */
    public function getIdDemandante()
    {
        return $this->idDemandante;
    }

    /**
     * Set nombreDemandante
     *
     * @param string $nombreDemandante
     *
     * @return Demandantes
     */
    public function setNombreDemandante($nombreDemandante)
    {
        $this->nombreDemandante = $nombreDemandante;

        return $this;
    }

    /**
     * Get nombreDemandante
     *
     * @return string
     */
    public function getNombreDemandante()
    {
        return $this->nombreDemandante;
    }

    /**
     * Set edadDemandante
     *
     * @param integer $edadDemandante
     *
     * @return Demandantes
     */
    public function setEdadDemandante($edadDemandante)
    {
        $this->edadDemandante = $edadDemandante;

        return $this;
    }

    /**
     * Get edadDemandante
     *
     * @return integer
     */
    public function getEdadDemandante()
    {
        return $this->edadDemandante;
    }

    /**
     * Set dpiDemandante
     *
     * @param integer $dpiDemandante
     *
     * @return Demandantes
     */
    public function setDpiDemandante($dpiDemandante)
    {
        $this->dpiDemandante = $dpiDemandante;

        return $this;
    }

    /**
     * Get dpiDemandante
     *
     * @return integer
     */
    public function getDpiDemandante()
    {
        return $this->dpiDemandante;
    }

    /**
     * Set cedulaDemandante
     *
     * @param string $cedulaDemandante
     *
     * @return Demandantes
     */
    public function setCedulaDemandante($cedulaDemandante)
    {
        $this->cedulaDemandante = $cedulaDemandante;

        return $this;
    }

    /**
     * Get cedulaDemandante
     *
     * @return string
     */
    public function getCedulaDemandante()
    {
        return $this->cedulaDemandante;
    }

    /**
     * Set direccionDemandante
     *
     * @param string $direccionDemandante
     *
     * @return Demandantes
     */
    public function setDireccionDemandante($direccionDemandante)
    {
        $this->direccionDemandante = $direccionDemandante;

        return $this;
    }

    /**
     * Get direccionDemandante
     *
     * @return string
     */
    public function getDireccionDemandante()
    {
        return $this->direccionDemandante;
    }

    /**
     * Set dirtrabajoDemandante
     *
     * @param string $dirtrabajoDemandante
     *
     * @return Demandantes
     */
    public function setDirtrabajoDemandante($dirtrabajoDemandante)
    {
        $this->dirtrabajoDemandante = $dirtrabajoDemandante;

        return $this;
    }

    /**
     * Get dirtrabajoDemandante
     *
     * @return string
     */
    public function getDirtrabajoDemandante()
    {
        return $this->dirtrabajoDemandante;
    }

    /**
     * Set telefonoDemandante
     *
     * @param integer $telefonoDemandante
     *
     * @return Demandantes
     */
    public function setTelefonoDemandante($telefonoDemandante)
    {
        $this->telefonoDemandante = $telefonoDemandante;

        return $this;
    }

    /**
     * Get telefonoDemandante
     *
     * @return integer
     */
    public function getTelefonoDemandante()
    {
        return $this->telefonoDemandante;
    }

    /**
     * Set idEstadocivil
     *
     * @param \BufeteBundle\Entity\Estadosciviles $idEstadocivil
     *
     * @return Demandantes
     */
    public function setIdEstadocivil(\BufeteBundle\Entity\Estadosciviles $idEstadocivil = null)
    {
        $this->idEstadocivil = $idEstadocivil;

        return $this;
    }

    /**
     * Get idEstadocivil
     *
     * @return \BufeteBundle\Entity\Estadosciviles
     */
    public function getIdEstadocivil()
    {
        return $this->idEstadocivil;
    }

    /**
     * Set idTrabajo
     *
     * @param \BufeteBundle\Entity\Trabajos $idTrabajo
     *
     * @return Demandantes
     */
    public function setIdTrabajo(\BufeteBundle\Entity\Trabajos $idTrabajo = null)
    {
        $this->idTrabajo = $idTrabajo;

        return $this;
    }

    /**
     * Get idTrabajo
     *
     * @return \BufeteBundle\Entity\Trabajos
     */
    public function getIdTrabajo()
    {
        return $this->idTrabajo;
    }

    /**
     * Set idCiudad
     *
     * @param \BufeteBundle\Entity\Ciudad $idCiudad
     *
     * @return Demandantes
     */
    public function setIdCiudad(\BufeteBundle\Entity\Ciudad $idCiudad = null)
    {
        $this->idCiudad = $idCiudad;

        return $this;
    }

    /**
     * Get idCiudad
     *
     * @return \BufeteBundle\Entity\Ciudad
     */
    public function getIdCiudad()
    {
        return $this->idCiudad;
    }
}

