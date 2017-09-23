<?php

namespace BufeteBundle\Entity;

/**
 * Tribunales
 */
class Tribunales
{
    /**
     * @var integer
     */
    private $idTribunal;

    /**
     * @var string
     */
    private $tribunal;

    /**
     * @var boolean
     */
    private $estadoTribunal;


    /**
     * Get idTribunal
     *
     * @return integer
     */
    public function getIdTribunal()
    {
        return $this->idTribunal;
    }

    /**
     * Set tribunal
     *
     * @param string $tribunal
     *
     * @return Tribunales
     */
    public function setTribunal($tribunal)
    {
        $this->tribunal = $tribunal;

        return $this;
    }

    /**
     * Get tribunal
     *
     * @return string
     */
    public function getTribunal()
    {
        return $this->tribunal;
    }

    /**
     * Set estadoTribunal
     *
     * @param boolean $estadoTribunal
     *
     * @return Tribunales
     */
    public function setEstadoTribunal($estadoTribunal)
    {
        $this->estadoTribunal = $estadoTribunal;

        return $this;
    }

    /**
     * Get estadoTribunal
     *
     * @return boolean
     */
    public function getEstadoTribunal()
    {
        return $this->estadoTribunal;
    }
}

