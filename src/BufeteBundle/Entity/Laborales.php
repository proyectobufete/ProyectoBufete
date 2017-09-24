<?php

namespace BufeteBundle\Entity;

/**
 * Laborales
 */
class Laborales
{
    /**
     * @var integer
     */
    private $idTipolaboral;

    /**
     * @var \DateTime
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     */
    private $fechaFin;

    /**
     * @var float
     */
    private $salario;

    /**
     * @var boolean
     */
    private $vaciones;

    /**
     * @var boolean
     */
    private $indemnizacion;

    /**
     * @var boolean
     */
    private $diaseptimos;

    /**
     * @var boolean
     */
    private $bonoanual;

    /**
     * @var boolean
     */
    private $horasextra;

    /**
     * @var boolean
     */
    private $bonoincentivo;

    /**
     * @var boolean
     */
    private $diasasueto;

    /**
     * @var boolean
     */
    private $aguinaldo;

    /**
     * @var boolean
     */
    private $reajustesalarial;

    /**
     * @var boolean
     */
    private $salariosretenidos;

    /**
     * @var string
     */
    private $otros;

    /**
     * @var string
     */
    private $otros2;

    /**
     * @var \BufeteBundle\Entity\Casos
     */
    private $idCaso;

    /**
     * @var \BufeteBundle\Entity\Trabajos
     */
    private $idTrabajo;


    /**
     * Get idTipolaboral
     *
     * @return integer
     */
    public function getIdTipolaboral()
    {
        return $this->idTipolaboral;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Laborales
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Laborales
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set salario
     *
     * @param float $salario
     *
     * @return Laborales
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario
     *
     * @return float
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Set vaciones
     *
     * @param boolean $vaciones
     *
     * @return Laborales
     */
    public function setVaciones($vaciones)
    {
        $this->vaciones = $vaciones;

        return $this;
    }

    /**
     * Get vaciones
     *
     * @return boolean
     */
    public function getVaciones()
    {
        return $this->vaciones;
    }

    /**
     * Set indemnizacion
     *
     * @param boolean $indemnizacion
     *
     * @return Laborales
     */
    public function setIndemnizacion($indemnizacion)
    {
        $this->indemnizacion = $indemnizacion;

        return $this;
    }

    /**
     * Get indemnizacion
     *
     * @return boolean
     */
    public function getIndemnizacion()
    {
        return $this->indemnizacion;
    }

    /**
     * Set diaseptimos
     *
     * @param boolean $diaseptimos
     *
     * @return Laborales
     */
    public function setDiaseptimos($diaseptimos)
    {
        $this->diaseptimos = $diaseptimos;

        return $this;
    }

    /**
     * Get diaseptimos
     *
     * @return boolean
     */
    public function getDiaseptimos()
    {
        return $this->diaseptimos;
    }

    /**
     * Set bonoanual
     *
     * @param boolean $bonoanual
     *
     * @return Laborales
     */
    public function setBonoanual($bonoanual)
    {
        $this->bonoanual = $bonoanual;

        return $this;
    }

    /**
     * Get bonoanual
     *
     * @return boolean
     */
    public function getBonoanual()
    {
        return $this->bonoanual;
    }

    /**
     * Set horasextra
     *
     * @param boolean $horasextra
     *
     * @return Laborales
     */
    public function setHorasextra($horasextra)
    {
        $this->horasextra = $horasextra;

        return $this;
    }

    /**
     * Get horasextra
     *
     * @return boolean
     */
    public function getHorasextra()
    {
        return $this->horasextra;
    }

    /**
     * Set bonoincentivo
     *
     * @param boolean $bonoincentivo
     *
     * @return Laborales
     */
    public function setBonoincentivo($bonoincentivo)
    {
        $this->bonoincentivo = $bonoincentivo;

        return $this;
    }

    /**
     * Get bonoincentivo
     *
     * @return boolean
     */
    public function getBonoincentivo()
    {
        return $this->bonoincentivo;
    }

    /**
     * Set diasasueto
     *
     * @param boolean $diasasueto
     *
     * @return Laborales
     */
    public function setDiasasueto($diasasueto)
    {
        $this->diasasueto = $diasasueto;

        return $this;
    }

    /**
     * Get diasasueto
     *
     * @return boolean
     */
    public function getDiasasueto()
    {
        return $this->diasasueto;
    }

    /**
     * Set aguinaldo
     *
     * @param boolean $aguinaldo
     *
     * @return Laborales
     */
    public function setAguinaldo($aguinaldo)
    {
        $this->aguinaldo = $aguinaldo;

        return $this;
    }

    /**
     * Get aguinaldo
     *
     * @return boolean
     */
    public function getAguinaldo()
    {
        return $this->aguinaldo;
    }

    /**
     * Set reajustesalarial
     *
     * @param boolean $reajustesalarial
     *
     * @return Laborales
     */
    public function setReajustesalarial($reajustesalarial)
    {
        $this->reajustesalarial = $reajustesalarial;

        return $this;
    }

    /**
     * Get reajustesalarial
     *
     * @return boolean
     */
    public function getReajustesalarial()
    {
        return $this->reajustesalarial;
    }

    /**
     * Set salariosretenidos
     *
     * @param boolean $salariosretenidos
     *
     * @return Laborales
     */
    public function setSalariosretenidos($salariosretenidos)
    {
        $this->salariosretenidos = $salariosretenidos;

        return $this;
    }

    /**
     * Get salariosretenidos
     *
     * @return boolean
     */
    public function getSalariosretenidos()
    {
        return $this->salariosretenidos;
    }

    /**
     * Set otros
     *
     * @param string $otros
     *
     * @return Laborales
     */
    public function setOtros($otros)
    {
        $this->otros = $otros;

        return $this;
    }

    /**
     * Get otros
     *
     * @return string
     */
    public function getOtros()
    {
        return $this->otros;
    }

    /**
     * Set otros2
     *
     * @param string $otros2
     *
     * @return Laborales
     */
    public function setOtros2($otros2)
    {
        $this->otros2 = $otros2;

        return $this;
    }

    /**
     * Get otros2
     *
     * @return string
     */
    public function getOtros2()
    {
        return $this->otros2;
    }

    /**
     * Set idCaso
     *
     * @param \BufeteBundle\Entity\Casos $idCaso
     *
     * @return Laborales
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

    /**
     * Set idTrabajo
     *
     * @param \BufeteBundle\Entity\Trabajos $idTrabajo
     *
     * @return Laborales
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

    public function __toString()
    {
        return $this->idTipolaboral;
    }
}
