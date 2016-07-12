<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 7/6/16
 * Time: 10:33 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="proyectos")
 */
class Proyecto
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
     * @ORM\OneToMany(targetEntity="FotoProyecto", mappedBy="proyecto")
     **/
    private $fotos;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $description;

    /**
    @ORM\ManyToOne(targetEntity="Negocio",inversedBy="proyectos")
    @ORM\JoinColumn(name="negocio_id", referencedColumnName="id")
     **/
    private $negocio;

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
     * @param mixed $negocio
     */
    public function setNegocio($negocio)
    {
        $this->negocio = $negocio;
    }

    /**
     * @return mixed
     */
    public function getNegocio()
    {
        return $this->negocio;
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