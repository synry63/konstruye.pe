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
     * @Assert\NotBlank(message="Ingrese sus Nombres.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=64,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     * )
     */
    protected $nombres;

    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     *
     * @Assert\NotBlank(message="Ingrese el nombre de su empresa.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=64,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     * )
     */
    protected $nombreEmpresa;

    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     *
     * @Assert\NotBlank(message="Ingrese sus Apellidos", groups={"Registration", "Profile"})
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
     * @ORM\Column(type="string",nullable=true, length=8)
     *
     * @Assert\NotBlank(message="Ingrese su DNI.", groups={"Registration", "Profile"})
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
     * @ORM\Column(type="string",nullable=true, length=11)
     *
     * @Assert\NotBlank(message="Ingrese su RUC.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=11,
     *     max=11,
     *     minMessage="Must be exactly 11.",
     *     maxMessage="Must be exactly 11.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $ruc;



    /**
     * @ORM\OneToOne(targetEntity="FotoProfile",mappedBy="user",cascade={"persist"})
     */
    private $profile;

    /**
     * @ORM\Column(type="string",nullable=true, length=10)
     *
     */
    protected $telefono;

    /**
     * @return mixed
     */
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }

    /**
     * @return mixed
     */
    public function getRuc()
    {
        return $this->ruc;
    }

    /**
     * @param mixed $ruc
     */
    public function setRuc($ruc)
    {
        $this->ruc = $ruc;
    }
    /**
     * @param mixed $nombreEmpresa
     */
    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;
    }

    /**
     * @ORM\Column(type="string", length=1)
     *
     */
    protected $isWhat;

    /**
     * @ORM\OneToMany(targetEntity="Negocio", mappedBy="user")
     */
    private $negocios;

    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * @return mixed
     */
    public function getIsWhat()
    {
        return $this->isWhat;
    }

    /**
     * @param mixed $isWhat
     */
    public function setIsWhat($isWhat)
    {
        $this->isWhat = $isWhat;
    }

    /**
     * @param mixed $facebook_id
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @param mixed $facebook_access_token
     */
    public function setFacebookAccessToken($facebook_access_token)
    {
        $this->facebook_access_token = $facebook_access_token;
    }

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