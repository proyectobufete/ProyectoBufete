<?php

namespace BufeteBundle\Entity;

/**
 * Asignacionclinica
 */
class Asignacionclinica
{
    /**
     * @var integer
     */
    private $idAsignacion;

    /**
     * @var integer
     */
    private $notaClinica;

    /**
     * @var string
     */
    private $observacionesClinica;

    /**
     * @var boolean
     */
    private $estadoAsignacionest;

    /**
     * @var \BufeteBundle\Entity\Estudiantes
     */
    private $idEstudiante;

    /**
     * @var \BufeteBundle\Entity\Clinicas
     */
    private $idClinica;


    /**
     * Get idAsignacion
     *
     * @return integer
     */
    public function getIdAsignacion()
    {
        return $this->idAsignacion;
    }

    /**
     * Set notaClinica
     *
     * @param integer $notaClinica
     *
     * @return Asignacionclinica
     */
    public function setNotaClinica($notaClinica)
    {
        $this->notaClinica = $notaClinica;

        return $this;
    }

    /**
     * Get notaClinica
     *
     * @return integer
     */
    public function getNotaClinica()
    {
        return $this->notaClinica;
    }

    /**
     * Set observacionesClinica
     *
     * @param string $observacionesClinica
     *
     * @return Asignacionclinica
     */
    public function setObservacionesClinica($observacionesClinica)
    {
        $this->observacionesClinica = $observacionesClinica;

        return $this;
    }

    /**
     * Get observacionesClinica
     *
     * @return string
     */
    public function getObservacionesClinica()
    {
        return $this->observacionesClinica;
    }

    /**
     * Set estadoAsignacionest
     *
     * @param boolean $estadoAsignacionest
     *
     * @return Asignacionclinica
     */
    public function setEstadoAsignacionest($estadoAsignacionest)
    {
        $this->estadoAsignacionest = $estadoAsignacionest;

        return $this;
    }

    /**
     * Get estadoAsignacionest
     *
     * @return boolean
     */
    public function getEstadoAsignacionest()
    {
        return $this->estadoAsignacionest;
    }

    /**
     * Set idEstudiante
     *
     * @param \BufeteBundle\Entity\Estudiantes $idEstudiante
     *
     * @return Asignacionclinica
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
     * Set idClinica
     *
     * @param \BufeteBundle\Entity\Clinicas $idClinica
     *
     * @return Asignacionclinica
     */
    public function setIdClinica(\BufeteBundle\Entity\Clinicas $idClinica = null)
    {
        $this->idClinica = $idClinica;

        return $this;
    }

    /**
     * Get idClinica
     *
     * @return \BufeteBundle\Entity\Clinicas
     */
    public function getIdClinica()
    {
        return $this->idClinica;
    }
}

