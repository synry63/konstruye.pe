<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 10/4/2016
 * Time: 8:02 AM
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
 */
class Provincia
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
    @ORM\ManyToOne(targetEntity="Departamento",inversedBy="provincias")
    @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
     **/
    private $departamento;

    /**
     * @ORM\OneToMany(targetEntity="Distrito", mappedBy="provincia")
     **/
    private $distritos;


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

    /**
     * @return mixed
     */
    public function getDepartamento()
    {
        return $this->departamento;
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
    public function getDistritos()
    {
        return $this->distritos;
    }

    /**
     * @param mixed $distritos
     */
    public function setDistritos($distritos)
    {
        $this->distritos = $distritos;
    }

}