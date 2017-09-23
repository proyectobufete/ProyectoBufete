<?php

namespace BufeteBundle\Entity;

/**
 * Records
 */
class Records
{
    /**
     * @var integer
     */
    private $idRecord;

    /**
     * @var \DateTime
     */
    private $inscripcionRecord;

    /**
     * @var \DateTime
     */
    private $solvenciaRecord;

    /**
     * @var \BufeteBundle\Entity\Casos
     */
    private $idCaso;


    /**
     * Get idRecord
     *
     * @return integer
     */
    public function getIdRecord()
    {
        return $this->idRecord;
    }

    /**
     * Set inscripcionRecord
     *
     * @param \DateTime $inscripcionRecord
     *
     * @return Records
     */
    public function setInscripcionRecord($inscripcionRecord)
    {
        $this->inscripcionRecord = $inscripcionRecord;

        return $this;
    }

    /**
     * Get inscripcionRecord
     *
     * @return \DateTime
     */
    public function getInscripcionRecord()
    {
        return $this->inscripcionRecord;
    }

    /**
     * Set solvenciaRecord
     *
     * @param \DateTime $solvenciaRecord
     *
     * @return Records
     */
    public function setSolvenciaRecord($solvenciaRecord)
    {
        $this->solvenciaRecord = $solvenciaRecord;

        return $this;
    }

    /**
     * Get solvenciaRecord
     *
     * @return \DateTime
     */
    public function getSolvenciaRecord()
    {
        return $this->solvenciaRecord;
    }

    /**
     * Set idCaso
     *
     * @param \BufeteBundle\Entity\Casos $idCaso
     *
     * @return Records
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

