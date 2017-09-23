<?php

namespace BufeteBundle\Entity;

/**
 * Civiles
 */
class Civiles
{
    /**
     * @var integer
     */
    private $idCivil;

    /**
     * @var string
     */
    private $pretencion;

    /**
     * @var \BufeteBundle\Entity\Casos
     */
    private $idCaso;


    /**
     * Get idCivil
     *
     * @return integer
     */
    public function getIdCivil()
    {
        return $this->idCivil;
    }

    /**
     * Set pretencion
     *
     * @param string $pretencion
     *
     * @return Civiles
     */
    public function setPretencion($pretencion)
    {
        $this->pretencion = $pretencion;

        return $this;
    }

    /**
     * Get pretencion
     *
     * @return string
     */
    public function getPretencion()
    {
        return $this->pretencion;
    }

    /**
     * Set idCaso
     *
     * @param \BufeteBundle\Entity\Casos $idCaso
     *
     * @return Civiles
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

