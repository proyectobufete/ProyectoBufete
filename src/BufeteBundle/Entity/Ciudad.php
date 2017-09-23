<?php

namespace BufeteBundle\Entity;

/**
 * Ciudad
 */
class Ciudad
{
    /**
     * @var integer
     */
    private $idCiudad;

    /**
     * @var integer
     */
    private $postalCiudad;

    /**
     * @var string
     */
    private $ciudad;

    /**
     * @var boolean
     */
    private $estadoCiudad;

    /**
     * @var \BufeteBundle\Entity\Departamentos
     */
    private $idDepartamento;


    /**
     * Get idCiudad
     *
     * @return integer
     */
    public function getIdCiudad()
    {
        return $this->idCiudad;
    }

    /**
     * Set postalCiudad
     *
     * @param integer $postalCiudad
     *
     * @return Ciudad
     */
    public function setPostalCiudad($postalCiudad)
    {
        $this->postalCiudad = $postalCiudad;

        return $this;
    }

    /**
     * Get postalCiudad
     *
     * @return integer
     */
    public function getPostalCiudad()
    {
        return $this->postalCiudad;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Ciudad
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set estadoCiudad
     *
     * @param boolean $estadoCiudad
     *
     * @return Ciudad
     */
    public function setEstadoCiudad($estadoCiudad)
    {
        $this->estadoCiudad = $estadoCiudad;

        return $this;
    }

    /**
     * Get estadoCiudad
     *
     * @return boolean
     */
    public function getEstadoCiudad()
    {
        return $this->estadoCiudad;
    }

    /**
     * Set idDepartamento
     *
     * @param \BufeteBundle\Entity\Departamentos $idDepartamento
     *
     * @return Ciudad
     */
    public function setIdDepartamento(\BufeteBundle\Entity\Departamentos $idDepartamento = null)
    {
        $this->idDepartamento = $idDepartamento;

        return $this;
    }

    /**
     * Get idDepartamento
     *
     * @return \BufeteBundle\Entity\Departamentos
     */
    public function getIdDepartamento()
    {
        return $this->idDepartamento;
    }
}

