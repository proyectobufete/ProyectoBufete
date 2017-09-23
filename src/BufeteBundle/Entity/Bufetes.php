<?php

namespace BufeteBundle\Entity;

/**
 * Bufetes
 */
class Bufetes
{
    /**
     * @var integer
     */
    private $idBufete;

    /**
     * @var string
     */
    private $nombreBufete;

    /**
     * @var integer
     */
    private $telefono1Bufete;

    /**
     * @var integer
     */
    private $telefono2;

    /**
     * @var string
     */
    private $emailBufete;

    /**
     * @var string
     */
    private $direccionBufete;

    /**
     * @var boolean
     */
    private $estadoBufete;

    /**
     * @var \BufeteBundle\Entity\Ciudad
     */
    private $idCiudad;


    /**
     * Get idBufete
     *
     * @return integer
     */
    public function getIdBufete()
    {
        return $this->idBufete;
    }

    /**
     * Set nombreBufete
     *
     * @param string $nombreBufete
     *
     * @return Bufetes
     */
    public function setNombreBufete($nombreBufete)
    {
        $this->nombreBufete = $nombreBufete;

        return $this;
    }

    /**
     * Get nombreBufete
     *
     * @return string
     */
    public function getNombreBufete()
    {
        return $this->nombreBufete;
    }

    /**
     * Set telefono1Bufete
     *
     * @param integer $telefono1Bufete
     *
     * @return Bufetes
     */
    public function setTelefono1Bufete($telefono1Bufete)
    {
        $this->telefono1Bufete = $telefono1Bufete;

        return $this;
    }

    /**
     * Get telefono1Bufete
     *
     * @return integer
     */
    public function getTelefono1Bufete()
    {
        return $this->telefono1Bufete;
    }

    /**
     * Set telefono2
     *
     * @param integer $telefono2
     *
     * @return Bufetes
     */
    public function setTelefono2($telefono2)
    {
        $this->telefono2 = $telefono2;

        return $this;
    }

    /**
     * Get telefono2
     *
     * @return integer
     */
    public function getTelefono2()
    {
        return $this->telefono2;
    }

    /**
     * Set emailBufete
     *
     * @param string $emailBufete
     *
     * @return Bufetes
     */
    public function setEmailBufete($emailBufete)
    {
        $this->emailBufete = $emailBufete;

        return $this;
    }

    /**
     * Get emailBufete
     *
     * @return string
     */
    public function getEmailBufete()
    {
        return $this->emailBufete;
    }

    /**
     * Set direccionBufete
     *
     * @param string $direccionBufete
     *
     * @return Bufetes
     */
    public function setDireccionBufete($direccionBufete)
    {
        $this->direccionBufete = $direccionBufete;

        return $this;
    }

    /**
     * Get direccionBufete
     *
     * @return string
     */
    public function getDireccionBufete()
    {
        return $this->direccionBufete;
    }

    /**
     * Set estadoBufete
     *
     * @param boolean $estadoBufete
     *
     * @return Bufetes
     */
    public function setEstadoBufete($estadoBufete)
    {
        $this->estadoBufete = $estadoBufete;

        return $this;
    }

    /**
     * Get estadoBufete
     *
     * @return boolean
     */
    public function getEstadoBufete()
    {
        return $this->estadoBufete;
    }

    /**
     * Set idCiudad
     *
     * @param \BufeteBundle\Entity\Ciudad $idCiudad
     *
     * @return Bufetes
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

