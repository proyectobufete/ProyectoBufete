<?php

namespace BufeteBundle\Entity;

/**
 * Infofinal
 */
class Infofinal
{
    /**
     * @var integer
     */
    private $idIfno;

    /**
     * @var \DateTime
     */
    private $fechaInfo;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var string
     */
    private $rutaInfo;

    /**
     * @var \BufeteBundle\Entity\Casos
     */
    private $idCaso;


    /**
     * Get idIfno
     *
     * @return integer
     */
    public function getIdIfno()
    {
        return $this->idIfno;
    }

    /**
     * Set fechaInfo
     *
     * @param \DateTime $fechaInfo
     *
     * @return Infofinal
     */
    public function setFechaInfo($fechaInfo)
    {
        $this->fechaInfo = $fechaInfo;

        return $this;
    }

    /**
     * Get fechaInfo
     *
     * @return \DateTime
     */
    public function getFechaInfo()
    {
        return $this->fechaInfo;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Infofinal
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
     * Set rutaInfo
     *
     * @param string $rutaInfo
     *
     * @return Infofinal
     */
    public function setRutaInfo($rutaInfo)
    {
        $this->rutaInfo = $rutaInfo;

        return $this;
    }

    /**
     * Get rutaInfo
     *
     * @return string
     */
    public function getRutaInfo()
    {
        return $this->rutaInfo;
    }

    /**
     * Set idCaso
     *
     * @param \BufeteBundle\Entity\Casos $idCaso
     *
     * @return Infofinal
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

