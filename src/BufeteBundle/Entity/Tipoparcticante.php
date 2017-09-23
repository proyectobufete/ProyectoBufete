<?php

namespace BufeteBundle\Entity;

/**
 * Tipoparcticante
 */
class Tipoparcticante
{
    /**
     * @var integer
     */
    private $idtipopracticante;

    /**
     * @var string
     */
    private $tipopracticante;


    /**
     * Get idtipopracticante
     *
     * @return integer
     */
    public function getIdtipopracticante()
    {
        return $this->idtipopracticante;
    }

    /**
     * Set tipopracticante
     *
     * @param string $tipopracticante
     *
     * @return Tipoparcticante
     */
    public function setTipopracticante($tipopracticante)
    {
        $this->tipopracticante = $tipopracticante;

        return $this;
    }

    /**
     * Get tipopracticante
     *
     * @return string
     */
    public function getTipopracticante()
    {
        return $this->tipopracticante;
    }
}

