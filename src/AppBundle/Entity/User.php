<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:50 PM
 */
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="usuarios")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=64,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $nombres;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your lastnames.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=64,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $apellidos;

    /**
     * @ORM\Column(type="string", length=8)
     *
     * @Assert\NotBlank(message="Please enter your DNI.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     minMessage="Must be exactly 8.",
     *     maxMessage="Must be exactly 8.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $dni;

    /**
     * @ORM\OneToOne(targetEntity="FotoProfile",mappedBy="user",cascade={"persist"})
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="Negocio", mappedBy="user")
     */
    private $negocios;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @param mixed $negocios
     */
    public function setNegocios($negocios)
    {
        $this->negocios = $negocios;
    }

    /**
     * @return mixed
     */
    public function getNegocios()
    {
        return $this->negocios;
    }

    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param mixed $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }


    /**
     * @param mixed $nombres
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    /**
     * @return mixed
     */
    public function getNombres()
    {
        return $this->nombres;
    }


}