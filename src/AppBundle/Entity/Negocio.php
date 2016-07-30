<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/1/16
 * Time: 1:43 PM
 */
// src/AppBundle/Entity/Product.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NegocioRepository")
 * @Vich\Uploadable
 * @ORM\Table(name="negocios")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"negocio" = "Negocio",
 *                        "inmueble" = "Inmueble",
 *                        "constructura-inmobiliaria" = "ConstructoraInmobiliaria",
 *                        "especialista" = "Especialista",
 *                        "proveedor" = "Proveedor"
 * })
 */
class Negocio
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $web;

    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default": false})
     */
    private $isAccepted;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=64)
     *
     */
    private $departamento;

    /**
     * @ORM\Column(type="string", length=64)
     *
     */
    private $distrito;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $registeredAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @var File
     */
    private $tempFile;

    /**
     * @return mixed
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }
    /**
     * @param mixed $isAccepted
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
    }



    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getTempFile()
    {
        return $this->tempFile;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $tempFile
     */
    public function setTempFile($tempFile)
    {
        $this->tempFile = $tempFile;
    }

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $tags;


    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="negocio")
     **/
    private $productos;

    /**
     * @ORM\OneToMany(targetEntity="Foto", mappedBy="negocio")
     **/
    private $fotos;
    /**
     * @ORM\ManyToMany(targetEntity="CategoriaListado", inversedBy="negocios")
     * @ORM\JoinTable(name="negocios_categorias_listado")
     */
    private $categoriasListado;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioNegocio", mappedBy="negocio")
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="negocios")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;


    /**
     * @ORM\OneToOne(targetEntity="Logo",mappedBy="negocio",cascade={"persist"})
     */
    private $logo;

    /**
     * @ORM\OneToOne(targetEntity="Banner",mappedBy="negocio",cascade={"persist"})
     */
    private $banner;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $facebookLink;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $twitterLink;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $pinteresLink;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $instagramLink;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $googleMapLat;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $googleMapLng;

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return mixed
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @param mixed $instagramLink
     */
    public function setInstagramLink($instagramLink)
    {
        $this->instagramLink = $instagramLink;
    }

    /**
     * @return mixed
     */
    public function getInstagramLink()
    {
        return $this->instagramLink;
    }

    /**
     * @param mixed $pinteresLink
     */
    public function setPinteresLink($pinteresLink)
    {
        $this->pinteresLink = $pinteresLink;
    }

    /**
     * @return mixed
     */
    public function getPinteresLink()
    {
        return $this->pinteresLink;
    }

    /**
     * @param mixed $twitterLink
     */
    public function setTwitterLink($twitterLink)
    {
        $this->twitterLink = $twitterLink;
    }

    /**
     * @return mixed
     */
    public function getTwitterLink()
    {
        return $this->twitterLink;
    }


    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @param mixed $departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return mixed
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param mixed $distrito
     */
    public function setDistrito($distrito)
    {
        $this->distrito = $distrito;
    }

    /**
     * @return mixed
     */
    public function getDistrito()
    {
        return $this->distrito;
    }



    public function __construct() {
        $this->registeredAt = new \DateTime('now');
        $this->comentarios = new ArrayCollection();
        $this->productos = new ArrayCollection();
        $this->categoriasListado = new ArrayCollection();
    }


    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }


    /**
     * @param mixed $comentarios_proveedor
     */
    public function setComentarios($comentarios_proveedor)
    {
        $this->comentarios = $comentarios_proveedor;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $categoriasListado
     */
    public function setCategoriasListado($categoriasListado)
    {
        $this->categoriasListado = $categoriasListado;
    }

    /**
     * @return mixed
     */
    public function getCategoriasListado()
    {
        return $this->categoriasListado;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getGoogleMapLat()
    {
        return $this->googleMapLat;
    }

    /**
     * @param mixed $googleMapLat
     */
    public function setGoogleMapLat($googleMapLat)
    {
        $this->googleMapLat = $googleMapLat;
    }

    /**
     * @return mixed
     */
    public function getGoogleMapLng()
    {
        return $this->googleMapLng;
    }

    /**
     * @param mixed $googleMapLng
     */
    public function setGoogleMapLng($googleMapLng)
    {
        $this->googleMapLng = $googleMapLng;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $productos
     */
    public function setProductos($productos)
    {
        $this->productos = $productos;
    }

    /**
     * @return mixed
     */
    public function getProductos()
    {
        return $this->productos;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return mixed
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param mixed $fotos
     */
    public function setFotos($fotos)
    {
        $this->fotos = $fotos;
    }

    /**
     * @return mixed
     */
    public function getFotos()
    {
        return $this->fotos;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        //var_dump($this->categoriasListado);
        if(count($this->categoriasListado)==0){
            $context->buildViolation('Selecione un listado como minimo')
                ->atPath('categoriasListado')
                ->addViolation();
        }

    }
    /**
     * @param \DateTime $registeredAt
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

}