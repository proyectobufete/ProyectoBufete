<?php

namespace BufeteBundle\Entity;

/**
 * Revisiones
 */
class Revisiones
{
    /**
     * @var integer
     */
    private $idRevision;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var \DateTime
     */
    private $fechaEnvio;

    /**
     * @var \DateTime
     */
    private $fechaRevision;

    /**
     * @var string
     */
    private $ruta;

    /**
     * @var \BufeteBundle\Entity\Casos
     */
    private $idCaso;


    /**
     * Get idRevision
     *
     * @return integer
     */
    public function getIdRevision()
    {
        return $this->idRevision;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Revisiones
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
     * Set fechaEnvio
     *
     * @param \DateTime $fechaEnvio
     *
     * @return Revisiones
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = $fechaEnvio;

        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return \DateTime
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set fechaRevision
     *
     * @param \DateTime $fechaRevision
     *
     * @return Revisiones
     */
    public function setFechaRevision($fechaRevision)
    {
        $this->fechaRevision = $fechaRevision;

        return $this;
    }

    /**
     * Get fechaRevision
     *
     * @return \DateTime
     */
    public function getFechaRevision()
    {
        return $this->fechaRevision;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     *
     * @return Revisiones
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set idCaso
     *
     * @param \BufeteBundle\Entity\Casos $idCaso
     *
     * @return Revisiones
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
}

