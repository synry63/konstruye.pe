<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/6/16
 * Time: 11:34 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoriaListadoRepository")
 * @ORM\Table(name="categorias_listado")
 */
class CategoriaListado
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
     * @ORM\Column(type="string", length=100)
     */
    private $slug;
    /**
     * @ORM\ManyToMany(targetEntity="Negocio", mappedBy="categoriasListado")
     */
    private $negocios;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaListado", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="CategoriaListado", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $imgSmall;

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {


        return $this->children;
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
     * @param mixed $img_small
     */
    public function setImgSmall($img_small)
    {
        $this->imgSmall = $img_small;
    }

    /**
     * @return mixed
     */
    public function getImgSmall()
    {
        return $this->imgSmall;
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
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $proveedores
     */
    public function setNegocios($proveedores)
    {
        $this->negocios = $proveedores;
    }

    /**
     * @return mixed
     */
    public function getNegocios()
    {
        return $this->negocios;
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


    public function __construct() {
        $this->negocios = new ArrayCollection();
        $this->children = new ArrayCollection();
    }


}