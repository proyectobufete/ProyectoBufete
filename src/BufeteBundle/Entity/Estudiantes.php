<?php

namespace BufeteBundle\Entity;

/**
 * Estudiantes
 */
class Estudiantes
{
    /**
     * @var integer
     */
    private $idEstudiante;

    /**
     * @var string
     */
    private $carneEstudiante;

    /**
     * @var boolean
     */
    private $cierrePensum;

    /**
     * @var integer
     */
    private $estadoEstudiante;

    /**
     * @var \BufeteBundle\Entity\Personas
     */
    private $idPersona;

    /**
     * @var \BufeteBundle\Entity\Tipoparcticante
     */
    private $idtipopracticante;


    /**
     * Get idEstudiante
     *
     * @return integer
     */
    public function getIdEstudiante()
    {
        return $this->idEstudiante;
    }

    /**
     * Set carneEstudiante
     *
     * @param string $carneEstudiante
     *
     * @return Estudiantes
     */
    public function setCarneEstudiante($carneEstudiante)
    {
        $this->carneEstudiante = $carneEstudiante;

        return $this;
    }

    /**
     * Get carneEstudiante
     *
     * @return string
     */
    public function getCarneEstudiante()
    {
        return $this->carneEstudiante;
    }

    /**
     * Set cierrePensum
     *
     * @param boolean $cierrePensum
     *
     * @return Estudiantes
     */
    public function setCierrePensum($cierrePensum)
    {
        $this->cierrePensum = $cierrePensum;

        return $this;
    }

    /**
     * Get cierrePensum
     *
     * @return boolean
     */
    public function getCierrePensum()
    {
        return $this->cierrePensum;
    }

    /**
     * Set estadoEstudiante
     *
     * @param integer $estadoEstudiante
     *
     * @return Estudiantes
     */
    public function setEstadoEstudiante($estadoEstudiante)
    {
        $this->estadoEstudiante = $estadoEstudiante;

        return $this;
    }

    /**
     * Get estadoEstudiante
     *
     * @return integer
     */
    public function getEstadoEstudiante()
    {
        return $this->estadoEstudiante;
    }

    /**
     * Set idPersona
     *
     * @param \BufeteBundle\Entity\Personas $idPersona
     *
     * @return Estudiantes
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

    /**
     * Set idtipopracticante
     *
     * @param \BufeteBundle\Entity\Tipoparcticante $idtipopracticante
     *
     * @return Estudiantes
     */
    public function setIdtipopracticante(\BufeteBundle\Entity\Tipoparcticante $idtipopracticante = null)
    {
        $this->idtipopracticante = $idtipopracticante;

        return $this;
    }

    /**
     * Get idtipopracticante
     *
     * @return \BufeteBundle\Entity\Tipoparcticante
     */
    public function getIdtipopracticante()
    {
        return $this->idtipopracticante;
    }
}

