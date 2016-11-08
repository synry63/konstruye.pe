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
 * @ORM\Table(name="servicios_inmuebles")
 */
class ServicioInmueble
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    @ORM\ManyToOne(targetEntity="Inmueble",inversedBy="servicios")
    @ORM\JoinColumn(name="inmueble_id", referencedColumnName="id")
     **/
    private $inmueble;

    /**
    @ORM\ManyToOne(targetEntity="Servicio",inversedBy="inmuebles")
    @ORM\JoinColumn(name="servicio_id", referencedColumnName="id")
     **/
    private $servicio;

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
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * @param mixed $servicio
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;
    }

    public function __construct()
    {
        $this->cantidad = 1;
    }
}