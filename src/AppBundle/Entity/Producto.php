<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/6/16
 * Time: 11:36 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="productos")
 */
class Producto
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
    private $slug;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $img_small;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $img_large;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     @ORM\ManyToOne(targetEntity="Negocio",inversedBy="productos")
     @ORM\JoinColumn(name="negocio_id", referencedColumnName="id")
     **/
    private $negocio;

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
     * @param mixed $img_large
     */
    public function setImgLarge($img_large)
    {
        $this->img_large = $img_large;
    }

    /**
     * @return mixed
     */
    public function getImgLarge()
    {
        return $this->img_large;
    }

    /**
     * @param mixed $img_small
     */
    public function setImgSmall($img_small)
    {
        $this->img_small = $img_small;
    }

    /**
     * @return mixed
     */
    public function getImgSmall()
    {
        return $this->img_small;
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
     * @param mixed $proveedor
     */
    public function setNegocio($proveedor)
    {
        $this->negocio = $proveedor;
    }

    /**
     * @return mixed
     */
    public function getNegocio()
    {
        return $this->negocio;
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







}