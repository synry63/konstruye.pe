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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoriaListadoProductoRepository")
 * @ORM\Table(name="categorias_listado_producto")
 */
class CategoriaListadoProducto
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
     * @ORM\ManyToMany(targetEntity="Producto", mappedBy="categoriasListado")
     */
    private $productos;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaListadoProducto", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="CategoriaListadoProducto", mappedBy="parent")
     */
    private $children;

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
     * @return mixed
     */
    public function getProductos()
    {
        return $this->productos;
    }

    /**
     * @param mixed $productos
     */
    public function setProductos($productos)
    {
        $this->productos = $productos;
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