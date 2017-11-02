<?php

namespace BufeteBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Personas
 */
class Personas implements UserInterface
{


  /**
   * @Assert\File(
   *
   *     mimeTypes = {"image/jpeg", "image/png", "image/jpg"},
   *     mimeTypesMessage = "Por favor seleccione una imagen",
   * )
   */
  protected $foto;

  /**
   * @ORM\OneToOne(targetEntity="Estudiantes", mappedBy="personas")
   *
   */
   protected $estudiantes;


    /**
     * @var integer
     */
    private $idPersona;

    /**
     * @var string
     */
    private $nombrePersona;

    /**
     * @var integer
     */
    private $telefonoPersona;

    /**
     * @var integer
     */
    private $tel2Persona;

    /**
     * @var integer
     */
    private $dpiPersona;

    /**
     * @var string
     */
    private $direccionPersona;

    /**
     * @var string
     */
    private $dir2Persona;

    /**
     * @var string
     */
    private $emailPersona;

    /**
     * @var string
     */
    private $usuarioPersona;

    /**
     * @var string
     */
    private $passPersona;

    /**
     * @var string
     */
    //private $foto;

    /**
     * @var boolean
     */
    private $estadoPersona;

    /**
     * @var string
     */
    private $role;

    /**
     * @var \BufeteBundle\Entity\Bufetes
     */
    private $idBufete;

    //AUTH
    public function getUsername()
    {
        return $this->usuarioPersona;
    }

    public function getSalt()
    {
       return null;
    }

    public function getRoles()
    {
        return array($this->getRole());
    }

    public function eraseCredentials(){

    }
    //END AUTH


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
     * Set nombrePersona
     *
     * @param string $nombrePersona
     *
     * @return Personas
     */
    public function setNombrePersona($nombrePersona)
    {
        $this->nombrePersona = $nombrePersona;

        return $this;
    }

    /**
     * Get nombrePersona
     *
     * @return string
     */
    public function getNombrePersona()
    {
        return $this->nombrePersona;
    }

    /**
     * Set telefonoPersona
     *
     * @param integer $telefonoPersona
     *
     * @return Personas
     */
    public function setTelefonoPersona($telefonoPersona)
    {
        $this->telefonoPersona = $telefonoPersona;

        return $this;
    }

    /**
     * Get telefonoPersona
     *
     * @return integer
     */
    public function getTelefonoPersona()
    {
        return $this->telefonoPersona;
    }

    /**
     * Set tel2Persona
     *
     * @param integer $tel2Persona
     *
     * @return Personas
     */
    public function setTel2Persona($tel2Persona)
    {
        $this->tel2Persona = $tel2Persona;

        return $this;
    }

    /**
     * Get tel2Persona
     *
     * @return integer
     */
    public function getTel2Persona()
    {
        return $this->tel2Persona;
    }

    /**
     * Set dpiPersona
     *
     * @param integer $dpiPersona
     *
     * @return Personas
     */
    public function setDpiPersona($dpiPersona)
    {
        $this->dpiPersona = $dpiPersona;

        return $this;
    }

    /**
     * Get dpiPersona
     *
     * @return integer
     */
    public function getDpiPersona()
    {
        return $this->dpiPersona;
    }

    /**
     * Set direccionPersona
     *
     * @param string $direccionPersona
     *
     * @return Personas
     */
    public function setDireccionPersona($direccionPersona)
    {
        $this->direccionPersona = $direccionPersona;

        return $this;
    }

    /**
     * Get direccionPersona
     *
     * @return string
     */
    public function getDireccionPersona()
    {
        return $this->direccionPersona;
    }

    /**
     * Set dir2Persona
     *
     * @param string $dir2Persona
     *
     * @return Personas
     */
    public function setDir2Persona($dir2Persona)
    {
        $this->dir2Persona = $dir2Persona;

        return $this;
    }

    /**
     * Get dir2Persona
     *
     * @return string
     */
    public function getDir2Persona()
    {
        return $this->dir2Persona;
    }

    /**
     * Set emailPersona
     *
     * @param string $emailPersona
     *
     * @return Personas
     */
    public function setEmailPersona($emailPersona)
    {
        $this->emailPersona = $emailPersona;

        return $this;
    }

    /**
     * Get emailPersona
     *
     * @return string
     */
    public function getEmailPersona()
    {
        return $this->emailPersona;
    }

    /**
     * Set usuarioPersona
     *
     * @param string $usuarioPersona
     *
     * @return Personas
     */
    public function setUsuarioPersona($usuarioPersona)
    {
        $this->usuarioPersona = $usuarioPersona;

        return $this;
    }

    /**
     * Get usuarioPersona
     *
     * @return string
     */
    public function getUsuarioPersona()
    {
        return $this->usuarioPersona;
    }

    /**
     * Set passPersona
     *
     * @param string $passPersona
     *
     * @return Personas
     */
    public function setPassPersona($passPersona)
    {
        $this->passPersona = $passPersona;

        return $this;
    }

    /**
     * Get passPersona
     *
     * @return string
     */
    public function getPassPersona()
    {
        return $this->passPersona;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->passPersona;
    }

    /**
     * Set foto
     *
     * @param string $foto
     *
     * @return Personas
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set estadoPersona
     *
     * @param boolean $estadoPersona
     *
     * @return Personas
     */
    public function setEstadoPersona($estadoPersona)
    {
        $this->estadoPersona = $estadoPersona;

        return $this;
    }

    /**
     * Get estadoPersona
     *
     * @return boolean
     */
    public function getEstadoPersona()
    {
        return $this->estadoPersona;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Personas
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set idBufete
     *
     * @param \BufeteBundle\Entity\Bufetes $idBufete
     *
     * @return Personas
     */
    public function setIdBufete(\BufeteBundle\Entity\Bufetes $idBufete = null)
    {
        $this->idBufete = $idBufete;

        return $this;
    }

    /**
     * Get idBufete
     *
     * @return \BufeteBundle\Entity\Bufetes
     */
    public function getIdBufete()
    {
        return $this->idBufete;
    }

    public function __toString()
    {
        return $this->nombrePersona;
    }

    /**
     * Set Estudiantes
     *
     * @param \BufeteBundle\Entity\Estudiantes $estudiantes
     * @return Personas
     */
    public function setEstudiantes(\BufeteBundle\Entity\Estudiantes $estudiantes = null)
    {
      $this->estudiantes = $estudiantes;
      $estudiantes->setIdPersona($this);
      return $this;
    }

    /**
     * Get estudiantes
     *
     * @return \BufeteBundle\Entity\Estudiantes
     */
     public function getEstudiantes()
     {
       return $this->estudiantes;
     }
}
