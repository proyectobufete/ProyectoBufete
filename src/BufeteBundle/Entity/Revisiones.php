<?php

namespace BufeteBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Revisiones
 */
class Revisiones
{

    /**
     * @Assert\File(
     *     maxSize = "5000k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Por favor seleccione un archivo en formato PDF"
     * )
     */
    protected $rutaArchivo;


    /**
     * @var integer
     */
    private $idRevision;

    /**
     * @var integer
     */
    private $idPersona;

    /**
     * @var string
     */
    private $tituloEntrega;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var string
     */
    private $nombreArchivo;

    /**
     * @var string
     */
    //private $rutaArchivo;

    /**
     * @var \DateTime
     */
    private $fechaLimite;

    /**
     * @var string
     */
    private $comentarios;

    /**
     * @var \DateTime
     */
    private $fechaEnvio;

    /**
     * @var integer
     */
    private $estadoRevision;

    /**
     * @var \BufeteBundle\Entity\Casos
     */
    private $idCaso;





    /**
     * @var integer
     */
    private $idRevisado;





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
     * Set idPersona
     *
     * @param integer $idPersona
     *
     * @return Revisiones
     */
    public function setIdPersona($idPersona)
    {
        $this->idPersona = $idPersona;

        return $this;
    }

    /**
     * Get idPersona
     *
     * @return integer
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set tituloEntrega
     *
     * @param string $tituloEntrega
     *
     * @return Revisiones
     */
    public function setTituloEntrega($tituloEntrega)
    {
        $this->tituloEntrega = $tituloEntrega;

        return $this;
    }

    /**
     * Get tituloEntrega
     *
     * @return string
     */
    public function getTituloEntrega()
    {
        return $this->tituloEntrega;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Revisiones
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = new \DateTime("now");
        //$this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set nombreArchivo
     *
     * @param string $nombreArchivo
     *
     * @return Revisiones
     */
    public function setNombreArchivo($nombreArchivo)
    {
        $this->nombreArchivo = $nombreArchivo;

        return $this;
    }

    /**
     * Get nombreArchivo
     *
     * @return string
     */
    public function getNombreArchivo()
    {
        return $this->nombreArchivo;
    }

    /**
     * Set rutaArchivo
     *
     * @param string $rutaArchivo
     *
     * @return Revisiones
     */
    public function setRutaArchivo($rutaArchivo)
    {
        $this->rutaArchivo = $rutaArchivo;

        return $this;
    }

    /**
     * Get rutaArchivo
     *
     * @return string
     */
    public function getRutaArchivo()
    {
        return $this->rutaArchivo;
    }

    /**
     * Set fechaLimite
     *
     * @param \DateTime $fechaLimite
     *
     * @return Revisiones
     */
    public function setFechaLimite($fechaLimite)
    {
        $this->fechaLimite = $fechaLimite;

        return $this;
    }

    /**
     * Get fechaLimite
     *
     * @return \DateTime
     */
    public function getFechaLimite()
    {
        return $this->fechaLimite;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return Revisiones
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
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
        $this->fechaEnvio = new \DateTime("now");
        //$this->fechaEnvio = $fechaEnvio;

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
     * Set estadoRevision
     *
     * @param integer $estadoRevision
     *
     * @return Revisiones
     */
    public function setEstadoRevision($estadoRevision)
    {
        $this->estadoRevision = $estadoRevision;

        return $this;
    }

    /**
     * Get estadoRevision
     *
     * @return integer
     */
    public function getEstadoRevision()
    {
        return $this->estadoRevision;
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


    /**
     * Set idRevisado
     *
     * @param integer $idRevisado
     *
     * @return Revisiones
     */
    public function setIdRevisado($idRevisado)
    {
        $this->idRevisado = $idRevisado;

        return $this;
    }

    /**
     * Get idRevisado
     *
     * @return integer
     */
    public function getIdRevisado()
    {
        return $this->idRevisado;
    }
}
