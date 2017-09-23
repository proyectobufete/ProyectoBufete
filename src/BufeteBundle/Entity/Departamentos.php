<?php

namespace BufeteBundle\Entity;

/**
 * Departamentos
 */
class Departamentos
{
    /**
     * @var integer
     */
    private $idDepartamento;

    /**
     * @var string
     */
    private $departamento;

    /**
     * @var \BufeteBundle\Entity\Paises
     */
    private $idPais;


    /**
     * Get idDepartamento
     *
     * @return integer
     */
    public function getIdDepartamento()
    {
        return $this->idDepartamento;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     *
     * @return Departamentos
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return string
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set idPais
     *
     * @param \BufeteBundle\Entity\Paises $idPais
     *
     * @return Departamentos
     */
    public function setIdPais(\BufeteBundle\Entity\Paises $idPais = null)
    {
        $this->idPais = $idPais;

        return $this;
    }

    /**
     * Get idPais
     *
     * @return \BufeteBundle\Entity\Paises
     */
    public function getIdPais()
    {
        return $this->idPais;
    }
}

