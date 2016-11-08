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
     * @ORM\Column(type="string",length=64,nullable=true)
     */
    private $paraUsoTipo;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $parametrosMunicipales;

    /**
     * @ORM\OneToOne(targetEntity="Operacion",cascade={"persist"})
     * @ORM\JoinColumn(name="operacion_id", referencedColumnName="id")
     */
    private $operacion;

    /**
     * @ORM\OneToOne(targetEntity="Structure",cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id")
     */
    private $structure;

    /**
     * @ORM\OneToMany(targetEntity="ServicioInmueble", mappedBy="inmueble")
     */
    private $sevicios;
    /**
     * @ORM\OneToMany(targetEntity="GeneralInmueble", mappedBy="inmueble")
     */
    private $generales;

    /**
    @ORM\ManyToOne(targetEntity="ConstructoraInmobiliaria",inversedBy="inmuebles")
    @ORM\JoinColumn(name="constructora_id", referencedColumnName="id")
     **/
    private $constructora;

    /**
     * @ORM\OneToOne(targetEntity="Anunciante",mappedBy="negocio",cascade={"persist"})
     */
    private $anunciante;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $sort;

    /**
     * @return mixed
     */
    public function getParaUsoTipo()
    {
        return $this->paraUsoTipo;
    }

    /**
     * @param mixed $paraUsoTipo
     */
    public function setParaUsoTipo($paraUsoTipo)
    {
        $this->paraUsoTipo = $paraUsoTipo;
    }

    /**
     * @return mixed
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;
    }


    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }



    /**
     * @return mixed
     */
    public function getConstructora()
    {
        return $this->constructora;
    }

    /**
     * @param mixed $constructora
     */
    public function setConstructora($constructora)
    {
        $this->constructora = $constructora;
    }



    /**
     * @return mixed
     */
    public function getGenerales()
    {
        return $this->generales;
    }

    /**
     * @return mixed
     */
    public function getAnunciante()
    {
        return $this->anunciante;
    }

    /**
     * @param mixed $anunciante
     */
    public function setAnunciante($anunciante)
    {
        $this->anunciante = $anunciante;
    }

    /**
     * @param mixed $generales
     */
    public function setGenerales($generales)
    {
        $this->generales = $generales;
    }
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
     * @return mixed
     */
    public function getPrecioSoles()
    {
        return $this->precioSoles;
    }

    /**
     * @param mixed $precioSoles
     */
    public function setPrecioSoles($precioSoles)
    {
        $this->precioSoles = $precioSoles;
    }

    /**
     * @return mixed
     */
    public function getPrecioDolares()
    {
        return $this->precioDolares;
    }

    /**
     * @param mixed $precioDolares
     */
    public function setPrecioDolares($precioDolares)
    {
        $this->precioDolares = $precioDolares;
    }

    /**
     * @return mixed
     */
    public function getAreaTotal()
    {
        return $this->areaTotal;
    }

    /**
     * @param mixed $areaTotal
     */
    public function setAreaTotal($areaTotal)
    {
        $this->areaTotal = $areaTotal;
    }

    /**
     * @return mixed
     */
    public function getAreaTechada()
    {
        return $this->areaTechada;
    }

    /**
     * @param mixed $areaTechada
     */
    public function setAreaTechada($areaTechada)
    {
        $this->areaTechada = $areaTechada;
    }

    /**
     * @return mixed
     */
    public function getDormitorios()
    {
        return $this->dormitorios;
    }

    /**
     * @param mixed $dormitorios
     */
    public function setDormitorios($dormitorios)
    {
        $this->dormitorios = $dormitorios;
    }

    /**
     * @return mixed
     */
    public function getMedioBanos()
    {
        return $this->medioBanos;
    }

    /**
     * @param mixed $medioBanos
     */
    public function setMedioBanos($medioBanos)
    {
        $this->medioBanos = $medioBanos;
    }

    /**
     * @return mixed
     */
    public function getStacionamientos()
    {
        return $this->stacionamientos;
    }

    /**
     * @param mixed $stacionamientos
     */
    public function setStacionamientos($stacionamientos)
    {
        $this->stacionamientos = $stacionamientos;
    }

    /**
     * @return mixed
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * @param mixed $unidades
     */
    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;
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