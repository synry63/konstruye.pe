<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 12:58 PM
 */
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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InmuebleRepository")
 */
class Inmueble extends Negocio
{
    /**
     * @var decimal
     */
    /** @ORM\Column(type="decimal",precision=10,scale=2,nullable=true) **/
    private $precioSoles;

    /**
     * @var decimal
     */
    /** @ORM\Column(type="decimal",precision=10,scale=2,nullable=true) **/
    private $precioDolares;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $areaTotal;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $areaTechada;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $dormitorios;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $banos;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $medioBanos;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $stacionamientos;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $unidades;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $parametrosMunicipales;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $structure;

    /**
     * @ORM\OneToMany(targetEntity="ServicioInmueble", mappedBy="inmueble")
     */
    private $sevicios;

    /**
     * @return mixed
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param mixed $structure
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }

    /**
     * @return mixed
     */
    public function getSevicios()
    {
        return $this->sevicios;
    }

    /**
     * @param mixed $sevicios
     */
    public function setSevicios($sevicios)
    {
        $this->sevicios = $sevicios;
    }



    /**
     * @return mixed
     */
    public function getBanos()
    {
        return $this->banos;
    }

    /**
     * @param mixed $banos
     */
    public function setBanos($banos)
    {
        $this->banos = $banos;
    }


    /**
     * @param mixed $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    /**
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $parametrosMunicipales
     */
    public function setParametrosMunicipales($parametrosMunicipales)
    {
        $this->parametrosMunicipales = $parametrosMunicipales;
    }

    /**
     * @return mixed
     */
    public function getParametrosMunicipales()
    {
        return $this->parametrosMunicipales;
    }

}