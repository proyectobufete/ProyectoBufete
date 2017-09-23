<?php

namespace BufeteBundle\Entity;

/**
 * Tipoasunto
 */
class Tipoasunto
{
    /**
     * @var integer
     */
    private $idTipoasunto;

    /**
     * @var string
     */
    private $asunto;


    /**
     * Get idTipoasunto
     *
     * @return integer
     */
    public function getIdTipoasunto()
    {
        return $this->idTipoasunto;
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     *
     * @return Tipoasunto
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }
}

