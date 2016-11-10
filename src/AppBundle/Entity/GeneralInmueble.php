<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 3:25 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
/**
 * @ORM\Entity
 * @ORM\Table(name="generales_inmuebles")
 */
class GeneralInmueble
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    @ORM\ManyToOne(targetEntity="Inmueble",inversedBy="generales")
    @ORM\JoinColumn(name="inmueble_id", referencedColumnName="id")
     **/
    private $inmueble;

    /**
    @ORM\ManyToOne(targetEntity="General",inversedBy="inmuebles")
    @ORM\JoinColumn(name="general_id", referencedColumnName="id")
     **/
    private $general;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }



    /**
     * @return mixed
     */
    public function getGeneral()
    {
        return $this->general;
    }

    /**
     * @param mixed $general
     */
    public function setGeneral($general)
    {
        $this->general = $general;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getInmueble()
    {
        return $this->inmueble;
    }

    /**
     * @param mixed $inmueble
     */
    public function setInmueble($inmueble)
    {
        $this->inmueble = $inmueble;
    }


    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function __construct()
    {
        $this->cantidad = 1;
    }

}